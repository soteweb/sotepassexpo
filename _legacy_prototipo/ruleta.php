<?php
// ruleta.php - Ruleta Interactiva de Premios (SotePass Expo)
require_once __DIR__.'/config.php';

$slug = trim($_GET['stand'] ?? 'kurosu');
$leadId = intval($_GET['lead_id'] ?? 0);
$db = getDB();

$stmt = $db->prepare('SELECT * FROM stands WHERE slug = ? AND is_active = 1');
$stmt->execute([$slug]);
$stand = $stmt->fetch();

if (! $stand) {
    exit("Stand no válido. <a href='index.php'>Volver</a>");
}

// Si no hay lead_id en URL, verificar si hay un lead existente o redirigir al registro
if ($leadId <= 0) {
    header('Location: registro.php?stand='.urlencode($slug));
    exit;
}

// Obtener datos del lead
$leadStmt = $db->prepare('SELECT id, full_name, city, phone FROM leads WHERE id = ? AND stand_id = ?');
$leadStmt->execute([$leadId, $stand['id']]);
$lead = $leadStmt->fetch();

$isTotem = isset($_GET['totem']) && $_GET['totem'] == '1';

if (! $lead) {
    header('Location: registro.php?stand='.urlencode($slug).($isTotem ? '&totem=1' : ''));
    exit;
}

// Verificar si ya giró previamente
$spinStmt = $db->prepare('
    SELECT s.*, p.name as prize_name, p.is_real_prize 
    FROM ruleta_spins s 
    JOIN prizes p ON s.prize_id = p.id 
    WHERE s.lead_id = ? AND s.stand_id = ?
');
$spinStmt->execute([$lead['id'], $stand['id']]);
$existingSpin = $spinStmt->fetch();

// Obtener lista de premios
$prizesStmt = $db->prepare('SELECT id, name, color, text_color, is_real_prize, icon_badge FROM prizes WHERE stand_id = ? ORDER BY id ASC');
$prizesStmt->execute([$stand['id']]);
$prizes = $prizesStmt->fetchAll();

$primaryColor = $stand['primary_color'] ?: '#16a34a';
$secondaryColor = $stand['secondary_color'] ?: '#0f172a';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ruleta de Premios | <?= htmlspecialchars($stand['name']) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800;900&family=Share+Tech+Mono&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-mono-code { font-family: 'Share Tech Mono', monospace; }
        
        /* Puntero de la Ruleta */
        .ruleta-pointer {
            position: absolute;
            top: -12px;
            left: 50%;
            transform: translateX(-50%);
            width: 32px;
            height: 42px;
            background: linear-gradient(to bottom, #f59e0b, #d97706);
            clip-path: polygon(50% 100%, 0 0, 100% 0);
            z-index: 30;
            filter: drop-shadow(0 4px 6px rgba(0,0,0,0.5));
            transition: transform 0.1s ease;
        }

        .ruleta-pointer.tick {
            transform: translateX(-50%) rotate(-12deg);
        }

        /* Centro decorativo */
        .ruleta-center-cap {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 68px;
            height: 68px;
            background: radial-gradient(circle, #ffffff 30%, #e2e8f0 70%, #94a3b8 100%);
            border-radius: 50%;
            box-shadow: 0 4px 15px rgba(0,0,0,0.4), inset 0 2px 4px rgba(255,255,255,0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 20;
            border: 4px solid #1e293b;
        }

        /* Borde exterior iluminado */
        .ruleta-rim {
            border: 8px solid #1e293b;
            box-shadow: 0 0 35px rgba(34, 197, 94, 0.25), inset 0 0 15px rgba(0,0,0,0.5);
        }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex flex-col items-center justify-between p-3 sm:p-6 antialiased selection:bg-emerald-500">

    <!-- Top Header -->
    <header class="w-full <?= $isTotem ? 'max-w-2xl' : 'max-w-md sm:max-w-lg' ?> text-center py-2 relative">
        <!-- Botones Superiores: Pantalla Completa & Modo Tótem -->
        <div class="absolute right-0 top-1 flex items-center gap-1.5">
            <a href="ruleta.php?stand=<?= urlencode($slug) ?>&lead_id=<?= $leadId ?>&totem=<?= $isTotem ? '0' : '1' ?>" 
               title="Alternar Modo Tótem / TV Touch Vertical"
               class="p-2 rounded-xl border <?= $isTotem ? 'bg-amber-500/20 text-amber-300 border-amber-500/40' : 'bg-slate-900 text-slate-400 border-slate-800' ?> hover:text-white transition text-xs flex items-center gap-1">
                <span class="text-xs">📺</span>
                <span class="hidden sm:inline font-bold text-[10px]"><?= $isTotem ? 'Tótem TV' : 'Modo TV' ?></span>
            </a>
            <button onclick="toggleFullScreen()" title="Pantalla Completa (F11)"
                    class="p-2 rounded-xl bg-slate-900 border border-slate-800 text-slate-400 hover:text-white transition text-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path></svg>
            </button>
        </div>

        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-900 border border-slate-800 text-[11px] font-bold text-slate-300 uppercase tracking-wider mb-2">
            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
            SotePass Expo • <?= htmlspecialchars($stand['stand_number']) ?>
            <?php if ($isTotem) { ?>
                <span class="text-amber-400 ml-1">• MODO TÓTEM VERTICAL</span>
            <?php } ?>
        </div>
        <h1 class="<?= $isTotem ? 'text-2xl sm:text-4xl' : 'text-xl sm:text-2xl' ?> font-black text-white tracking-tight">
            <?= htmlspecialchars($stand['name']) ?>
        </h1>
        <p class="<?= $isTotem ? 'text-sm sm:text-base' : 'text-xs' ?> text-slate-400 mt-0.5">
            Participante: <strong class="text-emerald-400"><?= htmlspecialchars($lead['full_name']) ?></strong> (<?= htmlspecialchars($lead['city']) ?>)
        </p>
    </header>

    <!-- Área de la Ruleta -->
    <main class="w-full <?= $isTotem ? 'max-w-2xl' : 'max-w-md sm:max-w-lg' ?> flex flex-col items-center justify-center my-auto py-4">

        <!-- Contenedor de la Ruleta (Escalado Dinámico para TV Touch Vertical) -->
        <div class="relative <?= $isTotem ? 'w-[320px] h-[320px] sm:w-[460px] sm:h-[460px] md:w-[540px] md:h-[540px]' : 'w-[320px] h-[320px] sm:w-[380px] sm:h-[380px]' ?> flex items-center justify-center transition-all duration-300">
            
            <!-- Puntero superior -->
            <div id="ruletaPointer" class="ruleta-pointer"></div>

            <!-- Canvas de la Ruleta en Alta Resolución (600x600 interno) -->
            <div class="w-full h-full rounded-full ruleta-rim relative overflow-hidden bg-slate-900 flex items-center justify-center">
                <canvas id="ruletaCanvas" width="600" height="600" class="w-full h-full rounded-full"></canvas>
            </div>

            <!-- Centro decorativo con logo / estrella -->
            <div class="ruleta-center-cap <?= $isTotem ? 'w-20 h-20 text-2xl' : 'w-16 h-16 text-xl' ?>">
                <span>⭐</span>
            </div>

        </div>

        <!-- Botón de Giro (Grande y táctil para pantallas táctiles) -->
        <div class="w-full mt-6 px-4">
            <?php if ($existingSpin) { ?>
                <button onclick="mostrarCuponExistente()"
                    class="w-full py-4 sm:py-5 px-6 rounded-2xl font-black text-white text-lg sm:text-xl shadow-xl shadow-emerald-500/20 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 transform transition active:scale-95 flex items-center justify-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                    <span>Ver mi Cupón de Premio</span>
                </button>
                <p class="text-center text-xs text-slate-400 mt-2">Ya giraste la ruleta en esta visita. Podés volver a ver tu pase de canje.</p>
            <?php } else { ?>
                <button id="btnGirar" onclick="ejecutarGiro()"
                    class="w-full py-4 sm:py-5 px-6 rounded-2xl font-black text-white text-lg sm:text-2xl tracking-wide uppercase shadow-2xl shadow-amber-500/30 bg-gradient-to-r from-amber-500 via-orange-500 to-amber-600 hover:brightness-110 transform transition active:scale-95 flex items-center justify-center gap-3">
                    <span class="text-2xl sm:text-3xl animate-bounce">🎯</span>
                    <span>¡TOCÁ AQUÍ PARA GIRAR!</span>
                </button>
                <p id="spinNotice" class="text-center text-xs sm:text-sm text-slate-400 mt-2">Tenés 1 oportunidad de ganar un premio instantáneo.</p>
            <?php } ?>
        </div>

    </main>
        </div>

    </main>

    <!-- Footer -->
    <footer class="w-full max-w-md py-3 text-center text-xs text-slate-600 border-t border-slate-900">
        SotePass Expo • Certificación de Ruletas y Sorteos
    </footer>

    <!-- MODAL: PASE DIGITAL SOTEPASS (CUPÓN DE PREMIO) -->
    <div id="modalPremio" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-md flex items-center justify-center p-4 hidden">
        <div class="w-full max-w-sm bg-slate-900 border-2 border-emerald-500/50 rounded-3xl shadow-2xl p-6 text-center relative overflow-hidden transform transition-all scale-95 duration-300" id="modalCard">
            
            <!-- Cinta decorativa -->
            <div class="absolute -top-10 -right-10 w-28 h-28 bg-emerald-500/20 rounded-full blur-2xl pointer-events-none"></div>

            <div class="w-16 h-16 mx-auto mb-3 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-center text-3xl" id="modalEmoji">
                🎁
            </div>

            <span class="text-[11px] font-bold uppercase tracking-widest text-emerald-400 bg-emerald-950/80 px-3 py-1 rounded-full border border-emerald-800">
                Pase Digital de Canje
            </span>

            <h3 class="text-2xl font-black text-white mt-3 leading-snug" id="modalTituloPremio">
                Gorra Oficial John Deere
            </h3>

            <p class="text-xs text-slate-300 mt-2 px-2" id="modalDescPremio">
                ¡Felicidades <strong class="text-white"><?= htmlspecialchars($lead['full_name']) ?></strong>! Presentá este código en el mostrador del stand para retirar tu regalo.
            </p>

            <!-- Ticket / Código Cupón con estilo SotePass -->
            <div class="my-5 p-4 rounded-2xl bg-slate-950 border border-slate-800 relative">
                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">Código de Verificación Antifraude</p>
                <div class="text-3xl font-mono-code font-black text-emerald-400 tracking-widest mt-1" id="modalCodigo">
                    SP-KR-8472
                </div>
                <div class="flex items-center justify-between text-[11px] text-slate-400 border-t border-slate-800/80 mt-3 pt-2">
                    <span>Stand: <strong><?= htmlspecialchars($stand['stand_number']) ?></strong></span>
                    <span id="horaEmision" class="font-mono text-slate-300"></span>
                </div>
            </div>

            <div class="space-y-2">
                <?php if ($isTotem) { ?>
                    <a href="registro.php?stand=<?= urlencode($slug) ?>&totem=1" class="w-full py-4 bg-emerald-500 hover:bg-emerald-400 text-slate-950 text-base font-black rounded-xl transition flex items-center justify-center gap-2 shadow-lg shadow-emerald-500/30">
                        <span>▶ ¡Siguiente Participante!</span>
                        <span id="totemCountDown" class="text-xs bg-slate-950/20 px-2 py-0.5 rounded-full font-mono">15s</span>
                    </a>
                <?php } ?>
                <button onclick="cerrarModal()" class="w-full py-3 bg-slate-800 hover:bg-slate-700 text-slate-200 text-sm font-bold rounded-xl transition">
                    Cerrar y Guardar Pantalla
                </button>
            </div>

            <div class="mt-4 pt-3 border-t border-slate-800/60 text-[10px] text-slate-500">
                Verificado por SotePass Expo • Uso único e intransferible
            </div>

        </div>
    </div>

    <!-- Script de Ruleta con Físicas y Web Audio API -->
    <script>
        const isTotemMode = <?= $isTotem ? 'true' : 'false' ?>;
        const prizes = <?= json_encode($prizes, JSON_UNESCAPED_UNICODE) ?>;
        const standSlug = '<?= htmlspecialchars($stand['slug']) ?>';
        const leadId = <?= $leadId ?>;
        const canvas = document.getElementById('ruletaCanvas');
        const ctx = canvas.getContext('2d');
        const pointer = document.getElementById('ruletaPointer');
        const btnGirar = document.getElementById('btnGirar');
        const numSlices = prizes.length;
        const sliceAngle = (2 * Math.PI) / numSlices;

        let currentAngle = 0;
        let isSpinning = false;
        let totemTimerInterval = null;

        function toggleFullScreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().catch(() => {});
            } else {
                if (document.exitFullscreen) document.exitFullscreen();
            }
        }

        // Web Audio API para el sonido de ticker mecánico
        let audioCtx = null;
        function playTickSound() {
            try {
                if (!audioCtx) {
                    audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                }
                const osc = audioCtx.createOscillator();
                const gain = audioCtx.createGain();
                osc.type = 'triangle';
                osc.frequency.setValueAtTime(450, audioCtx.currentTime);
                osc.frequency.exponentialRampToValueAtTime(120, audioCtx.currentTime + 0.04);
                gain.gain.setValueAtTime(0.3, audioCtx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 0.04);
                osc.connect(gain);
                gain.connect(audioCtx.destination);
                osc.start();
                osc.stop(audioCtx.currentTime + 0.04);

                // Pequeña vibración en el puntero
                pointer.classList.add('tick');
                setTimeout(() => pointer.classList.remove('tick'), 60);
            } catch (e) {
                // Audio no soportado o bloqueado
            }
        }

        // Dibujar Ruleta
        function drawWheel() {
            const centerX = canvas.width / 2;
            const centerY = canvas.height / 2;
            const radius = centerX - 8;

            ctx.clearRect(0, 0, canvas.width, canvas.height);

            for (let i = 0; i < numSlices; i++) {
                const startAngle = currentAngle + i * sliceAngle;
                const endAngle = startAngle + sliceAngle;
                const p = prizes[i];

                // Segmento
                ctx.beginPath();
                ctx.moveTo(centerX, centerY);
                ctx.arc(centerX, centerY, radius, startAngle, endAngle);
                ctx.closePath();
                ctx.fillStyle = p.color || '#16a34a';
                ctx.fill();
                ctx.lineWidth = 2;
                ctx.strokeStyle = '#0f172a';
                ctx.stroke();

                // Texto del premio
                ctx.save();
                ctx.translate(centerX, centerY);
                ctx.rotate(startAngle + sliceAngle / 2);
                ctx.textAlign = 'right';
                ctx.fillStyle = p.text_color || '#ffffff';
                ctx.font = 'bold 16px "Plus Jakarta Sans", sans-serif';
                ctx.shadowColor = 'rgba(0,0,0,0.7)';
                ctx.shadowBlur = 5;
                
                // Truncar texto largo
                let text = p.name;
                if (text.length > 22) text = text.substring(0, 20) + '...';
                ctx.fillText(text, radius - 30, 6);
                ctx.restore();
            }
        }

        drawWheel();

        // Ejecutar Giro sincronizado con el Backend
        async function ejecutarGiro() {
            if (isSpinning) return;
            isSpinning = true;
            if (btnGirar) btnGirar.disabled = true;

            try {
                // Solicitar al servidor el premio de forma justa
                const res = await fetch('api.php?action=spin_ruleta', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ slug: standSlug, lead_id: leadId })
                });

                const data = await res.json();

                if (!data.success) {
                    alert(data.error || 'Error al girar la ruleta.');
                    isSpinning = false;
                    if (btnGirar) btnGirar.disabled = false;
                    return;
                }

                // Iniciar animación física
                const targetSliceIndex = data.slice_index;
                animarGiro(targetSliceIndex, data);

            } catch (err) {
                alert('Hubo un problema de conexión. Intente nuevamente.');
                isSpinning = false;
                if (btnGirar) btnGirar.disabled = false;
            }
        }

        // Animación suave de giro con cálculo angular exacto
        function animarGiro(targetIndex, prizeData) {
            const spins = 5; // Vueltas completas
            // El puntero está arriba: 270 grados (3*PI / 2)
            const pointerAngle = (3 * Math.PI) / 2;
            const targetSliceCenter = (targetIndex * sliceAngle) + (sliceAngle / 2);
            
            // Calculamos ángulo final objetivo
            const normalizedCurrent = currentAngle % (2 * Math.PI);
            let finalTargetAngle = (spins * 2 * Math.PI) + (pointerAngle - targetSliceCenter);
            
            // Asegurar que avance hacia adelante
            while (finalTargetAngle < currentAngle + (spins * 2 * Math.PI)) {
                finalTargetAngle += 2 * Math.PI;
            }

            const startAngle = currentAngle;
            const distance = finalTargetAngle - startAngle;
            const duration = 5000; // 5 segundos de suspenso
            const startTime = performance.now();
            let lastTickSlice = -1;

            function step(now) {
                const elapsed = now - startTime;
                const progress = Math.min(elapsed / duration, 1);
                
                // Easing cubic out (deceleración realista)
                const easeOut = 1 - Math.pow(1 - progress, 3);
                currentAngle = startAngle + (distance * easeOut);
                
                // Sonido de tick cada vez que un separador cruza el puntero
                const relativeAngle = ((pointerAngle - currentAngle) % (2 * Math.PI) + 2 * Math.PI) % (2 * Math.PI);
                const currentSlice = Math.floor(relativeAngle / sliceAngle);
                if (currentSlice !== lastTickSlice) {
                    playTickSound();
                    lastTickSlice = currentSlice;
                }

                drawWheel();

                if (progress < 1) {
                    requestAnimationFrame(step);
                } else {
                    isSpinning = false;
                    onFinGiro(prizeData);
                }
            }

            requestAnimationFrame(step);
        }

        function onFinGiro(data) {
            // Disparar confeti si es premio real
            if (data.is_real_prize) {
                confetti({
                    particleCount: 100,
                    spread: 70,
                    origin: { y: 0.6 }
                });
            }

            // Mostrar el Pase de Canje SotePass
            mostrarModalPremio(data.prize_name, data.coupon_code, data.is_real_prize);
        }

        function mostrarModalPremio(nombre, codigo, esReal) {
            document.getElementById('modalTituloPremio').innerText = nombre;
            document.getElementById('modalCodigo').innerText = codigo;
            document.getElementById('modalEmoji').innerText = esReal ? '🎉' : '👏';
            document.getElementById('horaEmision').innerText = 'Emitido: ' + new Date().toLocaleTimeString('es-PY', {hour: '2-digit', minute:'2-digit'});
            
            const modal = document.getElementById('modalPremio');
            const card = document.getElementById('modalCard');
            modal.classList.remove('hidden');
            setTimeout(() => {
                card.classList.remove('scale-95');
                card.classList.add('scale-100');
            }, 50);

            // Temporizador automático si está en modo Tótem / Kiosco TV Touch
            if (isTotemMode) {
                let timeLeft = 15;
                const cdEl = document.getElementById('totemCountDown');
                if (totemTimerInterval) clearInterval(totemTimerInterval);
                totemTimerInterval = setInterval(() => {
                    timeLeft--;
                    if (cdEl) cdEl.innerText = timeLeft + 's';
                    if (timeLeft <= 0) {
                        clearInterval(totemTimerInterval);
                        window.location.href = `registro.php?stand=${standSlug}&totem=1`;
                    }
                }, 1000);
            }
        }

        function cerrarModal() {
            if (totemTimerInterval) clearInterval(totemTimerInterval);
            const modal = document.getElementById('modalPremio');
            modal.classList.add('hidden');
            if (isTotemMode) {
                window.location.href = `registro.php?stand=${standSlug}&totem=1`;
            } else {
                window.location.reload();
            }
        }

        function mostrarCuponExistente() {
            <?php if ($existingSpin) { ?>
            mostrarModalPremio(
                <?= json_encode($existingSpin['prize_name']) ?>, 
                <?= json_encode($existingSpin['coupon_code']) ?>, 
                <?= $existingSpin['is_real_prize'] ? 'true' : 'false' ?>
            );
            <?php } ?>
        }
    </script>
</body>
</html>

