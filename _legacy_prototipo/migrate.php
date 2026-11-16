<?php

// migrate.php - Migración y datos iniciales para SotePass Expo (BD: agrosorteo)

$host = '127.0.0.1';
$db = 'agrosorteo';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=$charset", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    echo "Conectado a MySQL: $db\n";

    // 1. Tabla: stands (Empresas expositoras)
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS stands (
            id INT AUTO_INCREMENT PRIMARY KEY,
            slug VARCHAR(60) NOT NULL UNIQUE,
            name VARCHAR(120) NOT NULL,
            tagline VARCHAR(255) NULL,
            stand_number VARCHAR(50) NOT NULL,
            sector VARCHAR(100) NOT NULL,
            primary_color VARCHAR(20) DEFAULT '#16a34a',
            secondary_color VARCHAR(20) DEFAULT '#0f172a',
            logo_url VARCHAR(255) NULL,
            access_pin VARCHAR(20) NOT NULL DEFAULT '1234',
            allow_spins TINYINT(1) DEFAULT 1,
            max_spins_per_lead INT DEFAULT 1,
            is_active TINYINT(1) DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");
    echo "✔ Tabla 'stands' creada.\n";

    // 2. Tabla: prizes (Premios de la ruleta por stand)
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS prizes (
            id INT AUTO_INCREMENT PRIMARY KEY,
            stand_id INT NOT NULL,
            name VARCHAR(100) NOT NULL,
            color VARCHAR(30) DEFAULT '#22c55e',
            text_color VARCHAR(30) DEFAULT '#ffffff',
            total_stock INT NOT NULL DEFAULT 50,
            remaining_stock INT NOT NULL DEFAULT 50,
            probability_weight INT NOT NULL DEFAULT 10,
            is_real_prize TINYINT(1) DEFAULT 1,
            icon_badge VARCHAR(50) DEFAULT 'gift',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (stand_id) REFERENCES stands(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");
    echo "✔ Tabla 'prizes' creada.\n";

    // 3. Tabla: leads (Visitantes registrados en el stand)
    $pdo->exec('
        CREATE TABLE IF NOT EXISTS leads (
            id INT AUTO_INCREMENT PRIMARY KEY,
            stand_id INT NOT NULL,
            full_name VARCHAR(150) NOT NULL,
            phone VARCHAR(50) NOT NULL,
            city VARCHAR(100) NOT NULL,
            profile VARCHAR(60) NOT NULL,
            hectares VARCHAR(60) NULL,
            interest VARCHAR(120) NULL,
            ip_address VARCHAR(45) NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (stand_id) REFERENCES stands(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ');
    echo "✔ Tabla 'leads' creada.\n";

    // 4. Tabla: ruleta_spins (Giros y cupones de canje de premios)
    $pdo->exec('
        CREATE TABLE IF NOT EXISTS ruleta_spins (
            id INT AUTO_INCREMENT PRIMARY KEY,
            stand_id INT NOT NULL,
            lead_id INT NOT NULL,
            prize_id INT NOT NULL,
            coupon_code VARCHAR(30) NOT NULL UNIQUE,
            is_redeemed TINYINT(1) DEFAULT 0,
            redeemed_at TIMESTAMP NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (stand_id) REFERENCES stands(id) ON DELETE CASCADE,
            FOREIGN KEY (lead_id) REFERENCES leads(id) ON DELETE CASCADE,
            FOREIGN KEY (prize_id) REFERENCES prizes(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ');
    echo "✔ Tabla 'ruleta_spins' creada.\n";

    // 5. Tabla: draws (Sorteos oficiales proyectados en TV)
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS draws (
            id INT AUTO_INCREMENT PRIMARY KEY,
            stand_id INT NOT NULL,
            title VARCHAR(150) NOT NULL,
            prize_description VARCHAR(200) NOT NULL,
            winner_lead_id INT NULL,
            draw_date TIMESTAMP NULL,
            status VARCHAR(30) DEFAULT 'pending',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (stand_id) REFERENCES stands(id) ON DELETE CASCADE,
            FOREIGN KEY (winner_lead_id) REFERENCES leads(id) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");
    echo "✔ Tabla 'draws' creada.\n";

    // Insertar datos de prueba para demostración (Kurosu & Cia. / John Deere)
    $checkStand = $pdo->prepare("SELECT id FROM stands WHERE slug = 'kurosu'");
    $checkStand->execute();
    $standId = $checkStand->fetchColumn();

    if (! $standId) {
        $stmt = $pdo->prepare("
            INSERT INTO stands (slug, name, tagline, stand_number, sector, primary_color, secondary_color, access_pin)
            VALUES ('kurosu', 'Kurosu & Cia. | John Deere', 'Líderes en tecnología agrícola y soluciones de campo', 'Stand 45', 'Sector Maquinarias', '#16a34a', '#1e3a8a', '1234')
        ");
        $stmt->execute();
        $standId = $pdo->lastInsertId();
        echo "✔ Stand de demostración 'Kurosu & Cia.' creado (ID: $standId).\n";

        // Premios para Kurosu
        $prizes = [
            ['Gorra Oficial John Deere', '#15803d', '#ffffff', 30, 24, 20, 1, 'cap'],
            ['Termo Térmico Grabado', '#1e40af', '#ffffff', 10, 6, 8, 1, 'cup'],
            ['Llavero Metálico', '#d97706', '#ffffff', 50, 45, 30, 1, 'key'],
            ['Vale 15% Descuento Repuestos', '#7c3aed', '#ffffff', 20, 18, 15, 1, 'ticket'],
            ['Kit Destornillador Pro', '#047857', '#ffffff', 15, 11, 10, 1, 'tool'],
            ['¡Seguí Participando!', '#64748b', '#ffffff', 9999, 9999, 40, 0, 'smile'],
        ];

        $insPrize = $pdo->prepare('
            INSERT INTO prizes (stand_id, name, color, text_color, total_stock, remaining_stock, probability_weight, is_real_prize, icon_badge)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ');
        foreach ($prizes as $p) {
            $insPrize->execute([$standId, $p[0], $p[1], $p[2], $p[3], $p[4], $p[5], $p[6], $p[7]]);
        }
        echo "✔ Premios de la ruleta configurados para Kurosu.\n";

        // Leads de demostración iniciales
        $sampleLeads = [
            ['Hugo Benítez', '0985123456', 'Hohenau', 'Productor / Agricultor', '200 - 500 ha', 'Cosechadoras Serie S'],
            ['Martina Rojas', '0971987654', 'Encarnación', 'Empresa / Distribuidor', '> 1000 ha', 'Repuestos Originales'],
            ['Esteban Duarte', '0981555666', 'Obligado', 'Productor / Agricultor', '50 - 200 ha', 'Tractores Serie 5E'],
            ['Laura Bogado', '0992333444', 'Bella Vista', 'Estudiante / Académico', '< 50 ha', 'Agricultura de Precisión'],
            ['Carlos Giménez', '0983111222', 'Pirapó', 'Productor / Agricultor', '500 - 1000 ha', 'Sembradoras'],
        ];

        $insLead = $pdo->prepare('
            INSERT INTO leads (stand_id, full_name, phone, city, profile, hectares, interest)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ');
        foreach ($sampleLeads as $l) {
            $insLead->execute([$standId, $l[0], $l[1], $l[2], $l[3], $l[4], $l[5]]);
        }
        echo "✔ 5 Leads iniciales insertados para demostración.\n";

        // Sorteo demo
        $stmtDraw = $pdo->prepare("
            INSERT INTO draws (stand_id, title, prize_description, status)
            VALUES (?, 'Gran Sorteo Oficial Kurosu 2026', 'Kit de Herramientas Premium John Deere + Mochila Técnica', 'pending')
        ");
        $stmtDraw->execute([$standId]);
        echo "✔ Sorteo oficial configurado para Kurosu.\n";
    }

    echo "\n=== MIGRACIÓN COMPLETADA EXITOSAMENTE ===\n";

} catch (Exception $e) {
    echo 'ERROR en la migración: '.$e->getMessage()."\n";
    exit(1);
}
