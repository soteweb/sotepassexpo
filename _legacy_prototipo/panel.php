<?php
// panel.php - Panel de Control del Expositor / Stand (SotePass Expo)
require_once __DIR__.'/config.php';

$slug = trim($_GET['stand'] ?? 'kurosu');
$db = getDB();

$stmt = $db->prepare('SELECT * FROM stands WHERE slug = ?');
$stmt->execute([$slug]);
$stand = $stmt->fetch();

if (! $stand) {
    exit("Stand no encontrado. <a href='index.php'>Volver al inicio</a>");
}

$primaryColor = $stand['primary_color'] ?: '#16a34a';
$secondaryColor = $stand['secondary_color'] ?: '#0f172a';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Expositor | <?= htmlspecialchars($stand['name']) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Share+Tech+Mono&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-mono-code { font-family: 'Share Tech Mono', monospace; }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex flex-col antialiased selection:bg-emerald-500 selection:text-white">

    <!-- Top Navbar -->
    <header class="bg-slate-900 border-b border-slate-800 sticky top-0 z-40 backdrop-blur-md bg-slate-900/90">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center font-black text-white text-lg shadow-md" style="background-color: <?= $primaryColor ?>;">
                    <?= strtoupper(substr($stand['slug'], 0, 1)) ?>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="font-bold text-white text-base leading-none"><?= htmlspecialchars($stand['name']) ?></h1>
                        <span class="text-[11px] px-2 py-0.5 rounded-full bg-slate-800 text-slate-300 border border-slate-700">
                            <?= htmlspecialchars($stand['stand_number']) ?>
                        </span>
                    </div>
                    <p class="text-xs text-slate-400 mt-1"><?= htmlspecialchars($stand['sector']) ?></p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="sorteo.php?stand=<?= urlencode($stand['slug']) ?>" target="_blank"
                   class="hidden sm:inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-amber-500/10 border border-amber-500/30 text-amber-400 text-xs font-bold hover:bg-amber-500/20 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    <span>Abrir Sorteo en TV</span>
                </a>
                <a href="registro.php?stand=<?= urlencode($stand['slug']) ?>" target="_blank"
                   class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-bold hover:bg-emerald-500/20 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                    <span>Ver QR / Registro</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-6 space-y-6">

        <!-- Top Section: PIN & KPI Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            
            <div class="bg-slate-900 border border-slate-800 p-5 rounded-2xl flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Leads Captados</p>
                    <h3 class="text-3xl font-black text-white mt-1" id="kpiTotalLeads">--</h3>
                    <p class="text-[11px] text-emerald-400 mt-1 flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> En tiempo real
                    </p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center text-xl">
                    👥
                </div>
            </div>

            <div class="bg-slate-900 border border-slate-800 p-5 rounded-2xl flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Giros en Ruleta</p>
                    <h3 class="text-3xl font-black text-amber-400 mt-1" id="kpiTotalSpins">--</h3>
                    <p class="text-[11px] text-slate-400 mt-1">Visitantes que jugaron</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-400 flex items-center justify-center text-xl">
                    🎯
                </div>
            </div>

            <div class="bg-slate-900 border border-slate-800 p-5 rounded-2xl flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Premios Canjeados</p>
                    <h3 class="text-3xl font-black text-indigo-400 mt-1" id="kpiTotalRedeemed">--</h3>
                    <p class="text-[11px] text-slate-400 mt-1">Entregados en mostrador</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center text-xl">
                    🎁
                </div>
            </div>

            <div class="bg-slate-900 border border-slate-800 p-5 rounded-2xl flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Perfil Dominante</p>
                    <h3 class="text-lg font-bold text-slate-200 mt-1 truncate max-w-[150px]" id="kpiTopProfile">--</h3>
                    <p class="text-[11px] text-slate-400 mt-1">Mayoría de prospectos</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-blue-500/10 border border-blue-500/20 text-blue-400 flex items-center justify-center text-xl">
                    📊
                </div>
            </div>

        </div>

        <!-- Módulo de Validación Rápida de Canjes (Para la promotora en mostrador) -->
        <div class="bg-gradient-to-r from-slate-900 via-slate-900 to-slate-950 border border-slate-800 rounded-2xl p-5 shadow-lg">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-base font-bold text-white flex items-center gap-2">
                        <span>🔍</span> Validador de Cupones en Mostrador
                    </h2>
                    <p class="text-xs text-slate-400 mt-0.5">
                        El visitante muestra su pantalla de premio. Ingresá el código para verificarlo y marcarlo como entregado.
                    </p>
                </div>
                
                <form id="formCanje" class="flex gap-2 w-full md:w-auto">
                    <input type="text" id="inputCodigoCanje" placeholder="Ej. SP-KR-8472" required
                           class="uppercase font-mono-code px-4 py-2 bg-slate-950 border border-slate-700 rounded-xl text-emerald-400 font-bold text-sm tracking-wider focus:outline-none focus:border-emerald-500 w-full sm:w-48">
                    <button type="submit" id="btnCanjear"
                            class="px-5 py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-sm rounded-xl transition flex items-center gap-1.5 whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span>Canjear Premio</span>
                    </button>
                </form>
            </div>
            <div id="canjeAlert" class="mt-3 p-3 rounded-xl text-xs font-semibold hidden"></div>
        </div>

        <!-- Stock de Premios de la Ruleta -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
                <div>
                    <h2 class="text-base font-bold text-white flex items-center gap-2">
                        <span>🎡</span> Control y Edición de Premios (Ruleta)
                    </h2>
                    <p class="text-xs text-slate-400">Podés agregar nuevos premios, modificar nombres, colores, stock y probabilidades en tiempo real.</p>
                </div>
                <button onclick="abrirModalPremio()"
                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-amber-500/10 hover:bg-amber-500/20 text-amber-400 border border-amber-500/30 text-xs font-bold rounded-xl transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span>+ Agregar / Editar Premios</span>
                </button>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3" id="prizesContainer">
                <div class="text-xs text-slate-500 col-span-full py-4 text-center">Cargando premios...</div>
            </div>
        </div>

        <!-- Tabla de Leads en Vivo & Exportación -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-sm">
            
            <!-- Barra de Herramientas de la Tabla -->
            <div class="p-4 sm:p-5 border-b border-slate-800 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-base font-bold text-white flex items-center gap-2">
                        <span>📋</span> Visitantes Registrados (Leads Comerciales)
                    </h2>
                    <p class="text-xs text-slate-400 mt-0.5">Base de datos en vivo de personas que interactuaron con el stand.</p>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <!-- Buscador -->
                    <div class="relative w-full sm:w-64">
                        <input type="text" id="searchLead" placeholder="Buscar por nombre o ciudad..."
                               class="w-full pl-9 pr-3 py-2 bg-slate-950 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500">
                        <svg class="w-4 h-4 text-slate-500 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>

                    <!-- Botón Exportar Excel -->
                    <a id="btnExport" href="api.php?action=export_leads&slug=<?= urlencode($stand['slug']) ?>&pin=1234"
                       class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold rounded-xl transition shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <span>Descargar Excel (CSV)</span>
                    </a>
                </div>
            </div>

            <!-- Tabla -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-950/70 border-b border-slate-800 text-slate-400 font-semibold uppercase tracking-wider">
                            <th class="py-3 px-4">Fecha / Hora</th>
                            <th class="py-3 px-4">Visitante</th>
                            <th class="py-3 px-4">WhatsApp</th>
                            <th class="py-3 px-4">Ciudad</th>
                            <th class="py-3 px-4">Perfil / Hectáreas</th>
                            <th class="py-3 px-4">Premio Ruleta</th>
                            <th class="py-3 px-4 text-right">Contacto</th>
                        </tr>
                    </thead>
                    <tbody id="leadsTableBody" class="divide-y divide-slate-800/60 text-slate-300">
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-500">Cargando base de datos de leads...</td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>

    </main>

    <!-- MODAL: ADMINISTRAR / EDITAR PREMIO -->
    <div id="modalPremioEdit" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-sm flex items-center justify-center p-4 hidden">
        <div class="w-full max-w-md bg-slate-900 border border-slate-700 rounded-3xl p-6 shadow-2xl relative">
            <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                <h3 class="text-base font-bold text-white flex items-center gap-2" id="modalPremioTitulo">
                    <span>🎁</span> Configurar Premio de la Ruleta
                </h3>
                <button onclick="cerrarModalPremio()" class="text-slate-400 hover:text-white text-lg">✕</button>
            </div>

            <form id="formPremio" onsubmit="guardarPremio(event)" class="space-y-4 mt-4 text-xs">
                <input type="hidden" id="editPrizeId" value="0">

                <div>
                    <label class="block font-semibold text-slate-300 mb-1">Nombre del Premio o Mensaje *</label>
                    <input type="text" id="editPrizeName" required placeholder="Ej. Gorra Oficial John Deere"
                           class="w-full px-3 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-white text-xs focus:outline-none focus:border-amber-500">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-300 mb-1">Color del Segmento</label>
                        <div class="flex gap-2">
                            <input type="color" id="editPrizeColor" value="#16a34a"
                                   class="h-9 w-12 rounded-lg bg-slate-950 border border-slate-700 cursor-pointer">
                            <input type="text" id="editPrizeColorText" value="#16a34a"
                                   class="flex-1 px-2 py-2 bg-slate-950 border border-slate-700 rounded-xl text-white font-mono text-[11px]" readonly>
                        </div>
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-300 mb-1">¿Es Premio Físico?</label>
                        <select id="editPrizeIsReal" class="w-full px-3 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-white text-xs">
                            <option value="1">Sí (Entrega Regalo)</option>
                            <option value="0">No ("Seguí Participando")</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3" id="stockFieldsContainer">
                    <div>
                        <label class="block font-semibold text-slate-300 mb-1">Stock Total Inicial</label>
                        <input type="number" id="editPrizeTotalStock" min="0" value="50"
                               class="w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-xl text-white text-xs">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-300 mb-1">Stock Restante Actual</label>
                        <input type="number" id="editPrizeRemainingStock" min="0" value="50"
                               class="w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-xl text-white text-xs">
                    </div>
                </div>

                <div>
                    <label class="block font-semibold text-slate-300 mb-1">
                        Peso de Probabilidad (Mayor número = Más frecuente)
                    </label>
                    <input type="number" id="editPrizeProbWeight" min="1" max="100" value="15"
                           class="w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-xl text-white text-xs">
                    <p class="text-[10px] text-slate-500 mt-1">Ej: 5 para premios caros (menos frecuente), 30 para premios comunes o 'Seguí Participando'.</p>
                </div>

                <div class="flex items-center justify-between pt-3 border-t border-slate-800">
                    <button type="button" id="btnDeletePrize" onclick="eliminarPremio()" class="px-3 py-2 rounded-xl bg-rose-950/60 text-rose-400 hover:bg-rose-900 border border-rose-800 text-xs font-semibold hidden">
                        Eliminar
                    </button>
                    <div class="flex gap-2 ml-auto">
                        <button type="button" onclick="cerrarModalPremio()" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs rounded-xl font-bold">
                            Cancelar
                        </button>
                        <button type="submit" class="px-5 py-2 bg-amber-500 hover:bg-amber-400 text-slate-950 text-xs font-black rounded-xl transition">
                            Guardar Premio
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer class="mt-auto py-4 bg-slate-900 border-t border-slate-800 text-center text-xs text-slate-500">
        Panel de Control provisto por <a href="index.php" class="text-emerald-400 font-bold hover:underline">SotePass Expo</a> • Licencia B2B
    </footer>

    <!-- Script del Dashboard -->
    <script>
        const standSlug = '<?= htmlspecialchars($stand['slug']) ?>';
        const accessPin = '<?= htmlspecialchars($stand['access_pin']) ?>';
        let allLeads = [];
        let currentPrizes = [];

        // Color picker sync
        document.getElementById('editPrizeColor').addEventListener('input', (e) => {
            document.getElementById('editPrizeColorText').value = e.target.value;
        });

        function abrirModalPremio(p = null) {
            const modal = document.getElementById('modalPremioEdit');
            const title = document.getElementById('modalPremioTitulo');
            const btnDelete = document.getElementById('btnDeletePrize');

            if (p) {
                title.innerHTML = `<span>✏</span> Editar: ${p.name}`;
                document.getElementById('editPrizeId').value = p.id;
                document.getElementById('editPrizeName').value = p.name;
                document.getElementById('editPrizeColor').value = p.color;
                document.getElementById('editPrizeColorText').value = p.color;
                document.getElementById('editPrizeIsReal').value = p.is_real_prize ? "1" : "0";
                document.getElementById('editPrizeTotalStock').value = p.total_stock;
                document.getElementById('editPrizeRemainingStock').value = p.remaining_stock;
                document.getElementById('editPrizeProbWeight').value = p.probability_weight;
                btnDelete.classList.remove('hidden');
            } else {
                title.innerHTML = `<span>➕</span> Agregar Nuevo Premio a la Ruleta`;
                document.getElementById('editPrizeId').value = "0";
                document.getElementById('editPrizeName').value = "";
                document.getElementById('editPrizeColor').value = "#2563eb";
                document.getElementById('editPrizeColorText').value = "#2563eb";
                document.getElementById('editPrizeIsReal').value = "1";
                document.getElementById('editPrizeTotalStock').value = "20";
                document.getElementById('editPrizeRemainingStock').value = "20";
                document.getElementById('editPrizeProbWeight').value = "15";
                btnDelete.classList.add('hidden');
            }

            modal.classList.remove('hidden');
        }

        function cerrarModalPremio() {
            document.getElementById('modalPremioEdit').classList.add('hidden');
        }

        async function guardarPremio(e) {
            e.preventDefault();
            const payload = {
                slug: standSlug,
                pin: accessPin,
                prize_id: document.getElementById('editPrizeId').value,
                name: document.getElementById('editPrizeName').value,
                color: document.getElementById('editPrizeColor').value,
                is_real_prize: document.getElementById('editPrizeIsReal').value,
                total_stock: document.getElementById('editPrizeTotalStock').value,
                remaining_stock: document.getElementById('editPrizeRemainingStock').value,
                probability_weight: document.getElementById('editPrizeProbWeight').value
            };

            try {
                const res = await fetch('api.php?action=save_prize', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });
                const data = await res.json();
                if (data.success) {
                    cerrarModalPremio();
                    loadDashboard();
                } else {
                    alert(data.error || 'Error al guardar premio.');
                }
            } catch (err) {
                alert('Error de conexión.');
            }
        }

        async function eliminarPremio() {
            const prizeId = document.getElementById('editPrizeId').value;
            if (!confirm('¿Seguro que deseas eliminar este premio de la ruleta?')) return;

            try {
                const res = await fetch('api.php?action=delete_prize', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ slug: standSlug, pin: accessPin, prize_id: prizeId })
                });
                const data = await res.json();
                if (data.success) {
                    cerrarModalPremio();
                    loadDashboard();
                } else {
                    alert(data.error || 'Error al eliminar.');
                }
            } catch (e) {
                alert('Error de conexión.');
            }
        }

        async function ajustarStockRapido(prizeId, delta) {
            const prize = currentPrizes.find(p => p.id == prizeId);
            if (!prize) return;
            const newStock = Math.max(0, parseInt(prize.remaining_stock) + delta);

            try {
                const res = await fetch('api.php?action=update_prize_stock', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ slug: standSlug, pin: accessPin, prize_id: prizeId, remaining_stock: newStock })
                });
                const data = await res.json();
                if (data.success) {
                    loadDashboard();
                }
            } catch (e) {}
        }

        async function loadDashboard() {
            try {
                const res = await fetch(`api.php?action=get_dashboard&slug=${standSlug}&pin=${accessPin}`);
                const data = await res.json();

                if (data.error) {
                    alert(data.error);
                    return;
                }

                // Actualizar KPIs
                document.getElementById('kpiTotalLeads').innerText = data.kpis.total_leads.toLocaleString();
                document.getElementById('kpiTotalSpins').innerText = data.kpis.total_spins.toLocaleString();
                document.getElementById('kpiTotalRedeemed').innerText = data.kpis.total_redeemed.toLocaleString();
                document.getElementById('kpiTopProfile').innerText = data.kpis.top_profile;

                // Renderizar Premios
                renderPrizes(data.prizes);

                // Renderizar Leads
                allLeads = data.leads;
                renderLeads(allLeads);

            } catch (e) {
                console.error(e);
            }
        }

        function renderPrizes(prizes) {
            currentPrizes = prizes;
            const container = document.getElementById('prizesContainer');
            container.innerHTML = '';

            prizes.forEach(p => {
                const isOutOfStock = p.is_real_prize && p.remaining_stock <= 0;
                const card = document.createElement('div');
                card.className = `p-3 rounded-xl border ${isOutOfStock ? 'bg-rose-950/20 border-rose-800/40 text-slate-400' : 'bg-slate-950 border-slate-800'} text-center flex flex-col justify-between group relative`;
                card.innerHTML = `
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <span class="w-2.5 h-2.5 rounded-full" style="background-color: ${p.color};"></span>
                            <button onclick='abrirModalPremio(${JSON.stringify(p)})' title="Editar este premio"
                                    class="text-slate-500 hover:text-amber-400 transition p-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            </button>
                        </div>
                        <div class="w-8 h-8 rounded-lg mx-auto flex items-center justify-center font-bold text-white text-xs mb-1.5 shadow" style="background-color: ${p.color};">
                            ${p.is_real_prize ? '🎁' : '✨'}
                        </div>
                        <p class="font-bold text-white text-xs leading-tight line-clamp-2">${p.name}</p>
                    </div>
                    <div class="mt-2 pt-2 border-t border-slate-800/80">
                        <p class="text-[10px] text-slate-400 font-semibold uppercase">Stock Restante</p>
                        <div class="flex items-center justify-center gap-1.5 mt-0.5">
                            ${p.is_real_prize ? `
                                <button onclick="ajustarStockRapido(${p.id}, -1)" class="w-5 h-5 rounded bg-slate-800 hover:bg-slate-700 text-xs font-bold text-slate-300 flex items-center justify-center">-</button>
                                <span class="text-sm font-black ${isOutOfStock ? 'text-rose-400' : 'text-emerald-400'}">${p.remaining_stock}</span>
                                <button onclick="ajustarStockRapido(${p.id}, 1)" class="w-5 h-5 rounded bg-slate-800 hover:bg-slate-700 text-xs font-bold text-slate-300 flex items-center justify-center">+</button>
                            ` : `<span class="text-xs font-bold text-slate-400">Ilimitado</span>`}
                        </div>
                        <p class="text-[9px] text-slate-500 mt-1">Canjeados: ${p.total_redeemed || 0} / Prob: ${p.probability_weight}</p>
                    </div>
                `;
                container.appendChild(card);
            });
        }

        function renderLeads(leads) {
            const tbody = document.getElementById('leadsTableBody');
            if (!leads.length) {
                tbody.innerHTML = `<tr><td colspan="7" class="py-6 text-center text-slate-500">No hay registros aún.</td></tr>`;
                return;
            }

            tbody.innerHTML = leads.map(l => {
                const dateStr = new Date(l.created_at).toLocaleTimeString('es-PY', {hour: '2-digit', minute:'2-digit'});
                const cleanPhone = l.phone.replace(/[^0-9]/g, '');
                const waLink = `https://wa.me/595${cleanPhone.startsWith('0') ? cleanPhone.substring(1) : cleanPhone}?text=Hola%20${encodeURIComponent(l.full_name)},%20te%20saludamos%20desde%20el%20stand%20de%20<?= urlencode($stand['name']) ?>!`;

                let prizeBadge = `<span class="text-slate-500">No giró</span>`;
                if (l.prize_name) {
                    const badgeClass = l.is_redeemed == 1 ? 'bg-indigo-950 text-indigo-300 border-indigo-800' : 'bg-emerald-950 text-emerald-300 border-emerald-800';
                    const statusText = l.is_redeemed == 1 ? 'CANJEADO' : 'PENDIENTE';
                    prizeBadge = `
                        <div>
                            <span class="font-bold text-white">${l.prize_name}</span>
                            <div class="flex items-center gap-1.5 mt-0.5">
                                <span class="font-mono-code text-[10px] text-emerald-400 font-bold">${l.coupon_code || ''}</span>
                                <span class="text-[9px] px-1.5 py-0.2 rounded border ${badgeClass}">${statusText}</span>
                            </div>
                        </div>
                    `;
                }

                return `
                    <tr class="hover:bg-slate-800/40 transition">
                        <td class="py-3 px-4 whitespace-nowrap text-slate-400">${dateStr}</td>
                        <td class="py-3 px-4 font-bold text-white">${l.full_name}</td>
                        <td class="py-3 px-4 font-mono-code text-slate-300">${l.phone}</td>
                        <td class="py-3 px-4">${l.city}</td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-0.5 rounded-full bg-slate-800 text-[10px] font-semibold text-slate-300">${l.profile}</span>
                            ${l.hectares ? `<span class="text-[10px] text-slate-500 block mt-0.5">${l.hectares}</span>` : ''}
                        </td>
                        <td class="py-3 px-4">${prizeBadge}</td>
                        <td class="py-3 px-4 text-right whitespace-nowrap">
                            <a href="${waLink}" target="_blank" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-600/20 text-emerald-400 hover:bg-emerald-600/30 text-[11px] font-semibold transition">
                                <span>WhatsApp</span>
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                            </a>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        // Buscador de Leads
        document.getElementById('searchLead').addEventListener('input', (e) => {
            const query = e.target.value.toLowerCase().trim();
            const filtered = allLeads.filter(l => 
                l.full_name.toLowerCase().includes(query) || 
                l.city.toLowerCase().includes(query) ||
                l.phone.includes(query)
            );
            renderLeads(filtered);
        });

        // Formulario de Canje en Mostrador
        document.getElementById('formCanje').addEventListener('submit', async (e) => {
            e.preventDefault();
            const input = document.getElementById('inputCodigoCanje');
            const code = input.value.trim().toUpperCase();
            const alertBox = document.getElementById('canjeAlert');

            try {
                const res = await fetch('api.php?action=redeem_coupon', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ slug: standSlug, pin: accessPin, coupon_code: code })
                });

                const data = await res.json();
                alertBox.classList.remove('hidden');

                if (data.success) {
                    alertBox.className = "mt-3 p-3 rounded-xl text-xs font-semibold bg-emerald-950/80 border border-emerald-600 text-emerald-300";
                    alertBox.innerHTML = `✔ <strong>¡CANJE VÁLIDO!</strong> Entregar: <span class="text-white text-sm underline">${data.lead.prize_name}</span> a <strong>${data.lead.full_name}</strong> (${data.lead.city}).`;
                    input.value = '';
                    loadDashboard(); // Recargar datos
                } else {
                    alertBox.className = "mt-3 p-3 rounded-xl text-xs font-semibold bg-rose-950/80 border border-rose-600 text-rose-300";
                    alertBox.innerHTML = `⚠ <strong>ERROR:</strong> ${data.message || data.error}`;
                }
            } catch (err) {
                alert('Error al validar el cupón.');
            }
        });

        // Inicializar
        loadDashboard();
        // Polling cada 15 segundos para datos frescos
        setInterval(loadDashboard, 15000);
    </script>
</body>
</html>

