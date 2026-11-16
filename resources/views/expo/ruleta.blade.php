<x-guest-layout>
    <x-slot:title>Ruleta de Premios | {{ $stand->name }}</x-slot>

    <style>
        .ruleta-pointer {
            position: absolute;
            top: -12px;
            left: 50%;
            transform: translateX(-50%);
            width: 34px;
            height: 44px;
            background: linear-gradient(to bottom, #f59e0b, #d97706);
            clip-path: polygon(50% 100%, 0 0, 100% 0);
            z-index: 30;
            filter: drop-shadow(0 4px 6px rgba(0,0,0,0.5));
            transition: transform 0.1s ease;
        }
        .ruleta-pointer.tick {
            transform: translateX(-50%) rotate(-12deg);
        }
        .ruleta-center-cap {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: radial-gradient(circle, #ffffff 30%, #e2e8f0 70%, #94a3b8 100%);
            border-radius: 50%;
            box-shadow: 0 4px 15px rgba(0,0,0,0.4), inset 0 2px 4px rgba(255,255,255,0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 20;
            border: 4px solid #1e293b;
        }
        .ruleta-rim {
            border: 8px solid #1e293b;
            box-shadow: 0 0 35px rgba(34, 197, 94, 0.25), inset 0 0 15px rgba(0,0,0,0.5);
        }
    </style>

    <div class="min-h-screen flex flex-col items-center justify-between p-3 sm:p-6 antialiased">

        <!-- Top Header con Identidad Corporativa -->
        <header class="w-full {{ $isTotem ? 'max-w-2xl' : 'max-w-md sm:max-w-lg' }} text-center py-2 relative">
            <!-- Botones Superiores: Pantalla Completa & Modo Tótem -->
            <div class="absolute right-0 top-1 flex items-center gap-1.5 z-20">
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

            <!-- Identidad de la Empresa: Logo + Nombre -->
            <div class="flex items-center justify-center gap-3 mb-2">
                @if($stand->logo_url)
                    <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-white p-1.5 shadow-md border-2 border-white flex items-center justify-center overflow-hidden shrink-0">
                        <img src="{{ asset($stand->logo_url) }}" alt="{{ $stand->name }}" class="max-w-full max-h-full object-contain">
                    </div>
                @endif
                <div class="text-center sm:text-left">
                    <h1 class="{{ $isTotem ? 'text-2xl sm:text-4xl' : 'text-xl sm:text-2xl' }} font-black text-white tracking-tight">
                        {{ $stand->name }}
                    </h1>
                    <div class="flex items-center justify-center sm:justify-start gap-2 mt-0.5">
                        <span class="text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded-full bg-slate-900 border border-slate-800 text-slate-300">
                            {{ $stand->stand_number }} &bull; {{ $stand->sector }}
                        </span>
                        @if($isTotem)
                            <span class="text-[10px] font-black uppercase text-amber-400 bg-amber-950/80 px-2 py-0.5 rounded-full border border-amber-500/40">
                                MODO TÓTEM VERTICAL
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <p class="{{ $isTotem ? 'text-sm sm:text-base' : 'text-xs' }} text-slate-400">
                Participante: <strong class="text-emerald-400">{{ $lead->full_name }}</strong> ({{ $lead->city }})
            </p>
        </header>

        <!-- Área de la Ruleta -->
        <main class="w-full {{ $isTotem ? 'max-w-2xl' : 'max-w-md sm:max-w-lg' }} flex flex-col items-center justify-center my-auto py-4">

            @if(session('repeat_notice') === 'already_played_event')
                <div class="w-full mb-4 p-4 rounded-2xl bg-indigo-950/90 border border-indigo-500/50 text-center text-xs text-indigo-200 shadow-lg">
                    <span class="text-sm font-bold text-white block mb-0.5">👋 ¡Qué bueno verte de nuevo, {{ $lead->full_name }}!</span>
                    Ya participaste de la ruleta en esta feria. Podés ver tu cupón abajo y <strong>seguís registrado para el Gran Sorteo en TV</strong>.
                </div>
            @elseif(session('repeat_notice') === 'already_played_today')
                <div class="w-full mb-4 p-4 rounded-2xl bg-amber-950/90 border border-amber-500/50 text-center text-xs text-amber-200 shadow-lg">
                    <span class="text-sm font-bold text-white block mb-0.5">👋 ¡Hola de nuevo, {{ $lead->full_name }}!</span>
                    Ya utilizaste tu giro de hoy. Podés volver a jugar mañana a partir de las 08:00 AM. ¡Seguís participando del Sorteo en TV!
                </div>
            @endif

            <!-- Contenedor de la Ruleta (Escalado Dinámico para TV Touch Vertical y Móviles) -->
            <div class="relative w-[280px] h-[280px] xs:w-[320px] xs:h-[320px] {{ $isTotem ? 'sm:w-[460px] sm:h-[460px] md:w-[540px] md:h-[540px]' : 'sm:w-[380px] sm:h-[380px]' }} flex items-center justify-center transition-all duration-300">
                
                <div id="ruletaPointer" class="ruleta-pointer"></div>

                <div class="w-full h-full rounded-full ruleta-rim relative overflow-hidden bg-slate-900 flex items-center justify-center">
                    <canvas id="ruletaCanvas" width="600" height="600" class="w-full h-full rounded-full"></canvas>
                </div>

                <!-- Centro decorativo con LOGO OFICIAL DE LA EMPRESA -->
                <div class="ruleta-center-cap {{ $isTotem ? 'w-24 h-24 p-2.5' : 'w-18 h-18 sm:w-20 sm:h-20 p-2' }} bg-white shadow-2xl border-4"
                     style="border-color: {{ $stand->primary_color }};">
                    @if($stand->logo_url)
                        <img src="{{ asset($stand->logo_url) }}" alt="{{ $stand->name }}" class="w-full h-full object-contain rounded-full">
                    @else
                        <span class="font-black text-slate-900 text-lg sm:text-xl">{{ strtoupper(substr($stand->slug, 0, 2)) }}</span>
                    @endif
                </div>

            </div>

            <!-- Botón de Giro -->
            <div class="w-full mt-6 px-4">
                @if($existingSpin)
                    <button onclick="mostrarCuponExistente()"
                        class="w-full py-4 sm:py-5 px-6 rounded-2xl font-black text-white text-lg sm:text-xl shadow-xl shadow-emerald-500/20 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 transform transition active:scale-95 flex items-center justify-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                        <span>Ver mi Cupón de Premio</span>
                    </button>
                    <p class="text-center text-xs text-slate-400 mt-2">Ya giraste la ruleta en esta visita. Podés volver a ver tu pase de canje.</p>
                @else
                    <button id="btnGirar" onclick="ejecutarGiro()"
                        class="w-full py-4 sm:py-5 px-6 rounded-2xl font-black text-white text-lg sm:text-2xl tracking-wide uppercase shadow-2xl shadow-amber-500/30 bg-gradient-to-r from-amber-500 via-orange-500 to-amber-600 hover:brightness-110 transform transition active:scale-95 flex items-center justify-center gap-3">
                        <span class="text-2xl sm:text-3xl animate-bounce">🎯</span>
                        <span>¡TOCÁ AQUÍ PARA GIRAR!</span>
                    </button>
                    <p id="spinNotice" class="text-center text-xs sm:text-sm text-slate-400 mt-2">Tenés 1 oportunidad de ganar un premio instantáneo.</p>
                @endif
            </div>

        </main>

        <!-- Footer -->
        <footer class="w-full {{ $isTotem ? 'max-w-2xl' : 'max-w-md sm:max-w-lg' }} py-3 text-center text-xs text-slate-600 border-t border-slate-900">
            SotePass Expo • Certificación de Ruletas y Sorteos
        </footer>

        <!-- MODAL: PASE DIGITAL SOTEPASS (CUPÓN DE PREMIO) -->
        <div id="modalPremio" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-md flex items-center justify-center p-4 hidden">
            <div class="w-full max-w-sm bg-slate-900 border-2 border-emerald-500/50 rounded-3xl shadow-2xl p-6 text-center relative overflow-hidden transform transition-all scale-95 duration-300" id="modalCard">
                
                <div class="absolute -top-10 -right-10 w-28 h-28 bg-emerald-500/20 rounded-full blur-2xl pointer-events-none"></div>

                <div class="w-16 h-16 mx-auto mb-3 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-center text-3xl" id="modalEmoji">
                    🎁
                </div>

                <span class="text-[11px] font-bold uppercase tracking-widest text-emerald-400 bg-emerald-950/80 px-3 py-1 rounded-full border border-emerald-800">
                    Pase Digital de Canje
                </span>

                <h3 class="text-2xl font-black text-white mt-3 leading-snug" id="modalTituloPremio">
                    Premio
                </h3>

                <p class="text-xs text-slate-300 mt-2 px-2" id="modalDescPremio">
                    ¡Felicidades <strong class="text-white">{{ $lead->full_name }}</strong>! Presentá este código en el mostrador para retirar tu premio.
                </p>

                <!-- Ticket / Código Cupón con estilo SotePass -->
                <div class="my-5 p-4 rounded-2xl bg-slate-950 border border-slate-800 relative">
                    <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">Código de Verificación Antifraude</p>
                    <div class="text-3xl font-digital font-black text-emerald-400 tracking-widest mt-1" id="modalCodigo">
                        SP-XX-0000
                    </div>
                    <div class="flex items-center justify-between text-[11px] text-slate-400 border-t border-slate-800/80 mt-3 pt-2">
                        <span>Stand: <strong>{{ $stand->stand_number }}</strong></span>
                        <span id="horaEmision" class="font-mono text-slate-300"></span>
                    </div>
                </div>

                <div class="space-y-2">
                    @if($isTotem)
                        <a href="{{ route('expo.stand', ['slug' => $stand->slug, 'totem' => 1]) }}" class="w-full py-4 bg-emerald-500 hover:bg-emerald-400 text-slate-950 text-base font-black rounded-xl transition flex items-center justify-center gap-2 shadow-lg shadow-emerald-500/30">
                            <span>▶ ¡Siguiente Participante!</span>
                            <span id="totemCountDown" class="text-xs bg-slate-950/20 px-2 py-0.5 rounded-full font-mono">15s</span>
                        </a>
                    @endif
                    <button onclick="cerrarModal()" class="w-full py-3 bg-slate-800 hover:bg-slate-700 text-slate-200 text-sm font-bold rounded-xl transition">
                        Cerrar y Guardar Pantalla
                    </button>
                </div>

                <div class="mt-4 pt-3 border-t border-slate-800/60 text-[10px] text-slate-500">
                    Verificado por SotePass Expo • Uso único e intransferible
                </div>

            </div>
        </div>

    </div>

    <!-- Script de Ruleta con Físicas y Web Audio API -->
    <script>
        const isTotemMode = {{ $isTotem ? 'true' : 'false' }};
        const prizes = @json($prizes);
        const standSlug = '{{ $stand->slug }}';
        const leadId = {{ $lead->id }};
        const spinApiUrl = '{{ route("expo.api.spin", $stand->slug) }}';
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

        let audioCtx = null;
        function playTickSound() {
            try {
                if (!audioCtx) audioCtx = new (window.AudioContext || window.webkitAudioContext)();
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

                pointer.classList.add('tick');
                setTimeout(() => pointer.classList.remove('tick'), 60);
            } catch (e) {}
        }

        function drawWheel() {
            const centerX = canvas.width / 2;
            const centerY = canvas.height / 2;
            const radius = centerX - 10;

            ctx.clearRect(0, 0, canvas.width, canvas.height);

            const fallbackPalette = ['#4f46e5', '#16a34a', '#d97706', '#2563eb', '#9333ea', '#0891b2', '#ea580c', '#475569'];

            for (let i = 0; i < numSlices; i++) {
                const startAngle = currentAngle + i * sliceAngle;
                const endAngle = startAngle + sliceAngle;
                const p = prizes[i];

                // Resolver color del sector evitando que sectores contiguos tengan el mismo tono
                let sliceColor = p.color;
                if (!sliceColor || (i > 0 && sliceColor === prizes[i - 1].color)) {
                    sliceColor = fallbackPalette[i % fallbackPalette.length];
                }

                ctx.beginPath();
                ctx.moveTo(centerX, centerY);
                ctx.arc(centerX, centerY, radius, startAngle, endAngle);
                ctx.closePath();
                ctx.fillStyle = sliceColor;
                ctx.fill();
                
                // Borde blanco de 4px para máxima definición visual entre sectores
                ctx.lineWidth = 4;
                ctx.strokeStyle = '#ffffff';
                ctx.stroke();

                ctx.save();
                ctx.translate(centerX, centerY);
                ctx.rotate(startAngle + sliceAngle / 2);
                ctx.textAlign = 'right';
                ctx.fillStyle = p.text_color || '#ffffff';
                
                let fontSize = numSlices > 8 ? 13 : 15;
                ctx.font = `bold ${fontSize}px "Plus Jakarta Sans", sans-serif`;
                ctx.shadowColor = 'rgba(0,0,0,0.8)';
                ctx.shadowBlur = 6;
                
                let text = p.name;
                if (text.length > 18) text = text.substring(0, 16) + '...';
                ctx.fillText(text, radius - 28, 5);
                ctx.restore();
            }
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
                    body: JSON.stringify({ lead_id: leadId })
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
            const spins = 5;
            const pointerAngle = (3 * Math.PI) / 2;
            const targetSliceCenter = (targetIndex * sliceAngle) + (sliceAngle / 2);
            
            let finalTargetAngle = (spins * 2 * Math.PI) + (pointerAngle - targetSliceCenter);
            while (finalTargetAngle < currentAngle + (spins * 2 * Math.PI)) {
                finalTargetAngle += 2 * Math.PI;
            }

            const startAngle = currentAngle;
            const distance = finalTargetAngle - startAngle;
            const duration = 5000;
            const startTime = performance.now();
            let lastTickSlice = -1;

            function step(now) {
                const elapsed = now - startTime;
                const progress = Math.min(elapsed / duration, 1);
                
                const easeOut = 1 - Math.pow(1 - progress, 3);
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
                    particleCount: 120,
                    spread: 75,
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

            if (isTotemMode) {
                let timeLeft = 15;
                const cdEl = document.getElementById('totemCountDown');
                if (totemTimerInterval) clearInterval(totemTimerInterval);
                totemTimerInterval = setInterval(() => {
                    timeLeft--;
                    if (cdEl) cdEl.innerText = timeLeft + 's';
                    if (timeLeft <= 0) {
                        clearInterval(totemTimerInterval);
                        window.location.href = "{{ route('expo.stand', ['slug' => $stand->slug, 'totem' => 1]) }}";
                    }
                }, 1000);
            }
        }

        function cerrarModal() {
            if (totemTimerInterval) clearInterval(totemTimerInterval);
            const modal = document.getElementById('modalPremio');
            modal.classList.add('hidden');
            if (isTotemMode) {
                window.location.href = "{{ route('expo.stand', ['slug' => $stand->slug, 'totem' => 1]) }}";
            } else {
                window.location.reload();
            }
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

