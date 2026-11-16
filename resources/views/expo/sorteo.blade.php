<x-guest-layout>
    <x-slot:title>Gran Sorteo Final | {{ $stand->name }}</x-slot>

    <style>
        .glow-text { text-shadow: 0 0 25px {{ $stand->primary_color }}, 0 0 50px rgba(255, 255, 255, 0.4); }
        .glow-box { box-shadow: 0 0 45px {{ $stand->primary_color }}33, inset 0 0 20px rgba(255, 255, 255, 0.05); }
    </style>

    <div class="min-h-screen h-screen overflow-y-auto flex flex-col justify-between text-white relative selection:bg-none bg-slate-950" style="scrollbar-width: thin;">

        <!-- Fondo Corporativo Tecnológico con Acento de Marca -->
        <div class="absolute inset-0 opacity-10 pointer-events-none" 
             style="background-image: linear-gradient({{ $stand->primary_color }} 1px, transparent 1px), linear-gradient(90deg, {{ $stand->primary_color }} 1px, transparent 1px); background-size: 50px 50px;">
        </div>
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,transparent_0%,#020617_90%)] pointer-events-none"></div>

        <!-- Luces Ambientales del Color del Stand -->
        <div class="absolute -top-24 -left-24 w-96 h-96 rounded-full blur-3xl opacity-20 pointer-events-none"
             style="background-color: {{ $stand->primary_color }};"></div>
        <div class="absolute -bottom-24 -right-24 w-96 h-96 rounded-full blur-3xl opacity-20 pointer-events-none"
             style="background-color: {{ $stand->secondary_color ?? '#3b82f6' }};"></div>

        <!-- HEADER DE PANTALLA CON IDENTIDAD CORPORATIVA (COMPACTO Y RESPONSIVO) -->
        <header class="relative z-10 w-full px-4 sm:px-8 py-3 sm:py-4 flex justify-between items-center border-b border-slate-900 bg-slate-950/70 backdrop-blur-md shrink-0">
            
            <div class="flex items-center gap-3 sm:gap-4 min-w-0">
                <!-- Logo de la Empresa -->
                @if($stand->logo_url)
                    <div class="w-11 h-11 sm:w-14 sm:h-14 rounded-2xl bg-white p-1.5 shadow-xl border border-white/80 flex items-center justify-center overflow-hidden shrink-0">
                        <img src="{{ asset($stand->logo_url) }}" alt="{{ $stand->name }}" class="max-w-full max-h-full object-contain">
                    </div>
                @else
                    <div class="w-11 h-11 sm:w-14 sm:h-14 rounded-2xl flex items-center justify-center font-black text-xl text-white shadow-xl shrink-0" 
                         style="background: linear-gradient(135deg, {{ $stand->primary_color }}, {{ $stand->secondary_color ?? '#0f172a' }});">
                        {{ strtoupper(substr($stand->slug, 0, 2)) }}
                    </div>
                @endif

                <div class="min-w-0">
                    <div class="flex items-center gap-2">
                        <h2 class="text-lg sm:text-2xl font-black tracking-tight text-white uppercase truncate">{{ $stand->name }}</h2>
                        <span class="text-[9px] sm:text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded-full border shrink-0"
                              style="background-color: {{ $stand->primary_color }}22; border-color: {{ $stand->primary_color }}88; color: {{ $stand->primary_color }};">
                            {{ $stand->stand_number }}
                        </span>
                    </div>
                    <p class="text-[11px] sm:text-xs text-slate-400 mt-0.5 flex items-center gap-1.5 truncate">
                        <span>{{ $stand->sector }}</span>
                        @if($stand->tagline)
                            <span>&bull;</span>
                            <span class="text-slate-300 font-medium italic truncate">"{{ $stand->tagline }}"</span>
                        @endif
                    </p>
                </div>
            </div>

            <!-- Reloj y Control Fullscreen -->
            <div class="flex items-center gap-3 sm:gap-4 shrink-0">
                <div class="text-right hidden sm:block">
                    <div class="text-2xl sm:text-3xl font-digital font-bold tracking-wider text-slate-200" id="reloj">00:00:00</div>
                    <p class="text-[9px] text-slate-500 uppercase tracking-widest">Sorteo en Pantalla Gigante</p>
                </div>

                <button onclick="toggleFullScreen()" title="Pantalla Completa (F11)"
                        class="p-2.5 sm:p-3 bg-slate-900/80 hover:bg-slate-800 text-slate-300 hover:text-white rounded-xl sm:rounded-2xl border border-slate-800 transition cursor-pointer">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path></svg>
                </button>
            </div>
        </header>

        <!-- SELECTOR DE PREMIOS EN CASO DE HABER MÚLTIPLES (BARRA COMPACTA) -->
        <div class="relative z-10 w-full px-4 sm:px-8 py-2 bg-slate-900/70 border-b border-slate-800/80 overflow-x-auto shrink-0" style="scrollbar-width: none;">
            <div class="max-w-5xl mx-auto flex items-center justify-center gap-2" id="pillsContainer">
                @foreach($draws as $index => $d)
                    <button type="button" 
                            onclick="selectDraw({{ $d->id }})"
                            id="drawBtn-{{ $d->id }}"
                            class="draw-pill px-3 py-1.5 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 cursor-pointer border shrink-0 {{ $d->id === $activeDraw->id ? 'bg-white text-slate-900 border-white shadow-md' : 'bg-slate-800/80 text-slate-300 border-slate-700 hover:bg-slate-800' }}">
                        <span class="font-mono text-[10px] opacity-70">#{{ $index + 1 }}</span>
                        <span>{{ $d->title }}</span>
                        @if($d->status === 'completed' && $d->winner)
                            <span class="text-[9px] font-black text-emerald-400 bg-emerald-950/80 px-1.5 py-0.2 rounded border border-emerald-600">
                                ✓ {{ $d->winner->full_name }}
                            </span>
                        @endif
                    </button>
                @endforeach
            </div>
        </div>

        <!-- ÁREA CENTRAL DEL SORTEO (RESPONSIVA, CENTRADA CON MY-AUTO PARA EVITAR COLISIONES) -->
        <main class="flex-1 relative z-10 flex flex-col items-center px-4 sm:px-8 py-3 my-auto w-full max-w-4xl mx-auto">
            
            <!-- Banner del Premio Seleccionado -->
            <div class="text-center mb-3 sm:mb-5 max-w-3xl">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] sm:text-xs font-black uppercase tracking-widest mb-1.5 sm:mb-2 border shadow-xs"
                      style="background-color: {{ $stand->primary_color }}22; border-color: {{ $stand->primary_color }}66; color: {{ $stand->primary_color }};">
                    ★ <span id="drawTitleText">{{ $activeDraw->title }}</span> ★
                </span>
                <h1 class="text-2xl sm:text-4xl md:text-5xl font-black uppercase tracking-tight text-transparent bg-clip-text bg-gradient-to-r from-white via-slate-100 to-slate-300 drop-shadow-sm line-clamp-2" id="drawPrizeDesc">
                    {{ $activeDraw->prize_description }}
                </h1>
            </div>

            <!-- Tambor / Display del Ganador -->
            <div class="w-full bg-slate-900/90 backdrop-blur-xl border border-slate-800 rounded-3xl sm:rounded-[2.5rem] p-5 sm:p-8 text-center glow-box relative overflow-hidden">
                
                <div id="laserLine" class="absolute left-0 right-0 h-1 hidden top-1/2 -translate-y-1/2 z-20"
                     style="background-color: {{ $stand->primary_color }}; box-shadow: 0 0 25px {{ $stand->primary_color }};"></div>
                
                <p class="text-slate-400 text-[11px] sm:text-xs uppercase tracking-widest mb-1" id="estadoTexto">
                    Padrón Certificado: <span id="contadorLeads" class="font-bold text-white">{{ $totalParticipants }}</span> visitantes registrados
                </p>
                
                <div class="h-16 sm:h-24 md:h-28 flex items-center justify-center">
                    <div id="nombreDisplay" class="font-digital text-2xl sm:text-4xl md:text-6xl font-bold text-slate-200 tracking-wider transition-all truncate px-2">
                        PRESIONE INICIAR
                    </div>
                </div>
                
                <div id="datosGanador" class="mt-2 sm:mt-3 opacity-0 transition-opacity duration-1000 flex flex-wrap justify-center gap-2 sm:gap-3">
                    <span class="bg-slate-800/90 border border-slate-700 text-slate-200 px-3 py-1 sm:px-4 sm:py-1.5 rounded-xl text-xs sm:text-sm font-bold" id="ciudadDisplay">
                        CIUDAD
                    </span>
                    <span class="bg-slate-800/90 border border-slate-700 text-slate-200 px-3 py-1 sm:px-4 sm:py-1.5 rounded-xl text-xs sm:text-sm font-bold" id="perfilDisplay">
                        PERFIL
                    </span>
                    <span class="bg-emerald-950/80 border border-emerald-500 text-emerald-300 px-3 py-1 sm:px-4 sm:py-1.5 rounded-xl text-xs sm:text-sm font-mono font-bold" id="telefonoDisplay">
                        098X *** XXX
                    </span>
                </div>

            </div>

            <!-- Botones de Acción -->
            <div class="mt-4 sm:mt-6 flex items-center justify-center gap-3">
                <button id="btnSortear" onclick="iniciarSorteo()"
                        class="text-white font-black text-sm sm:text-base md:text-lg px-6 sm:px-10 py-3 sm:py-3.5 rounded-2xl transition shadow-xl uppercase tracking-wider flex items-center gap-2.5 cursor-pointer hover:brightness-110 active:scale-95"
                        style="background: linear-gradient(135deg, {{ $stand->primary_color }}, {{ $stand->secondary_color ?? '#1e293b' }}); box-shadow: 0 8px 25px -4px {{ $stand->primary_color }}88;">
                    <svg class="w-5 h-5 animate-spin-slow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    <span>Iniciar Sorteo en Vivo</span>
                </button>
            </div>

        </main>

        <!-- FOOTER CORPORATIVO (COMPACTO) -->
        <footer class="relative z-10 w-full px-4 sm:px-8 py-2 sm:py-3 flex justify-between items-center border-t border-slate-900 bg-slate-950/70 backdrop-blur-md text-[11px] text-slate-500 shrink-0">
            <div>
                <span class="uppercase tracking-wider font-bold text-slate-400">Certificación Tecnológica:</span>
                <span class="font-extrabold text-white ml-1">SotePass Expo</span>
            </div>
            <div class="text-right">
                Padrón en tiempo real &bull; Desarrollado por <strong>Soteweb</strong>
            </div>
        </footer>

    </div>

    <script>
        const standSlug = '{{ $stand->slug }}';
        const sorteoDataUrl = '{{ route("expo.sorteo.data", $stand->slug) }}';
        const sorteoExecUrl = '{{ route("expo.sorteo.execute", $stand->slug) }}';
        
        let allDraws = @json($draws);
        let selectedDrawId = {{ $activeDraw->id }};
        let participants = [];
        let isRunning = false;

        setInterval(() => {
            const relojEl = document.getElementById('reloj');
            if (relojEl) relojEl.innerText = new Date().toLocaleTimeString('es-PY', { hour12: false });
        }, 1000);

        let audioCtx = null;
        function playBeep(freq = 600, duration = 0.05) {
            try {
                if (!audioCtx) audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                const osc = audioCtx.createOscillator();
                const gain = audioCtx.createGain();
                osc.type = 'triangle';
                osc.frequency.setValueAtTime(freq, audioCtx.currentTime);
                gain.gain.setValueAtTime(0.15, audioCtx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + duration);
                osc.connect(gain);
                gain.connect(audioCtx.destination);
                osc.start();
                osc.stop(audioCtx.currentTime + duration);
            } catch (e) {}
        }

        function playVictoryChord() {
            try {
                if (!audioCtx) audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                [523.25, 659.25, 783.99, 1046.50].forEach((freq, idx) => {
                    setTimeout(() => {
                        const osc = audioCtx.createOscillator();
                        const gain = audioCtx.createGain();
                        osc.type = 'sine';
                        osc.frequency.setValueAtTime(freq, audioCtx.currentTime);
                        gain.gain.setValueAtTime(0.2, audioCtx.currentTime);
                        gain.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 1.2);
                        osc.connect(gain);
                        gain.connect(audioCtx.destination);
                        osc.start();
                        osc.stop(audioCtx.currentTime + 1.2);
                    }, idx * 100);
                });
            } catch (e) {}
        }

        function selectDraw(drawId) {
            if (isRunning) return;
            selectedDrawId = drawId;
            const target = allDraws.find(d => d.id === drawId);
            if (!target) return;

            document.getElementById('drawTitleText').innerText = target.title;
            document.getElementById('drawPrizeDesc').innerText = target.prize_description;

            // Actualizar estilo de pills
            document.querySelectorAll('.draw-pill').forEach(btn => {
                btn.className = 'draw-pill px-3 py-1.5 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 cursor-pointer border shrink-0 bg-slate-800/80 text-slate-300 border-slate-700 hover:bg-slate-800';
            });
            const activeBtn = document.getElementById('drawBtn-' + drawId);
            if (activeBtn) {
                activeBtn.className = 'draw-pill px-3 py-1.5 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 cursor-pointer border shrink-0 bg-white text-slate-900 border-white shadow-md';
            }

            // Si ya fue sorteado, mostrar directamente el ganador
            if (target.status === 'completed' && target.winner) {
                mostrarGanadorExistente(target.winner);
            } else {
                document.getElementById('nombreDisplay').innerText = 'PRESIONE INICIAR';
                document.getElementById('nombreDisplay').classList.remove('glow-text', 'text-emerald-400');
                document.getElementById('nombreDisplay').classList.add('text-slate-200');
                document.getElementById('datosGanador').classList.add('opacity-0');
                document.getElementById('estadoTexto').innerText = 'ESPERANDO INICIO DEL SORTEO';
                document.getElementById('btnSortear').innerHTML = `<span>Iniciar Sorteo en Vivo</span>`;
            }
        }

        function mostrarGanadorExistente(winner) {
            const display = document.getElementById('nombreDisplay');
            display.innerText = winner.full_name.toUpperCase();
            display.classList.remove('text-slate-200');
            display.classList.add('text-emerald-400', 'glow-text');

            document.getElementById('ciudadDisplay').innerText = (winner.city || 'PARAGUAY').toUpperCase();
            document.getElementById('perfilDisplay').innerText = (winner.profile || 'ASISTENTE').toUpperCase();
            
            const p = winner.phone || '';
            const masked = p.length > 6 ? p.substring(0, 4) + ' *** ' + p.substring(p.length - 3) : p;
            document.getElementById('telefonoDisplay').innerText = masked;

            document.getElementById('datosGanador').classList.remove('opacity-0');
            document.getElementById('estadoTexto').innerText = '✓ PREMIO YA SORTEADO Y CERTIFICADO';
            document.getElementById('btnSortear').innerHTML = `<span>Volver a Sortear este Premio</span>`;
        }

        async function loadDrawData() {
            try {
                const res = await fetch(sorteoDataUrl);
                const data = await res.json();
                participants = data.participants || [];
                allDraws = data.draws || allDraws;
                document.getElementById('contadorLeads').innerText = data.total_participants ?? participants.length;
            } catch (e) {}
        }

        async function iniciarSorteo() {
            if (isRunning) return;
            if (participants.length === 0) {
                alert('No hay participantes registrados en este stand todavía.');
                return;
            }

            let enteredPin = null;
            @if(!Auth::check())
                enteredPin = prompt('Por favor, ingresa el PIN de mostrador del stand para autorizar el sorteo oficial:');
                if (!enteredPin) return;
            @endif

            isRunning = true;
            document.getElementById('btnSortear').disabled = true;
            document.getElementById('laserLine').classList.remove('hidden');
            document.getElementById('datosGanador').classList.add('opacity-0');
            document.getElementById('estadoTexto').innerText = 'BUSCANDO GANADOR EN TIEMPO REAL...';

            const display = document.getElementById('nombreDisplay');
            display.classList.remove('text-emerald-400', 'glow-text');
            display.classList.add('text-slate-200');

            let officialWinner = null;
            try {
                const res = await fetch(sorteoExecUrl, {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ 
                        pin: enteredPin,
                        draw_id: selectedDrawId
                    })
                });

                if (res.status === 401) {
                    alert('PIN de mostrador incorrecto. Sorteo no autorizado.');
                    isRunning = false;
                    document.getElementById('btnSortear').disabled = false;
                    document.getElementById('laserLine').classList.add('hidden');
                    document.getElementById('estadoTexto').innerText = 'ESPERANDO INICIO';
                    return;
                }

                const data = await res.json();
                if (data.success && data.winner) {
                    officialWinner = data.winner;
                    const d = allDraws.find(item => item.id === selectedDrawId);
                    if (d) {
                        d.status = 'completed';
                        d.winner = officialWinner;
                    }
                } else {
                    officialWinner = participants[Math.floor(Math.random() * participants.length)];
                }
            } catch (err) {
                officialWinner = participants[Math.floor(Math.random() * participants.length)];
            }

            let speed = 40;
            let counter = 0;
            const totalTicks = 70;

            function cycle() {
                const randParticipant = participants[Math.floor(Math.random() * participants.length)];
                display.innerText = randParticipant.full_name.toUpperCase();
                playBeep(350 + (counter * 8), 0.03);
                counter++;

                if (counter < totalTicks) {
                    if (counter > totalTicks - 20) speed += 15;
                    setTimeout(cycle, speed);
                } else {
                    revelarGanador(officialWinner);
                }
            }

            cycle();
        }

        function revelarGanador(winner) {
            document.getElementById('laserLine').classList.add('hidden');
            const display = document.getElementById('nombreDisplay');
            
            display.innerText = winner.full_name.toUpperCase();
            display.classList.remove('text-slate-200');
            display.classList.add('text-emerald-400', 'glow-text');

            document.getElementById('ciudadDisplay').innerText = (winner.city || 'PARAGUAY').toUpperCase();
            document.getElementById('perfilDisplay').innerText = (winner.profile || 'ASISTENTE').toUpperCase();
            
            const p = winner.phone || '';
            const masked = p.length > 6 ? p.substring(0, 4) + ' *** ' + p.substring(p.length - 3) : p;
            document.getElementById('telefonoDisplay').innerText = masked;

            document.getElementById('datosGanador').classList.remove('opacity-0');
            document.getElementById('estadoTexto').innerText = '¡GANADOR OFICIAL CERTIFICADO!';

            const activeBtn = document.getElementById('drawBtn-' + selectedDrawId);
            if (activeBtn && !activeBtn.innerHTML.includes('✓')) {
                activeBtn.innerHTML += ` <span class="text-[9px] font-black text-emerald-400 bg-emerald-950/80 px-1.5 py-0.2 rounded border border-emerald-600">✓ ${winner.full_name}</span>`;
            }

            confetti({
                particleCount: 250,
                spread: 120,
                origin: { y: 0.6 }
            });

            playVictoryChord();

            isRunning = false;
            document.getElementById('btnSortear').disabled = false;
            document.getElementById('btnSortear').innerHTML = `<span>Sorteo Completado (Volver a Sortear)</span>`;
        }

        function toggleFullScreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().catch(() => {});
            } else {
                if (document.exitFullscreen) document.exitFullscreen();
            }
        }

        loadDrawData();
    </script>
</x-guest-layout>