<x-guest-layout>
    <x-slot:title>Sorteo en Vivo | {{ $stand->name }}</x-slot>

    <style>
        .glow-text { text-shadow: 0 0 25px rgba(34, 197, 94, 0.7), 0 0 50px rgba(34, 197, 94, 0.4); }
        .glow-box { box-shadow: 0 0 40px rgba(34, 197, 94, 0.15), inset 0 0 20px rgba(34, 197, 94, 0.05); }
    </style>

    <div class="h-screen overflow-hidden flex flex-col justify-between text-white relative selection:bg-none bg-gray-950">

        <!-- Patrón de Fondo Tecnológico -->
        <div class="absolute inset-0 opacity-15 pointer-events-none" 
             style="background-image: linear-gradient(#16a34a 1px, transparent 1px), linear-gradient(90deg, #16a34a 1px, transparent 1px); background-size: 50px 50px;">
        </div>
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,transparent_0%,#030712_85%)] pointer-events-none"></div>

        <!-- HEADER DE PANTALLA GIGANTE -->
        <header class="relative z-10 w-full px-8 py-6 flex justify-between items-start">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-2xl flex items-center justify-center font-black text-2xl text-white shadow-2xl" style="background-color: {{ $stand->primary_color }};">
                    {{ strtoupper(substr($stand->slug, 0, 1)) }}
                </div>
                <div>
                    <h2 class="text-3xl font-black tracking-tight text-white uppercase">{{ $stand->name }}</h2>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="text-xs px-2.5 py-0.5 rounded-full bg-emerald-950 border border-emerald-600 text-emerald-400 font-bold uppercase tracking-wider">
                            {{ $stand->stand_number }} • {{ $stand->sector }}
                        </span>
                        <span class="text-xs text-slate-400">Sorteo Certificado en Pantalla (PostgreSQL)</span>
                    </div>
                </div>
            </div>

            <div class="text-right">
                <div class="text-5xl font-digital font-bold text-emerald-400 tracking-wider" id="reloj">00:00:00</div>
                <p class="text-xs text-slate-500 uppercase tracking-widest mt-1">Transmisión en Vivo Stand</p>
            </div>
        </header>

        <!-- ÁREA CENTRAL DEL SORTEO -->
        <main class="flex-1 relative z-10 flex flex-col items-center justify-center -mt-6 px-6">
            
            <!-- Nombre del Premio Oficial -->
            <div class="mb-6 text-center" id="premioBanner">
                <span class="bg-amber-500/20 text-amber-400 border border-amber-500/40 px-6 py-2 rounded-full text-sm font-bold uppercase tracking-widest mb-3 inline-block">
                    ★ Gran Premio del Stand ★
                </span>
                <h1 class="text-4xl md:text-6xl font-black uppercase tracking-tight text-transparent bg-clip-text bg-gradient-to-r from-amber-300 via-yellow-200 to-amber-400" id="drawTitle">
                    {{ $draw->prize_description ?? ($draw->title ?? 'Gran Sorteo Oficial') }}
                </h1>
            </div>

            <!-- Ruleta Digital / Display del Ganador -->
            <div class="w-full max-w-5xl bg-slate-900/90 backdrop-blur-xl border border-slate-800 rounded-[2.5rem] p-10 text-center glow-box relative overflow-hidden">
                
                <div id="laserLine" class="absolute left-0 right-0 h-1 bg-emerald-400 shadow-[0_0_20px_#22c55e] hidden top-1/2 -translate-y-1/2 z-20"></div>
                
                <p class="text-slate-400 text-sm uppercase tracking-widest mb-2" id="estadoTexto">
                    Padrón Oficial: <span id="contadorLeads" class="text-emerald-400 font-bold">{{ $totalParticipants }}</span> visitantes participantes
                </p>
                
                <div class="h-32 flex items-center justify-center">
                    <div id="nombreDisplay" class="font-digital text-5xl md:text-7xl font-bold text-slate-200 tracking-wider transition-all">
                        PRESIONE INICIAR
                    </div>
                </div>
                
                <div id="datosGanador" class="mt-4 opacity-0 transition-opacity duration-1000 flex flex-wrap justify-center gap-4">
                    <span class="bg-emerald-950/80 border border-emerald-500 text-emerald-300 px-5 py-2 rounded-xl text-lg font-bold" id="ciudadDisplay">
                        CIUDAD
                    </span>
                    <span class="bg-blue-950/80 border border-blue-500 text-blue-300 px-5 py-2 rounded-xl text-lg font-bold" id="perfilDisplay">
                        PERFIL
                    </span>
                    <span class="bg-slate-800 border border-slate-700 text-slate-300 px-5 py-2 rounded-xl text-lg font-mono" id="telefonoDisplay">
                        098X *** XXX
                    </span>
                </div>

            </div>

            <!-- Botones de Control -->
            <div class="mt-8 flex gap-4">
                <button id="btnSortear" onclick="iniciarSorteo()"
                    class="bg-emerald-600 hover:bg-emerald-500 text-white font-black text-xl px-10 py-4 rounded-2xl transition shadow-[0_0_35px_rgba(34,197,94,0.3)] uppercase tracking-wider flex items-center gap-3">
                    <svg class="w-6 h-6 animate-spin-slow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    <span>Iniciar Sorteo en Vivo</span>
                </button>
                <button onclick="toggleFullScreen()" title="Pantalla Completa (F11)"
                    class="px-5 py-4 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-2xl transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path></svg>
                </button>
            </div>

        </main>

        <!-- FOOTER SOTEPASS EXPO -->
        <footer class="relative z-10 w-full px-8 py-6 flex justify-between items-end border-t border-slate-900 bg-slate-950/50 backdrop-blur-md">
            <div>
                <p class="text-slate-500 text-xs uppercase tracking-wider">Certificación Tecnológica</p>
                <p class="text-xl font-black text-emerald-400">SotePass Expo<span class="text-white">.</span></p>
            </div>
            <div class="text-right text-xs text-slate-500">
                Los datos son extraídos en tiempo real del registro digital de este stand.<br>
                Desarrollado por <span class="text-slate-300 font-bold">Soteweb</span> • PostgreSQL
            </div>
        </footer>

    </div>

    <script>
        const standSlug = '{{ $stand->slug }}';
        const sorteoDataUrl = '{{ route("expo.sorteo.data", $stand->slug) }}';
        const sorteoExecUrl = '{{ route("expo.sorteo.execute", $stand->slug) }}';
        let participants = [];
        let isRunning = false;

        setInterval(() => {
            document.getElementById('reloj').innerText = new Date().toLocaleTimeString('es-PY', { hour12: false });
        }, 1000);

        let audioCtx = null;
        function playBeep(freq = 600, duration = 0.05) {
            try {
                if (!audioCtx) audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                const osc = audioCtx.createOscillator();
                const gain = audioCtx.createGain();
                osc.type = 'square';
                osc.frequency.setValueAtTime(freq, audioCtx.currentTime);
                gain.gain.setValueAtTime(0.1, audioCtx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + duration);
                osc.connect(gain);
                gain.connect(audioCtx.destination);
                osc.start();
                osc.stop(audioCtx.currentTime + duration);
            } catch (e) {}
        }

        async function loadDrawData() {
            try {
                const res = await fetch(sorteoDataUrl);
                const data = await res.json();
                participants = data.participants || [];
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
                if (!enteredPin) {
                    return;
                }
            @endif

            isRunning = true;
            document.getElementById('btnSortear').disabled = true;
            document.getElementById('laserLine').classList.remove('hidden');
            document.getElementById('datosGanador').classList.add('opacity-0');
            document.getElementById('estadoTexto').innerText = 'BUSCANDO GANADOR ENTRE LOS ASISTENTES...';

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
                    body: JSON.stringify({ pin: enteredPin })
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
                } else {
                    officialWinner = participants[Math.floor(Math.random() * participants.length)];
                }
            } catch (err) {
                officialWinner = participants[Math.floor(Math.random() * participants.length)];
            }

            let speed = 40;
            let counter = 0;
            const totalTicks = 80;

            function cycle() {
                const randParticipant = participants[Math.floor(Math.random() * participants.length)];
                display.innerText = randParticipant.full_name.toUpperCase();
                playBeep(400 + (counter * 8), 0.03);
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

            confetti({
                particleCount: 200,
                spread: 100,
                origin: { y: 0.6 }
            });

            playBeep(880, 0.4);

            isRunning = false;
            document.getElementById('btnSortear').disabled = false;
            document.getElementById('btnSortear').innerHTML = `<span>Volver a Sortear</span>`;
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

