<?php
// admin.php - Panel SuperAdmin de Soteweb para SotePass Expo
require_once __DIR__.'/config.php';

$db = getDB();
$message = '';
$error = '';

// Procesar alta de nuevo Stand
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create_stand') {
    $slug = strtolower(trim(preg_replace('/[^a-zA-Z0-9_-]/', '', $_POST['slug'] ?? '')));
    $name = trim($_POST['name'] ?? '');
    $tagline = trim($_POST['tagline'] ?? '');
    $standNumber = trim($_POST['stand_number'] ?? '');
    $sector = trim($_POST['sector'] ?? 'General');
    $color = trim($_POST['primary_color'] ?? '#16a34a');
    $pin = trim($_POST['access_pin'] ?? '1234');

    if (! $slug || ! $name || ! $standNumber) {
        $error = 'Por favor complete el nombre, slug y número de stand.';
    } else {
        try {
            $stmt = $db->prepare('
                INSERT INTO stands (slug, name, tagline, stand_number, sector, primary_color, access_pin)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ');
            $stmt->execute([$slug, $name, $tagline, $standNumber, $sector, $color, $pin]);
            $newStandId = $db->lastInsertId();

            // Insertar premios por defecto
            $defaultPrizes = [
                ['Gorra Oficial', '#16a34a', 30, 30, 20, 1],
                ['Termo Metálico', '#2563eb', 10, 10, 8, 1],
                ['Llavero de Marca', '#d97706', 50, 50, 30, 1],
                ['Vale 10% Descuento', '#7c3aed', 20, 20, 15, 1],
                ['¡Seguí Participando!', '#64748b', 9999, 9999, 40, 0],
            ];
            $pStmt = $db->prepare('
                INSERT INTO prizes (stand_id, name, color, total_stock, remaining_stock, probability_weight, is_real_prize)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ');
            foreach ($defaultPrizes as $dp) {
                $pStmt->execute([$newStandId, $dp[0], $dp[1], $dp[2], $dp[3], $dp[4], $dp[5]]);
            }

            // Crear sorteo pendiente
            $dStmt = $db->prepare("INSERT INTO draws (stand_id, title, prize_description, status) VALUES (?, 'Gran Sorteo Oficial', 'Premio Principal del Stand', 'pending')");
            $dStmt->execute([$newStandId]);

            $message = "¡Stand '$name' creado con éxito! Ya tiene su Ruleta y Panel listos.";
        } catch (Exception $e) {
            $error = 'Error al crear stand: '.$e->getMessage();
        }
    }
}

// Consultar stands con métricas
$stands = $db->query('
    SELECT s.*, 
           (SELECT COUNT(*) FROM leads l WHERE l.stand_id = s.id) as total_leads,
           (SELECT COUNT(*) FROM ruleta_spins rs WHERE rs.stand_id = s.id) as total_spins
    FROM stands s 
    ORDER BY s.id DESC
')->fetchAll();

$globalLeads = $db->query('SELECT COUNT(*) FROM leads')->fetchColumn();
$globalSpins = $db->query('SELECT COUNT(*) FROM ruleta_spins')->fetchColumn();
$globalStands = count($stands);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SuperAdmin | SotePass Expo (Soteweb)</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=Share+Tech+Mono&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex flex-col antialiased selection:bg-emerald-500 selection:text-white">

    <header class="bg-slate-900 border-b border-slate-800 h-16 flex items-center px-6 justify-between">
        <div class="flex items-center gap-3">
            <span class="text-emerald-400 font-black text-xl">SotePass Expo</span>
            <span class="text-xs px-2 py-0.5 rounded bg-emerald-950 text-emerald-300 border border-emerald-800 font-bold uppercase">Master Admin</span>
        </div>
        <div class="flex items-center gap-4 text-xs">
            <span class="text-slate-400">Base de Datos: <strong class="text-emerald-400">agrosorteo</strong></span>
            <a href="index.php" class="px-3 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-200 transition">Ver Portal Principal</a>
        </div>
    </header>

    <main class="max-w-7xl mx-auto w-full px-4 sm:px-6 py-8 space-y-8">

        <?php if ($message) { ?>
            <div class="p-4 rounded-xl bg-emerald-950/80 border border-emerald-500 text-emerald-300 text-sm font-semibold">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php } ?>
        <?php if ($error) { ?>
            <div class="p-4 rounded-xl bg-rose-950/80 border border-rose-500 text-rose-300 text-sm font-semibold">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php } ?>

        <!-- KPIs Globales -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-slate-900 border border-slate-800 p-5 rounded-2xl">
                <p class="text-xs text-slate-400 uppercase font-semibold">Stands / Clientes Activos</p>
                <h3 class="text-3xl font-black text-white mt-1"><?= $globalStands ?></h3>
            </div>
            <div class="bg-slate-900 border border-slate-800 p-5 rounded-2xl">
                <p class="text-xs text-slate-400 uppercase font-semibold">Total Leads Captados en la Expo</p>
                <h3 class="text-3xl font-black text-emerald-400 mt-1"><?= number_format($globalLeads) ?></h3>
            </div>
            <div class="bg-slate-900 border border-slate-800 p-5 rounded-2xl">
                <p class="text-xs text-slate-400 uppercase font-semibold">Giros en Ruletas</p>
                <h3 class="text-3xl font-black text-amber-400 mt-1"><?= number_format($globalSpins) ?></h3>
            </div>
        </div>

        <!-- Formulario: Dar de Alta Nuevo Stand -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6">
            <h2 class="text-lg font-bold text-white mb-1">Dar de Alta Nuevo Stand / Expositor</h2>
            <p class="text-xs text-slate-400 mb-6">Genera automáticamente la landing de registro, la ruleta con premios base y el panel del cliente.</p>

            <form method="POST" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 text-xs">
                <input type="hidden" name="action" value="create_stand">

                <div>
                    <label class="block font-semibold text-slate-300 mb-1">Nombre de la Empresa *</label>
                    <input type="text" name="name" required placeholder="Ej. Somax Agro S.A."
                           class="w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-xl text-white">
                </div>

                <div>
                    <label class="block font-semibold text-slate-300 mb-1">Slug URL (Único, sin espacios) *</label>
                    <input type="text" name="slug" required placeholder="Ej. somax"
                           class="w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-xl text-white font-mono">
                </div>

                <div>
                    <label class="block font-semibold text-slate-300 mb-1">Número de Stand *</label>
                    <input type="text" name="stand_number" required placeholder="Ej. Stand 12 - Pabellón A"
                           class="w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-xl text-white">
                </div>

                <div>
                    <label class="block font-semibold text-slate-300 mb-1">Sector / Rubro</label>
                    <input type="text" name="sector" placeholder="Ej. Semillas e Insumos"
                           class="w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-xl text-white">
                </div>

                <div>
                    <label class="block font-semibold text-slate-300 mb-1">Color Principal (Hex)</label>
                    <div class="flex gap-2">
                        <input type="color" name="primary_color" value="#2563eb"
                               class="h-9 w-12 rounded-lg bg-slate-950 border border-slate-700 cursor-pointer">
                        <input type="text" value="#2563eb" readonly class="flex-1 px-3 py-2 bg-slate-950 border border-slate-700 rounded-xl text-white font-mono">
                    </div>
                </div>

                <div>
                    <label class="block font-semibold text-slate-300 mb-1">PIN de Acceso del Expositor</label>
                    <input type="text" name="access_pin" value="1234" required
                           class="w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-xl text-white font-mono">
                </div>

                <div class="sm:col-span-2 lg:col-span-3 pt-2">
                    <button type="submit" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs rounded-xl transition">
                        + Crear Stand y Configurar Ruleta
                    </button>
                </div>
            </form>
        </div>

        <!-- Lista de Stands Activos -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden">
            <div class="p-5 border-b border-slate-800">
                <h2 class="text-base font-bold text-white">Stands Registrados en la Plataforma</h2>
            </div>
            <div class="overflow-x-auto text-xs">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-950/70 border-b border-slate-800 text-slate-400 font-semibold uppercase">
                            <th class="py-3 px-4">Empresa</th>
                            <th class="py-3 px-4">Stand / Sector</th>
                            <th class="py-3 px-4">PIN</th>
                            <th class="py-3 px-4">Leads</th>
                            <th class="py-3 px-4">Giros</th>
                            <th class="py-3 px-4 text-right">Accesos Directos</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60 text-slate-300">
                        <?php foreach ($stands as $s) { ?>
                            <tr class="hover:bg-slate-800/40">
                                <td class="py-3 px-4 font-bold text-white flex items-center gap-2">
                                    <span class="w-3 h-3 rounded-full" style="background-color: <?= htmlspecialchars($s['primary_color']) ?>;"></span>
                                    <?= htmlspecialchars($s['name']) ?>
                                    <span class="text-[10px] text-slate-500 font-mono">(/<?= htmlspecialchars($s['slug']) ?>)</span>
                                </td>
                                <td class="py-3 px-4"><?= htmlspecialchars($s['stand_number']) ?> • <?= htmlspecialchars($s['sector']) ?></td>
                                <td class="py-3 px-4 font-mono text-amber-400 font-bold"><?= htmlspecialchars($s['access_pin']) ?></td>
                                <td class="py-3 px-4 font-bold text-emerald-400"><?= $s['total_leads'] ?></td>
                                <td class="py-3 px-4 font-bold text-amber-400"><?= $s['total_spins'] ?></td>
                                <td class="py-3 px-4 text-right whitespace-nowrap space-x-2">
                                    <a href="registro.php?stand=<?= urlencode($s['slug']) ?>" target="_blank" class="px-2 py-1 rounded bg-slate-800 hover:bg-slate-700 text-slate-200">Landing QR</a>
                                    <a href="panel.php?stand=<?= urlencode($s['slug']) ?>" target="_blank" class="px-2 py-1 rounded bg-indigo-600/20 text-indigo-300 hover:bg-indigo-600/30">Panel Stand</a>
                                    <a href="sorteo.php?stand=<?= urlencode($s['slug']) ?>" target="_blank" class="px-2 py-1 rounded bg-amber-600/20 text-amber-300 hover:bg-amber-600/30">Sorteo TV</a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

    </main>

    <footer class="mt-auto py-4 bg-slate-900 border-t border-slate-800 text-center text-xs text-slate-500">
        SotePass Expo • Soteweb División Soluciones Corporativas
    </footer>

</body>
</html>

