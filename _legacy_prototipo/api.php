<?php

// api.php - Backend API para SotePass Expo
require_once __DIR__.'/config.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'OPTIONS') {
    exit(0);
}

$action = $_GET['action'] ?? '';
$db = getDB();

// Helper para leer body JSON o POST
function getRequestData()
{
    $raw = file_get_contents('php://input');
    $json = json_decode($raw, true);
    if (is_array($json)) {
        return $json;
    }

    return $_POST;
}

switch ($action) {

    // 1. Obtener datos públicos del stand y sus premios
    case 'get_stand':
        $slug = trim($_GET['slug'] ?? '');
        if (! $slug) {
            jsonResponse(['error' => 'Falta el parámetro slug'], 400);
        }

        $stmt = $db->prepare('SELECT id, slug, name, tagline, stand_number, sector, primary_color, secondary_color, logo_url, allow_spins FROM stands WHERE slug = ? AND is_active = 1');
        $stmt->execute([$slug]);
        $stand = $stmt->fetch();

        if (! $stand) {
            jsonResponse(['error' => 'Stand no encontrado o inactivo'], 404);
        }

        // Obtener premios activos
        $prizesStmt = $db->prepare('SELECT id, name, color, text_color, is_real_prize, icon_badge, remaining_stock, probability_weight FROM prizes WHERE stand_id = ? ORDER BY id ASC');
        $prizesStmt->execute([$stand['id']]);
        $prizes = $prizesStmt->fetchAll();

        jsonResponse([
            'stand' => $stand,
            'prizes' => $prizes,
        ]);
        break;

        // 2. Registro de visitante (Lead)
    case 'register_lead':
        $data = getRequestData();
        $slug = trim($data['slug'] ?? '');
        $fullName = trim($data['full_name'] ?? '');
        $phone = trim($data['phone'] ?? '');
        $city = trim($data['city'] ?? '');
        $profile = trim($data['profile'] ?? 'Público General');
        $hectares = trim($data['hectares'] ?? '');
        $interest = trim($data['interest'] ?? '');

        if (! $slug || ! $fullName || ! $phone || ! $city) {
            jsonResponse(['error' => 'Por favor complete todos los campos obligatorios.'], 400);
        }

        $standStmt = $db->prepare('SELECT id FROM stands WHERE slug = ? AND is_active = 1');
        $standStmt->execute([$slug]);
        $stand = $standStmt->fetch();

        if (! $stand) {
            jsonResponse(['error' => 'Stand no válido.'], 404);
        }

        $ip = $_SERVER['REMOTE_ADDR'] ?? '';

        $insert = $db->prepare('
            INSERT INTO leads (stand_id, full_name, phone, city, profile, hectares, interest, ip_address)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ');
        $insert->execute([$stand['id'], $fullName, $phone, $city, $profile, $hectares, $interest, $ip]);
        $leadId = $db->lastInsertId();

        jsonResponse([
            'success' => true,
            'lead_id' => $leadId,
            'full_name' => $fullName,
            'message' => '¡Registro exitoso!',
        ]);
        break;

        // 3. Girar la Ruleta y calcular premio de forma segura en el servidor
    case 'spin_ruleta':
        $data = getRequestData();
        $slug = trim($data['slug'] ?? '');
        $leadId = intval($data['lead_id'] ?? 0);

        if (! $slug || ! $leadId) {
            jsonResponse(['error' => 'Datos insuficientes para girar la ruleta.'], 400);
        }

        $standStmt = $db->prepare('SELECT id, max_spins_per_lead, allow_spins FROM stands WHERE slug = ? AND is_active = 1');
        $standStmt->execute([$slug]);
        $stand = $standStmt->fetch();

        if (! $stand || ! $stand['allow_spins']) {
            jsonResponse(['error' => 'La ruleta no está disponible para este stand.'], 400);
        }

        // Verificar si el lead ya giró
        $spinsCount = $db->prepare('SELECT COUNT(*) FROM ruleta_spins WHERE stand_id = ? AND lead_id = ?');
        $spinsCount->execute([$stand['id'], $leadId]);
        if ($spinsCount->fetchColumn() >= $stand['max_spins_per_lead']) {
            jsonResponse(['error' => 'Ya has utilizado tu oportunidad en la ruleta.'], 400);
        }

        // Obtener todos los premios en orden para calcular índice de segmento
        $pStmt = $db->prepare('SELECT id, name, color, text_color, is_real_prize, icon_badge, total_stock, remaining_stock, probability_weight FROM prizes WHERE stand_id = ? ORDER BY id ASC');
        $pStmt->execute([$stand['id']]);
        $allPrizes = $pStmt->fetchAll();

        if (empty($allPrizes)) {
            jsonResponse(['error' => 'No hay premios configurados en este stand.'], 400);
        }

        // Filtrar elegibles según stock
        $pool = [];
        foreach ($allPrizes as $index => $p) {
            // Si es premio real y tiene stock > 0, o si es "sigue participando"
            $canWin = ($p['is_real_prize'] == 0) || ($p['remaining_stock'] > 0);
            if ($canWin && $p['probability_weight'] > 0) {
                for ($w = 0; $w < $p['probability_weight']; $w++) {
                    $pool[] = $index;
                }
            }
        }

        if (empty($pool)) {
            // Si por algún motivo se agotó todo, forzar el premio que no sea real o el último
            $selectedSliceIndex = count($allPrizes) - 1;
        } else {
            $randomKey = array_rand($pool);
            $selectedSliceIndex = $pool[$randomKey];
        }

        $chosenPrize = $allPrizes[$selectedSliceIndex];

        // Si es premio real, descontar stock de forma segura
        if ($chosenPrize['is_real_prize'] == 1 && $chosenPrize['remaining_stock'] > 0) {
            $dec = $db->prepare('UPDATE prizes SET remaining_stock = remaining_stock - 1 WHERE id = ? AND remaining_stock > 0');
            $dec->execute([$chosenPrize['id']]);
        }

        // Generar cupón SotePass único (Ej. SP-KR-8492)
        $couponCode = 'SP-'.strtoupper(substr($slug, 0, 2)).'-'.rand(1000, 9999);

        // Guardar el giro
        $saveSpin = $db->prepare('
            INSERT INTO ruleta_spins (stand_id, lead_id, prize_id, coupon_code)
            VALUES (?, ?, ?, ?)
        ');
        $saveSpin->execute([$stand['id'], $leadId, $chosenPrize['id'], $couponCode]);

        jsonResponse([
            'success' => true,
            'slice_index' => $selectedSliceIndex,
            'total_slices' => count($allPrizes),
            'prize_id' => $chosenPrize['id'],
            'prize_name' => $chosenPrize['name'],
            'is_real_prize' => (bool) $chosenPrize['is_real_prize'],
            'coupon_code' => $couponCode,
            'message' => $chosenPrize['is_real_prize'] ? '¡Felicidades! Has ganado un premio.' : '¡Gracias por visitarnos!',
        ]);
        break;

        // 4. Canjear cupón de premio en mostrador (Operador del stand)
    case 'redeem_coupon':
        $data = getRequestData();
        $code = strtoupper(trim($data['coupon_code'] ?? ''));
        $slug = trim($data['slug'] ?? '');
        $pin = trim($data['pin'] ?? '');

        if (! $code || ! $slug) {
            jsonResponse(['error' => 'Código de cupón requerido.'], 400);
        }

        // Verificar PIN
        $standStmt = $db->prepare('SELECT id FROM stands WHERE slug = ? AND access_pin = ?');
        $standStmt->execute([$slug, $pin]);
        $stand = $standStmt->fetch();
        if (! $stand) {
            jsonResponse(['error' => 'PIN de expositor incorrecto o no autorizado.'], 401);
        }

        // Buscar cupón
        $cStmt = $db->prepare('
            SELECT s.id, s.is_redeemed, s.redeemed_at, p.name as prize_name, l.full_name, l.city, l.phone
            FROM ruleta_spins s
            JOIN prizes p ON s.prize_id = p.id
            JOIN leads l ON s.lead_id = l.id
            WHERE s.coupon_code = ? AND s.stand_id = ?
        ');
        $cStmt->execute([$code, $stand['id']]);
        $coupon = $cStmt->fetch();

        if (! $coupon) {
            jsonResponse(['error' => 'Código de cupón no encontrado o inválido para este stand.'], 404);
        }

        if ($coupon['is_redeemed']) {
            jsonResponse([
                'success' => false,
                'already_redeemed' => true,
                'redeemed_at' => $coupon['redeemed_at'],
                'message' => 'Este cupón YA FUE CANJEADO el '.date('d/m H:i', strtotime($coupon['redeemed_at'])),
                'lead' => $coupon,
            ]);
        }

        // Marcar como canjeado
        $up = $db->prepare('UPDATE ruleta_spins SET is_redeemed = 1, redeemed_at = NOW() WHERE id = ?');
        $up->execute([$coupon['id']]);

        jsonResponse([
            'success' => true,
            'message' => '¡Premio canjeado con éxito!',
            'lead' => $coupon,
        ]);
        break;

        // 5. Dashboard del Expositor
    case 'get_dashboard':
        $slug = trim($_GET['slug'] ?? '');
        $pin = trim($_GET['pin'] ?? '');

        $standStmt = $db->prepare('SELECT * FROM stands WHERE slug = ?');
        $standStmt->execute([$slug]);
        $stand = $standStmt->fetch();

        if (! $stand) {
            jsonResponse(['error' => 'Stand no encontrado'], 404);
        }

        if ($pin !== $stand['access_pin']) {
            jsonResponse(['error' => 'PIN incorrecto'], 401);
        }

        // Métricas
        $totalLeads = $db->query("SELECT COUNT(*) FROM leads WHERE stand_id = {$stand['id']}")->fetchColumn();
        $spinsCount = $db->query("SELECT COUNT(*) FROM ruleta_spins WHERE stand_id = {$stand['id']}")->fetchColumn();
        $redeemedCount = $db->query("SELECT COUNT(*) FROM ruleta_spins WHERE stand_id = {$stand['id']} AND is_redeemed = 1")->fetchColumn();

        // Perfil predominante
        $profStmt = $db->query("
            SELECT profile, COUNT(*) as qty 
            FROM leads 
            WHERE stand_id = {$stand['id']} 
            GROUP BY profile 
            ORDER BY qty DESC 
            LIMIT 1
        ");
        $topProfile = $profStmt->fetch()['profile'] ?? 'N/A';

        // Lista de últimos 50 leads con sus premios si jugaron
        $leadsStmt = $db->prepare('
            SELECT l.id, l.full_name, l.phone, l.city, l.profile, l.hectares, l.interest, l.created_at,
                   s.coupon_code, s.is_redeemed, p.name as prize_name, p.is_real_prize
            FROM leads l
            LEFT JOIN ruleta_spins s ON l.id = s.lead_id
            LEFT JOIN prizes p ON s.prize_id = p.id
            WHERE l.stand_id = ?
            ORDER BY l.id DESC
            LIMIT 50
        ');
        $leadsStmt->execute([$stand['id']]);
        $leads = $leadsStmt->fetchAll();

        // Premios y stock actual
        $prizesStmt = $db->prepare('
            SELECT p.*, 
                   (SELECT COUNT(*) FROM ruleta_spins rs WHERE rs.prize_id = p.id AND rs.is_redeemed = 1) as total_redeemed
            FROM prizes p
            WHERE p.stand_id = ?
            ORDER BY p.id ASC
        ');
        $prizesStmt->execute([$stand['id']]);
        $prizes = $prizesStmt->fetchAll();

        jsonResponse([
            'stand' => [
                'id' => $stand['id'],
                'slug' => $stand['slug'],
                'name' => $stand['name'],
                'tagline' => $stand['tagline'],
                'stand_number' => $stand['stand_number'],
                'sector' => $stand['sector'],
                'primary_color' => $stand['primary_color'],
            ],
            'kpis' => [
                'total_leads' => intval($totalLeads),
                'total_spins' => intval($spinsCount),
                'total_redeemed' => intval($redeemedCount),
                'top_profile' => $topProfile,
            ],
            'leads' => $leads,
            'prizes' => $prizes,
        ]);
        break;

        // 6. Exportar base de leads en CSV compatible con Excel
    case 'export_leads':
        $slug = trim($_GET['slug'] ?? '');
        $pin = trim($_GET['pin'] ?? '');

        $standStmt = $db->prepare('SELECT id, name, access_pin FROM stands WHERE slug = ?');
        $standStmt->execute([$slug]);
        $stand = $standStmt->fetch();

        if (! $stand || $pin !== $stand['access_pin']) {
            exit('Acceso no autorizado.');
        }

        $leadsStmt = $db->prepare("
            SELECT l.id, l.created_at, l.full_name, l.phone, l.city, l.profile, l.hectares, l.interest,
                   p.name as premio, s.coupon_code, CASE WHEN s.is_redeemed = 1 THEN 'CANJEADO' ELSE 'PENDIENTE' END as estado_canje
            FROM leads l
            LEFT JOIN ruleta_spins s ON l.id = s.lead_id
            LEFT JOIN prizes p ON s.prize_id = p.id
            WHERE l.stand_id = ?
            ORDER BY l.id ASC
        ");
        $leadsStmt->execute([$stand['id']]);
        $rows = $leadsStmt->fetchAll();

        $filename = 'Leads_SotePass_'.preg_replace('/[^a-zA-Z0-9_-]/', '_', $stand['name']).'_'.date('Y-m-d_Hi').'.csv';

        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="'.$filename.'"');

        // UTF-8 BOM para que Microsoft Excel abra los acentos perfectamente
        echo "\xEF\xBB\xBF";

        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'Fecha / Hora', 'Nombre Completo', 'WhatsApp / Teléfono', 'Ciudad', 'Perfil', 'Hectáreas', 'Interés Comercial', 'Premio Ruleta', 'Código Cupón', 'Estado Canje'], ';');

        foreach ($rows as $r) {
            fputcsv($output, [
                $r['id'],
                $r['created_at'],
                $r['full_name'],
                $r['phone'],
                $r['city'],
                $r['profile'],
                $r['hectares'] ?: 'N/D',
                $r['interest'] ?: 'General',
                $r['premio'] ?: 'No giró',
                $r['coupon_code'] ?: 'N/A',
                $r['estado_canje'],
            ], ';');
        }
        fclose($output);
        exit;
        break;

        // 7. Sorteo en TV: Obtener padrón de leads para el sorteo del stand
    case 'get_draw_data':
        $slug = trim($_GET['slug'] ?? '');
        $standStmt = $db->prepare('SELECT id, name, stand_number, primary_color FROM stands WHERE slug = ?');
        $standStmt->execute([$slug]);
        $stand = $standStmt->fetch();

        if (! $stand) {
            jsonResponse(['error' => 'Stand no encontrado'], 404);
        }

        // Obtener sorteo activo o crear uno
        $drawStmt = $db->prepare('SELECT * FROM draws WHERE stand_id = ? ORDER BY id DESC LIMIT 1');
        $drawStmt->execute([$stand['id']]);
        $draw = $drawStmt->fetch();

        // Obtener nombres de leads para la animación
        $leadsStmt = $db->prepare('SELECT id, full_name, city, profile, phone FROM leads WHERE stand_id = ? ORDER BY id DESC');
        $leadsStmt->execute([$stand['id']]);
        $leads = $leadsStmt->fetchAll();

        jsonResponse([
            'stand' => $stand,
            'draw' => $draw,
            'total_participants' => count($leads),
            'participants' => $leads,
        ]);
        break;

        // 8. Ejecutar Sorteo y certificar ganador
    case 'execute_draw':
        $data = getRequestData();
        $slug = trim($data['slug'] ?? '');
        $pin = trim($data['pin'] ?? '');

        $standStmt = $db->prepare('SELECT id FROM stands WHERE slug = ? AND access_pin = ?');
        $standStmt->execute([$slug, $pin]);
        $stand = $standStmt->fetch();

        if (! $stand) {
            jsonResponse(['error' => 'No autorizado'], 401);
        }

        $leadsStmt = $db->prepare('SELECT id, full_name, city, profile, phone FROM leads WHERE stand_id = ?');
        $leadsStmt->execute([$stand['id']]);
        $leads = $leadsStmt->fetchAll();

        if (empty($leads)) {
            jsonResponse(['error' => 'No hay participantes registrados para sortear.'], 400);
        }

        $winner = $leads[array_rand($leads)];

        // Actualizar tabla draws
        $upDraw = $db->prepare("
            UPDATE draws 
            SET winner_lead_id = ?, draw_date = NOW(), status = 'completed'
            WHERE stand_id = ? AND status = 'pending'
            ORDER BY id DESC LIMIT 1
        ");
        $upDraw->execute([$winner['id'], $stand['id']]);

        jsonResponse([
            'success' => true,
            'winner' => $winner,
        ]);
        break;

        // 9. Actualizar stock de premio desde el dashboard
    case 'update_prize_stock':
        $data = getRequestData();
        $slug = trim($data['slug'] ?? '');
        $pin = trim($data['pin'] ?? '');
        $prizeId = intval($data['prize_id'] ?? 0);
        $remaining = intval($data['remaining_stock'] ?? 0);

        $standStmt = $db->prepare('SELECT id FROM stands WHERE slug = ? AND access_pin = ?');
        $standStmt->execute([$slug, $pin]);
        $stand = $standStmt->fetch();

        if (! $stand) {
            jsonResponse(['error' => 'No autorizado'], 401);
        }

        $up = $db->prepare('UPDATE prizes SET remaining_stock = ? WHERE id = ? AND stand_id = ?');
        $up->execute([$remaining, $prizeId, $stand['id']]);

        jsonResponse(['success' => true]);
        break;

        // 10. Guardar / Editar Premio Completo (Nombre, Stock, Color, Probabilidad)
    case 'save_prize':
        $data = getRequestData();
        $slug = trim($data['slug'] ?? '');
        $pin = trim($data['pin'] ?? '');
        $prizeId = intval($data['prize_id'] ?? 0);
        $name = trim($data['name'] ?? '');
        $color = trim($data['color'] ?? '#16a34a');
        $totalStock = intval($data['total_stock'] ?? 10);
        $remainingStock = isset($data['remaining_stock']) ? intval($data['remaining_stock']) : $totalStock;
        $probWeight = intval($data['probability_weight'] ?? 10);
        $isRealPrize = intval($data['is_real_prize'] ?? 1);

        if (! $name) {
            jsonResponse(['error' => 'El nombre del premio es obligatorio.'], 400);
        }

        $standStmt = $db->prepare('SELECT id FROM stands WHERE slug = ? AND access_pin = ?');
        $standStmt->execute([$slug, $pin]);
        $stand = $standStmt->fetch();

        if (! $stand) {
            jsonResponse(['error' => 'No autorizado o PIN inválido.'], 401);
        }

        if ($prizeId > 0) {
            // Actualizar existente
            $up = $db->prepare('
                UPDATE prizes 
                SET name = ?, color = ?, total_stock = ?, remaining_stock = ?, probability_weight = ?, is_real_prize = ?
                WHERE id = ? AND stand_id = ?
            ');
            $up->execute([$name, $color, $totalStock, $remainingStock, $probWeight, $isRealPrize, $prizeId, $stand['id']]);
            $msg = 'Premio actualizado con éxito.';
        } else {
            // Crear nuevo
            $ins = $db->prepare('
                INSERT INTO prizes (stand_id, name, color, total_stock, remaining_stock, probability_weight, is_real_prize)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ');
            $ins->execute([$stand['id'], $name, $color, $totalStock, $totalStock, $probWeight, $isRealPrize]);
            $msg = 'Premio agregado a la ruleta.';
        }

        jsonResponse(['success' => true, 'message' => $msg]);
        break;

        // 11. Eliminar Premio
    case 'delete_prize':
        $data = getRequestData();
        $slug = trim($data['slug'] ?? '');
        $pin = trim($data['pin'] ?? '');
        $prizeId = intval($data['prize_id'] ?? 0);

        $standStmt = $db->prepare('SELECT id FROM stands WHERE slug = ? AND access_pin = ?');
        $standStmt->execute([$slug, $pin]);
        $stand = $standStmt->fetch();

        if (! $stand) {
            jsonResponse(['error' => 'No autorizado.'], 401);
        }

        $del = $db->prepare('DELETE FROM prizes WHERE id = ? AND stand_id = ?');
        $del->execute([$prizeId, $stand['id']]);

        jsonResponse(['success' => true, 'message' => 'Premio eliminado.']);
        break;

    default:
        jsonResponse(['error' => 'Acción no válida o no especificada'], 400);
}
