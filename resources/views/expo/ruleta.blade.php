<x-guest-layout>
    <x-slot:title>Ruleta de Premios | {{ $stand->name }}</x-slot>

    <style>
        .ruleta-pointer-wrap {
            position: absolute;
            top: -14px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 40;
            filter: drop-shadow(0 6px 12px rgba(0,0,0,0.6));
            pointer-events: none;
        }
        .ruleta-pointer {
            width: 40px;
            height: 52px;
            background: linear-gradient(145deg, #fef08a 0%, #eab308 25%, #ca8a04 65%, #78350f 100%);
            clip-path: polygon(50% 100%, 0 0, 100% 0);
            border-radius: 4px;
            box-shadow: inset 0 2px 4px rgba(255,255,255,0.9), inset 0 -2px 4px rgba(0,0,0,0.5);
            transform-origin: 50% 15%;
            transition: transform 0.08s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .ruleta-pointer.tick {
            transform: rotate(-18deg) scale(1.08);
        }
        .ruleta-center-cap {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            border-radius: 50%;
            background: radial-gradient(circle at 35% 35%, #ffffff 0%, #f1f5f9 25%, #cbd5e1 55%, #64748b 85%, #334155 100%);
            box-shadow: 0 10px 25px rgba(0,0,0,0.5), inset 0 3px 6px rgba(255,255,255,0.9), inset 0 -3px 6px rgba(0,0,0,0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 30;
            cursor: pointer;
            transition: transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        .ruleta-center-cap:hover {
            transform: translate(-50%, -50%) scale(1.05);
        }
        .ruleta-center-cap:active {
            transform: translate(-50%, -50%) scale(0.95);
        }
        .ruleta-rim {
            border: 10px solid #0f172a;
            box-shadow: 0 0 45px rgba(99, 102, 241, 0.25), 0 20px 40px rgba(0,0,0,0.6), inset 0 0 20px rgba(0,0,0,0.8);
        }
        @keyframes pulse-ring {
            0% { transform: scale(0.95); opacity: 0.8; }
            50% { transform: scale(1.05); opacity: 0.4; }
            100% { transform: scale(0.95); opacity: 0.8; }
        }
        .pulse-cap-glow {
            animation: pulse-ring 2.5s infinite ease-in-out;
        }
    </style>

    <div class="min-h-screen flex flex-col items-center justify-between p-3 sm:p-6 antialiased select-none">

        <!-- Top Header con Identidad Corporativa y Atajos Rápidos -->
        <header class="w-full {{ $isTotem ? 'max-w-3xl' : 'max-w-xl sm:max-w-2xl' }} text-center py-2 relative flex items-center justify-between gap-3">
            
            <!-- Botón Volver al Formulario de Registro (Flujo Rápido de Promotor) -->
            <a href="{{ route('expo.stand', ['slug' => $stand->slug, 'totem' => $isTotem ? 1 : null]) }}"
               title="Volver al Formulario de Registro"
               class="p-2 sm:px-3 sm:py-2 rounded-xl bg-slate-900/90 border border-slate-800 text-slate-300 hover:text-white hover:bg-slate-800 transition text-xs flex items-center gap-1.5 shadow-xs shrink-0 cursor-pointer">
                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                <span class="hidden sm:inline font-bold text-[11px]">Registro</span>
            </a>

            <!-- Identidad de la Empresa: Logo + Nombre -->
            <div class="flex items-center justify-center gap-2.5 sm:gap-3 flex-1 min-w-0">
                @if($stand->logo_url)
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-2xl bg-white p-1.5 shadow-md border-2 border-white flex items-center justify-center overflow-hidden shrink-0">
                        <img src="{{ asset($stand->logo_url) }}" alt="{{ $stand->name }}" class="max-w-full max-h-full object-contain">
                    </div>
                @endif
                <div class="text-left truncate">
                    <h1 class="{{ $isTotem ? 'text-xl sm:text-3xl' : 'text-lg sm:text-xl' }} font-black text-white tracking-tight truncate">
                        {{ $stand->name }}
                    </h1>
                    <div class="flex items-center gap-2 mt-0.5">
                        <span class="text-[9px] sm:text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded-full bg-slate-900 border border-slate-800 text-slate-300">
                            {{ $stand->stand_number }} &bull; {{ $stand->sector }}
                        </span>
                        @if($isTotem)
                            <span class="text-[9px] sm:text-[10px] font-black uppercase text-amber-400 bg-amber-950/80 px-2 py-0.5 rounded-full border border-amber-500/40">
                                MODO TÓTEM TOUCH
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Acciones Superiores: Tótem & FullScreen -->
            <div class="flex items-center gap-1.5 shrink-0">
                <a href="{{ route('expo.ruleta', ['slug' => $stand->slug, 'leadId' => $lead->id, 'totem' => $isTotem ? null : 1]) }}" 
                   title="Alternar Modo Tótem / TV Touch Vertical"
                   class="p-2 rounded-xl border {{ $isTotem ? 'bg-amber-500/20 text-amber-300 border-amber-500/40' : 'bg-slate-900 text-slate-400 border-slate-800' }} hover:text-white transition text-xs flex items-center gap-1">
                    <span class="text-xs">📺</span>
                    <span class="hidden sm:inline font-bold text-[10px]">{{ $isTotem ? 'Tótem TV' : 'Modo TV' }}</span>
                </a>
                <button onclick="toggleFullScreen()" title="Pantalla Completa (F11)"
                        class="p-2 rounded-xl bg-slate-900 border border-slate-800 text-slate-400 hover:text-white transition text-xs cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path></svg>
                </button>
            </div>
        </header>

        <!-- Barra Informativa del Participante -->
        <div class="w-full {{ $isTotem ? 'max-w-3xl' : 'max-w-xl sm:max-w-2xl' }} text-center my-1">
            <span class="text-xs sm:text-sm text-slate-400 font-medium">
                Participante: <strong class="text-emerald-400 font-bold">{{ $lead->full_name }}</strong> ({{ $lead->city }})
            </span>
        </div>

        <!-- Área Principal de la Ruleta (Grande, Moderna y Centrada) -->
        <main class="w-full {{ $isTotem ? 'max-w-3xl' : 'max-w-xl sm:max-w-2xl' }} flex flex-col items-center justify-center my-auto py-2">

            @if(session('repeat_notice') === 'already_played_event')
                <div class="w-full mb-3 p-3.5 rounded-2xl bg-indigo-950/90 border border-indigo-500/50 text-center text-xs text-indigo-200 shadow-lg">
                    <span class="text-sm font-bold text-white block mb-0.5">👋 ¡Qué bueno verte de nuevo, {{ $lead->full_name }}!</span>
                    Ya participaste de la ruleta en esta feria. Podés ver tu cupón abajo y <strong>seguís registrado para el Gran Sorteo en TV</strong>.
                </div>
            @elseif(session('repeat_notice') === 'already_played_today')
                <div class="w-full mb-3 p-3.5 rounded-2xl bg-amber-950/90 border border-amber-500/50 text-center text-xs text-amber-200 shadow-lg">
                    <span class="text-sm font-bold text-white block mb-0.5">👋 ¡Hola de nuevo, {{ $lead->full_name }}!</span>
                    Ya utilizaste tu giro de hoy. Podés volver a jugar mañana a partir de las 08:00 AM. ¡Seguís participando del Sorteo en TV!
                </div>
            @endif

            <!-- Contenedor de la Ruleta (Escalado Grande con Protagonismo Visual) -->
            <div class="relative w-[320px] h-[320px] xs:w-[360px] xs:h-[360px] sm:w-[460px] sm:h-[460px] md:w-[540px] md:h-[540px] lg:w-[580px] lg:h-[580px] flex items-center justify-center transition-all duration-300 my-2">
                
                <!-- Puntero / Clapper Metálico con Sombra 3D -->
                <div class="ruleta-pointer-wrap">
                    <div id="ruletaPointer" class="ruleta-pointer"></div>
                </div>

                <!-- Marco Exterior de la Rueda con Bisel y Resplandor -->
                <div class="w-full h-full rounded-full ruleta-rim relative overflow-hidden bg-slate-950 flex items-center justify-center">
                    <canvas id="ruletaCanvas" width="800" height="800" class="w-full h-full rounded-full"></canvas>
                </div>

                <!-- Resplandor pulsante del centro -->
                <div class="absolute w-24 h-24 sm:w-28 sm:h-28 rounded-full bg-gold-400/20 pulse-cap-glow pointer-events-none"></div>

                <!-- Botón Central Metálico Cromado con LOGO (También sirve para iniciar el giro) -->
                <div class="ruleta-center-cap w-20 h-20 sm:w-24 sm:h-24 md:w-28 md:h-28 p-2 border-4"
                     style="border-color: {{ $stand->primary_color }};"
                     onclick="ejecutarGiro()"
                     title="Toca aquí para girar">
                    @if($stand->logo_url)
                        <img src="{{ asset($stand->logo_url) }}" alt="{{ $stand->name }}" class="w-full h-full object-contain rounded-full pointer-events-none">
                    @else
                        <span class="font-black text-slate-900 text-lg sm:text-2xl pointer-events-none">{{ strtoupper(substr($stand->slug, 0, 2)) }}</span>
                    @endif
                </div>

            </div>

            <!-- Botón Principal de Giro -->
            <div class="w-full mt-4 px-4 max-w-md sm:max-w-lg">
                @if($existingSpin)
                    <button onclick="mostrarCuponExistente()"
                        class="w-full py-4 sm:py-4.5 px-6 rounded-2xl font-black text-white text-base sm:text-lg shadow-xl shadow-emerald-500/20 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 transform transition active:scale-95 flex items-center justify-center gap-2 cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                        <span>Ver mi Cupón de Premio</span>
                    </button>
                    <p class="text-center text-xs text-slate-400 mt-2">Ya giraste la ruleta en esta visita. Podés volver a ver tu pase de canje.</p>
                @else
                    <button id="btnGirar" onclick="ejecutarGiro()"
                        class="w-full py-4 sm:py-5 px-6 rounded-2xl font-black text-white text-lg sm:text-xl md:text-2xl tracking-wide uppercase shadow-2xl shadow-amber-500/30 bg-gradient-to-r from-amber-500 via-orange-500 to-amber-600 hover:brightness-110 transform transition active:scale-95 flex items-center justify-center gap-3 cursor-pointer">
                        <span class="text-2xl sm:text-3xl animate-bounce">🎯</span>
                        <span>¡TOCÁ AQUÍ PARA GIRAR!</span>
                    </button>
                    <p id="spinNotice" class="text-center text-xs sm:text-sm text-slate-400 mt-2">Toca el botón o el centro de la ruleta para probar tu suerte.</p>
                @endif
            </div>

        </main>

        <!-- Footer -->
        <footer class="w-full {{ $isTotem ? 'max-w-3xl' : 'max-w-xl sm:max-w-2xl' }} py-2.5 text-center text-xs text-slate-600 border-t border-slate-900/80">
            SotePass Expo &bull; Certificación de Ruletas y Sorteos en Tiempo Real
        </footer>

        <!-- MODAL: PASE DIGITAL SOTEPASS (CUPÓN DE PREMIO Y FLUJO DE PROMOTOR) -->
        <div id="modalPremio" class="fixed inset-0 z-50 bg-black/85 backdrop-blur-md flex items-center justify-center p-4 hidden">
            <div class="w-full max-w-sm sm:max-w-md bg-slate-900 border-2 border-emerald-500/50 rounded-3xl shadow-2xl p-6 sm:p-7 text-center relative overflow-hidden transform transition-all scale-95 duration-300" id="modalCard">
                
                <div class="absolute -top-12 -right-12 w-32 h-32 bg-emerald-500/20 rounded-full blur-3xl pointer-events-none"></div>

                <div class="w-16 h-16 sm:w-18 sm:h-18 mx-auto mb-3 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-center text-3xl sm:text-4xl shadow-inner" id="modalEmoji">
                    🎁
                </div>

                <span class="text-[11px] font-black uppercase tracking-widest text-emerald-400 bg-emerald-950/80 px-3 py-1 rounded-full border border-emerald-800">
                    Pase Digital de Canje
                </span>

                <h3 class="text-2xl sm:text-3xl font-black text-white mt-3 leading-snug" id="modalTituloPremio">
                    Premio
                </h3>

                <p class="text-xs sm:text-sm text-slate-300 mt-2 px-2" id="modalDescPremio">
                    ¡Felicidades <strong class="text-white">{{ $lead->full_name }}</strong>! Presentá este código en el mostrador para retirar tu premio.
                </p>

                <!-- Ticket / Código Cupón con estilo SotePass -->
                <div class="my-5 p-4 rounded-2xl bg-slate-950 border border-slate-800 relative shadow-inner">
                    <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">Código de Verificación Antifraude</p>
                    <div class="text-3xl sm:text-4xl font-digital font-black text-emerald-400 tracking-widest mt-1" id="modalCodigo">
                        SP-XX-0000
                    </div>
                    <div class="flex items-center justify-between text-[11px] text-slate-400 border-t border-slate-800/80 mt-3 pt-2">
                        <span>Stand: <strong>{{ $stand->stand_number }}</strong></span>
                        <span id="horaEmision" class="font-mono text-slate-300"></span>
                    </div>
                </div>

                <!-- ACCIONES Y FLUJO HÍBRIDO DE PROMOTOR (OPCIÓN C) -->
                <div class="space-y-2.5 mt-4">
                    
                    <!-- Botón Principal: Volver Inmediatamente al Registro para el Siguiente Cliente -->
                    <a href="{{ route('expo.stand', ['slug' => $stand->slug, 'totem' => $isTotem ? 1 : null]) }}" 
                       id="btnSiguienteRegistro"
                       class="w-full py-4 px-5 rounded-2xl bg-gradient-to-r from-emerald-500 via-teal-500 to-emerald-600 hover:brightness-110 active:scale-95 text-slate-950 font-black text-base sm:text-lg shadow-xl shadow-emerald-500/30 transition-all flex items-center justify-between gap-3 cursor-pointer">
                        <div class="flex items-center gap-2">
                            <span class="text-xl">🚀</span>
                            <span>¡Siguiente Visitante!</span>
                        </div>
                        <span id="registroCountDown" class="text-xs font-mono font-black bg-slate-950/25 text-slate-950 px-2.5 py-1 rounded-xl">
                            15s
                        </span>
                    </a>

                    <!-- Botones Secundarios: Pausar y Cerrar -->
                    <div class="grid grid-cols-2 gap-2">
                        <button type="button" onclick="pausarAutoRedireccion()" 
                                id="btnPausarTimer"
                                class="py-2.5 px-3 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-bold transition flex items-center justify-center gap-1.5 cursor-pointer">
                            <span>📸 Pausar (Tomar Foto)</span>
                        </button>

                        <button type="button" onclick="cerrarModal()" 
                                class="py-2.5 px-3 rounded-xl bg-slate-800/80 hover:bg-slate-700 text-slate-400 hover:text-white text-xs font-bold transition flex items-center justify-center gap-1 cursor-pointer">
                            <span>✕ Cerrar Pase</span>
                        </button>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-t border-slate-800/60 text-[10px] text-slate-500">
                    Verificado por SotePass Expo &bull; Uso único e intransferible
                </div>

            </div>
        </div>

    </div>

    <!-- Script de Ruleta con Físicas Realistas, Luces y Web Audio API -->
    <script>
        const isTotemMode = {{ $isTotem ? 'true' : 'false' }};
        const prizes = @json($prizes);
        const standSlug = '{{ $stand->slug }}';
        const leadId = {{ $lead->id }};
        const spinApiUrl = '{{ route("expo.api.spin", $stand->slug) }}';
        const nextRegisterUrl = '{{ route("expo.stand", ["slug" => $stand->slug, "totem" => $isTotem ? 1 : null]) }}';
        const canvas = document.getElementById('ruletaCanvas');
        const ctx = canvas.getContext('2d');
        const pointer = document.getElementById('ruletaPointer');
        const btnGirar = document.getElementById('btnGirar');
        const numSlices = prizes.length;
        const sliceAngle = (2 * Math.PI) / numSlices;

        let currentAngle = 0;
        let isSpinning = false;
        let autoRedirectTimer = null;
        let secondsRemaining = 15;
        let isTimerPaused = false;

        function toggleFullScreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().catch(() => {});
            } else {
                if (document.exitFullscreen) document.exitFullscreen();
            }
        }

        // Helpers de color para crear gradientes y volumen 3D
        function adjustBrightness(hex, percent) {
            let num = parseInt(hex.replace('#', ''), 16);
            if (isNaN(num)) return hex;
            let r = (num >> 16) + percent;
            let g = ((num >> 8) & 0x00FF) + percent;
            let b = (num & 0x0000FF) + percent;
            r = Math.min(255, Math.max(0, r));
            g = Math.min(255, Math.max(0, g));
            b = Math.min(255, Math.max(0, b));
            return '#' + ((1 << 24) + (r << 16) + (g << 8) + b).toString(16).slice(1);
        }

        let audioCtx = null;
        function playTickSound() {
            try {
                if (!audioCtx) audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                if (audioCtx.state === 'suspended') audioCtx.resume();
                
                const now = audioCtx.currentTime;
                const osc = audioCtx.createOscillator();
                const gain = audioCtx.createGain();
                
                // Efecto de clapper mecánico real: golpe inicial agudo y rápida amortiguación
                osc.type = 'triangle';
                osc.frequency.setValueAtTime(1150, now);
                osc.frequency.exponentialRampToValueAtTime(180, now + 0.035);
                
                gain.gain.setValueAtTime(0.35, now);
                gain.gain.exponentialRampToValueAtTime(0.005, now + 0.035);
                
                osc.connect(gain);
                gain.connect(audioCtx.destination);
                
                osc.start(now);
                osc.stop(now + 0.035);

                pointer.classList.add('tick');
                setTimeout(() => pointer.classList.remove('tick'), 65);
            } catch (e) {}
        }

        function drawWheel() {
            const centerX = canvas.width / 2;
            const centerY = canvas.height / 2;
            const radius = centerX - 18;

            ctx.clearRect(0, 0, canvas.width, canvas.height);

            const fallbackPalette = ['#4f46e5', '#16a34a', '#d97706', '#2563eb', '#9333ea', '#0891b2', '#ea580c', '#475569'];

            // 1. Dibujar cada sector con gradiente 3D
            for (let i = 0; i < numSlices; i++) {
                const startAngle = currentAngle + i * sliceAngle;
                const endAngle = startAngle + sliceAngle;
                const p = prizes[i];

                let baseColor = p.color;
                if (!baseColor || (i > 0 && baseColor === prizes[i - 1].color)) {
                    baseColor = fallbackPalette[i % fallbackPalette.length];
                }

                ctx.beginPath();
                ctx.moveTo(centerX, centerY);
                ctx.arc(centerX, centerY, radius, startAngle, endAngle);
                ctx.closePath();

                // Gradiente radial para simular volumen y curvatura de rueda real
                const sliceGrad = ctx.createRadialGradient(centerX, centerY, 50, centerX, centerY, radius);
                sliceGrad.addColorStop(0, adjustBrightness(baseColor, 35));
                sliceGrad.addColorStop(0.6, baseColor);
                sliceGrad.addColorStop(1, adjustBrightness(baseColor, -25));

                ctx.fillStyle = sliceGrad;
                ctx.fill();
                
                // Borde blanco elegante entre sectores
                ctx.lineWidth = 3.5;
                ctx.strokeStyle = '#ffffff';
                ctx.stroke();

                // 2. Texto del Premio
                ctx.save();
                ctx.translate(centerX, centerY);
                ctx.rotate(startAngle + sliceAngle / 2);
                ctx.textAlign = 'right';
                ctx.fillStyle = p.text_color || '#ffffff';
                
                let fontSize = numSlices > 10 ? 13 : (numSlices > 6 ? 15 : 17);
                ctx.font = `bold ${fontSize}px "Plus Jakarta Sans", sans-serif`;
                ctx.shadowColor = 'rgba(0,0,0,0.85)';
                ctx.shadowBlur = 8;
                
                let text = p.name;
                if (text.length > 18) text = text.substring(0, 16) + '...';
                ctx.fillText(text, radius - 35, 6);
                ctx.restore();

                // 3. Remache / Clavija Dorada en el perímetro (Pin mecánico 3D)
                const pinAngle = endAngle;
                const pinX = centerX + (radius - 10) * Math.cos(pinAngle);
                const pinY = centerY + (radius - 10) * Math.sin(pinAngle);

                // Sombra del pin
                ctx.beginPath();
                ctx.arc(pinX, pinY + 1.5, 6, 0, 2 * Math.PI);
                ctx.fillStyle = 'rgba(0,0,0,0.45)';
                ctx.fill();

                // Cuerpo metálico dorado
                const pinGrad = ctx.createRadialGradient(pinX - 1.5, pinY - 1.5, 1, pinX, pinY, 6);
                pinGrad.addColorStop(0, '#ffffff');
                pinGrad.addColorStop(0.3, '#fef08a');
                pinGrad.addColorStop(0.7, '#ca8a04');
                pinGrad.addColorStop(1, '#78350f');
                ctx.beginPath();
                ctx.arc(pinX, pinY, 5, 0, 2 * Math.PI);
                ctx.fillStyle = pinGrad;
                ctx.fill();
            }

            // 4. Aro perimetral exterior metálico brillante
            ctx.beginPath();
            ctx.arc(centerX, centerY, radius, 0, 2 * Math.PI);
            ctx.lineWidth = 8;
            ctx.strokeStyle = 'rgba(255, 255, 255, 0.4)';
            ctx.stroke();
        }

        drawWheel();

        async function ejecutarGiro() {
            if (isSpinning) return;
            isSpinning = true;
            if (btnGirar) btnGirar.disabled = true;

            try {
                const res = await fetch(spinApiUrl, {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ lead_id: leadId, totem: isTotemMode ? 1 : 0 })
                });

                const data = await res.json();

                if (!data.success) {
                    alert(data.error || 'Error al girar la ruleta.');
                    isSpinning = false;
                    if (btnGirar) btnGirar.disabled = false;
                    return;
                }

                animarGiro(data.slice_index, data);

            } catch (err) {
                alert('Hubo un problema de conexión. Intente nuevamente.');
                isSpinning = false;
                if (btnGirar) btnGirar.disabled = false;
            }
        }

        function animarGiro(targetIndex, prizeData) {
            const spins = 6; // 6 vueltas para mayor emoción y suspenso
            const pointerAngle = (3 * Math.PI) / 2;
            const targetSliceCenter = (targetIndex * sliceAngle) + (sliceAngle / 2);
            
            let finalTargetAngle = (spins * 2 * Math.PI) + (pointerAngle - targetSliceCenter);
            while (finalTargetAngle < currentAngle + (spins * 2 * Math.PI)) {
                finalTargetAngle += 2 * Math.PI;
            }

            const startAngle = currentAngle;
            const distance = finalTargetAngle - startAngle;
            const duration = 5200; // 5.2 segundos de giro
            const startTime = performance.now();
            let lastTickSlice = -1;

            function step(now) {
                const elapsed = now - startTime;
                const progress = Math.min(elapsed / duration, 1);
                
                // Desaceleración suave quintic estilo casino real
                const easeOut = 1 - Math.pow(1 - progress, 4);
                currentAngle = startAngle + (distance * easeOut);
                
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
            if (data.is_real_prize) {
                confetti({
                    particleCount: 160,
                    spread: 80,
                    origin: { y: 0.6 }
                });
            }

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

            iniciarAutoRedireccion();
        }

        function iniciarAutoRedireccion() {
            secondsRemaining = 15;
            isTimerPaused = false;
            const countEl = document.getElementById('registroCountDown');
            if (autoRedirectTimer) clearInterval(autoRedirectTimer);

            autoRedirectTimer = setInterval(() => {
                if (isTimerPaused) return;
                secondsRemaining--;
                if (countEl) countEl.innerText = secondsRemaining + 's';
                if (secondsRemaining <= 0) {
                    clearInterval(autoRedirectTimer);
                    window.location.href = nextRegisterUrl;
                }
            }, 1000);
        }

        function pausarAutoRedireccion() {
            isTimerPaused = true;
            if (autoRedirectTimer) clearInterval(autoRedirectTimer);
            const countEl = document.getElementById('registroCountDown');
            if (countEl) {
                countEl.innerText = 'Pausado';
                countEl.className = 'text-xs font-mono font-black bg-amber-400 text-slate-950 px-2.5 py-1 rounded-xl shadow-xs';
            }
            const btnPause = document.getElementById('btnPausarTimer');
            if (btnPause) {
                btnPause.innerHTML = '<span>✓ Foto Habilitada</span>';
                btnPause.className = 'py-2.5 px-3 rounded-xl bg-emerald-950 border border-emerald-500/50 text-emerald-300 text-xs font-bold transition flex items-center justify-center gap-1.5';
            }
        }

        function cerrarModal() {
            if (autoRedirectTimer) clearInterval(autoRedirectTimer);
            const modal = document.getElementById('modalPremio');
            modal.classList.add('hidden');
            window.location.reload();
        }

        function mostrarCuponExistente() {
            @if($existingSpin)
                mostrarModalPremio(
                    @json($existingSpin->prize->name),
                    @json($existingSpin->coupon_code),
                    {{ $existingSpin->prize->is_real_prize ? 'true' : 'false' }}
                );
            @endif
        }
    </script>
</x-guest-layout>
