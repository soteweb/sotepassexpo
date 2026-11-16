<?php
// registro.php - Landing mobile de registro del stand (SotePass Expo)
require_once __DIR__.'/config.php';

$slug = trim($_GET['stand'] ?? 'kurosu');
$db = getDB();

$stmt = $db->prepare('SELECT * FROM stands WHERE slug = ? AND is_active = 1');
$stmt->execute([$slug]);
$stand = $stmt->fetch();

if (! $stand) {
    exit("Stand no encontrado o inactivo. <a href='index.php'>Volver al inicio</a>");
}

$primaryColor = $stand['primary_color'] ?: '#16a34a';
$secondaryColor = $stand['secondary_color'] ?: '#0f172a';
$isTotem = isset($_GET['totem']) && $_GET['totem'] == '1';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido a <?= htmlspecialchars($stand['name']) ?> | SotePass Expo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        :root {
            --brand-primary: <?= $primaryColor ?>;
            --brand-secondary: <?= $secondaryColor ?>;
        }
        .bg-brand { background-color: var(--brand-primary); }
        .text-brand { color: var(--brand-primary); }
        .border-brand { border-color: var(--brand-primary); }
        .ring-brand { --tw-ring-color: var(--brand-primary); }
    </style>
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen flex items-center justify-center p-3 sm:p-6 antialiased selection:bg-emerald-500 selection:text-white">

    <!-- Card Principal Mobile First -->
    <div class="w-full max-w-md bg-slate-950 rounded-3xl border border-slate-800 shadow-2xl overflow-hidden relative">
        
        <!-- Header con Identidad del Stand y SotePass -->
        <div class="relative p-6 pb-8 text-center overflow-hidden" style="background: linear-gradient(135deg, <?= $primaryColor ?> 0%, <?= $secondaryColor ?> 100%);">
            <!-- Efecto sutil de círculos -->
            <div class="absolute -right-12 -top-12 w-40 h-40 bg-white/10 rounded-full blur-xl pointer-events-none"></div>
            <div class="absolute -left-12 -bottom-12 w-40 h-40 bg-black/20 rounded-full blur-xl pointer-events-none"></div>

            <!-- Badge Superior SotePass Expo -->
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-black/30 backdrop-blur-md text-[11px] font-semibold text-white tracking-wider uppercase mb-4 border border-white/10">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                SotePass Expo • <?= htmlspecialchars($stand['stand_number']) ?>
            </div>

            <!-- Nombre de la Empresa -->
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight leading-tight drop-shadow-sm">
                <?= htmlspecialchars($stand['name']) ?>
            </h1>
            <p class="text-xs text-white/80 font-medium mt-1">
                <?= htmlspecialchars($stand['sector']) ?>
            </p>
        </div>

        <!-- Cuerpo del Registro -->
        <div class="p-6 -mt-4 bg-slate-950 rounded-t-3xl relative z-10">
            
            <div class="text-center mb-6">
                <span class="inline-block text-2xl mb-1">🎁</span>
                <h2 class="text-lg font-bold text-white">¡Girá la Ruleta de Premios!</h2>
                <p class="text-xs text-slate-400 mt-1">
                    Registrate en 30 segundos, descubrí tu premio instantáneo y participá del sorteo especial de nuestro stand.
                </p>
            </div>

            <!-- Formulario de Captación -->
            <form id="leadForm" class="space-y-4">
                <input type="hidden" name="slug" value="<?= htmlspecialchars($stand['slug']) ?>">

                <!-- Nombre Completo -->
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">
                        Nombre Completo <span class="text-rose-400">*</span>
                    </label>
                    <input type="text" name="full_name" required placeholder="Ej. Carlos Ferreira"
                        class="w-full px-4 py-3 bg-slate-900 border border-slate-700/80 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition">
                </div>

                <!-- WhatsApp -->
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">
                        Número de WhatsApp <span class="text-rose-400">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-emerald-400">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.007c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.861.173.086.275.072.376-.044.101-.116.433-.506.549-.68.116-.173.231-.145.39-.086s1.011.477 1.184.564.289.13.332.202c.043.073.043.419-.101.824z"/></svg>
                        </div>
                        <input type="tel" name="phone" required placeholder="0981 123456"
                            class="w-full pl-10 pr-4 py-3 bg-slate-900 border border-slate-700/80 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition">
                    </div>
                </div>

                <!-- Ciudad de Origen -->
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">
                        Ciudad / Localidad <span class="text-rose-400">*</span>
                    </label>
                    <input type="text" name="city" required placeholder="Ej. Hohenau, Encarnación, Santa Rita..."
                        class="w-full px-4 py-3 bg-slate-900 border border-slate-700/80 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition">
                </div>

                <!-- Perfil (Clasificación Comercial) -->
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">
                        ¿Cuál es tu actividad principal?
                    </label>
                    <select name="profile" class="w-full px-4 py-3 bg-slate-900 border border-slate-700/80 rounded-xl text-sm text-white focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition">
                        <option value="Productor / Agricultor">🌾 Productor / Agricultor</option>
                        <option value="Contratista Rural">🚜 Contratista Rural / Servicios</option>
                        <option value="Empresa / Distribuidor">🏢 Empresa / Comercial</option>
                        <option value="Estudiante / Académico">🎓 Estudiante / Profesional Técnico</option>
                        <option value="Público General">✨ Público General / Aficionado</option>
                    </select>
                </div>

                <!-- Hectáreas aproximadas (Opcional) -->
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">
                        Superficie que trabaja (Hectáreas) <span class="text-slate-500 font-normal">(Opcional)</span>
                    </label>
                    <select name="hectares" class="w-full px-4 py-3 bg-slate-900 border border-slate-700/80 rounded-xl text-sm text-white focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition">
                        <option value="">Seleccionar rango...</option>
                        <option value="Menos de 50 ha">Menos de 50 ha</option>
                        <option value="50 a 200 ha">50 a 200 ha</option>
                        <option value="200 a 500 ha">200 a 500 ha</option>
                        <option value="500 a 1.000 ha">500 a 1.000 ha</option>
                        <option value="Más de 1.000 ha">Más de 1.000 ha</option>
                    </select>
                </div>

                <!-- Botón de Envío -->
                <div class="pt-2">
                    <button type="submit" id="btnSubmit"
                        class="w-full py-4 px-6 rounded-xl font-bold text-white text-base shadow-lg shadow-emerald-500/20 transform transition active:scale-[0.98] flex items-center justify-center gap-2"
                        style="background: linear-gradient(135deg, <?= $primaryColor ?> 0%, #059669 100%);">
                        <span>Continuar y Girar Ruleta</span>
                        <svg class="w-5 h-5 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                </div>
            </form>

            <div id="statusMsg" class="mt-4 text-center text-xs hidden"></div>

        </div>

        <!-- Footer SotePass -->
        <div class="px-6 py-4 bg-slate-900 border-t border-slate-800 text-center text-xs text-slate-500">
            Tecnología y Sorteos provistos por <a href="index.php" class="font-bold text-emerald-400 hover:underline">SotePass Expo</a>
        </div>

    </div>

    <!-- Script de Envío AJAX -->
    <script>
        const leadForm = document.getElementById('leadForm');
        const btnSubmit = document.getElementById('btnSubmit');
        const statusMsg = document.getElementById('statusMsg');

        leadForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            btnSubmit.disabled = true;
            btnSubmit.innerHTML = `<svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg> <span>Guardando datos...</span>`;
            statusMsg.classList.add('hidden');

            const formData = new FormData(leadForm);
            const payload = Object.fromEntries(formData.entries());

            try {
                const response = await fetch('api.php?action=register_lead', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });

                const res = await response.json();

                if (res.success && res.lead_id) {
                    statusMsg.className = "mt-4 text-center text-xs text-emerald-400 font-semibold";
                    statusMsg.innerText = "¡Registro exitoso! Abriendo la Ruleta de Premios...";
                    statusMsg.classList.remove('hidden');

                    // Guardar datos en sessionStorage para fluidez
                    sessionStorage.setItem('sotepass_lead_id', res.lead_id);
                    sessionStorage.setItem('sotepass_lead_name', res.full_name);
                    sessionStorage.setItem('sotepass_stand_slug', payload.slug);

                    const isTotem = <?= $isTotem ? 'true' : 'false' ?>;
                    // Redirección inmediata a la Ruleta
                    setTimeout(() => {
                        window.location.href = `ruleta.php?stand=${payload.slug}&lead_id=${res.lead_id}${isTotem ? '&totem=1' : ''}`;
                    }, 800);
                } else {
                    throw new Error(res.error || 'Error al procesar el registro.');
                }
            } catch (err) {
                btnSubmit.disabled = false;
                btnSubmit.innerHTML = `<span>Reintentar</span>`;
                statusMsg.className = "mt-4 text-center text-xs text-rose-400 font-semibold";
                statusMsg.innerText = err.message;
                statusMsg.classList.remove('hidden');
            }
        });
    </script>
</body>
</html>

