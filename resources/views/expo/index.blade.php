<x-guest-layout>
    <x-slot:title>SotePass Expo | Captación de Leads y Ruleta para Stands</x-slot>

    <div class="min-h-screen bg-slate-950 text-slate-100 flex flex-col antialiased selection:bg-emerald-500 selection:text-white">

        <!-- Top Announcement Bar -->
        <div class="bg-gradient-to-r from-emerald-600 via-teal-600 to-emerald-700 text-white text-xs font-semibold py-2 px-4 text-center">
            🚀 Solución B2B de Soteweb: <strong>SotePass Expo</strong> — Convertí a cada visitante de tu stand en un cliente potencial con WhatsApp y datos de prospección.
        </div>

        <!-- Header / Navbar Propio de la Landing (Sin sidebar de backend) -->
        <header class="border-b border-slate-800 bg-slate-900/60 backdrop-blur-md sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                <a href="{{ route('expo.index') }}" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 rounded-xl bg-emerald-500 flex items-center justify-center font-black text-slate-950 text-xl shadow-lg shadow-emerald-500/30 group-hover:scale-105 transition-transform">
                        S
                    </div>
                    <div>
                        <span class="font-extrabold text-white text-lg tracking-tight">SotePass <span class="text-emerald-400">Expo</span></span>
                        <span class="block text-[10px] text-slate-400 -mt-1 font-semibold uppercase tracking-wider">Captación Digital de Leads para Stands</span>
                    </div>
                </a>

                <div class="flex items-center gap-2 sm:gap-3">
                    <a href="#planes" class="hidden sm:inline-flex px-3 py-1.5 text-xs font-bold text-slate-300 hover:text-white transition">
                        Planes B2B
                    </a>
                    <a href="#demo" class="hidden sm:inline-flex px-3 py-1.5 text-xs font-bold text-slate-300 hover:text-white transition">
                        Demos en Vivo
                    </a>

                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('expo.admin') }}" class="px-3.5 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-bold transition flex items-center gap-1.5 border border-slate-700">
                                <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                <span>Panel SuperAdmin</span>
                            </a>
                        @else
                            <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold transition flex items-center gap-1.5 shadow-sm">
                                <span>Mi Panel Expositor &rarr;</span>
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="px-3 py-1.5 text-xs font-bold text-slate-300 hover:text-white transition">
                            Iniciar Sesión
                        </a>
                        <a href="{{ route('register') }}" class="px-4 py-2 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black text-xs rounded-xl transition shadow-md shadow-emerald-500/25 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                            <span>Registrar Mi Empresa</span>
                        </a>
                    @endauth
                </div>
            </div>
        </header>

        <!-- Hero Section -->
        <section class="relative py-16 sm:py-24 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto text-center overflow-hidden">
            
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[300px] bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-900 border border-slate-800 text-xs font-semibold text-emerald-400 mb-6">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                Para Empresas Expositoras en Agrodinámica, Innovar y Ferias Comerciales
            </div>

            <h1 class="text-4xl sm:text-6xl font-black text-white tracking-tight max-w-4xl mx-auto leading-tight">
                Dejá de regalar merchandising a ciegas. <br class="hidden sm:inline">
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 via-teal-300 to-emerald-500">
                    Captá leads reales jugando a la Ruleta.
                </span>
            </h1>

            <p class="mt-6 text-base sm:text-lg text-slate-300 max-w-2xl mx-auto leading-relaxed">
                Cada visitante escanea el código QR de tu stand, se registra en 30 segundos, gira la <strong>Ruleta de Premios SotePass</strong> y recibís al instante su <strong>WhatsApp, ciudad y perfil de productor en tu Excel</strong>.
            </p>

            <!-- CTA Buttons -->
            <div class="mt-10 flex flex-wrap items-center justify-center gap-4">
                <a href="{{ route('register') }}" class="px-8 py-4 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black text-sm rounded-2xl shadow-xl shadow-emerald-500/25 transform transition hover:scale-105 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    <span>Registrar Mi Empresa / Stand</span>
                </a>
                <a href="#planes" class="px-7 py-4 bg-slate-900 hover:bg-slate-800 text-white font-bold text-sm rounded-2xl border border-slate-700 transition flex items-center gap-2">
                    <span>Ver Planes B2B</span>
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </a>
                <a href="#demo" class="px-6 py-4 text-slate-400 hover:text-white font-bold text-sm transition flex items-center gap-1.5">
                    <span>Probar Demo Kurosu &rarr;</span>
                </a>
            </div>

        </section>

        <!-- Hub de Demostración Interactiva -->
        <section id="demo" class="py-16 bg-slate-900/50 border-y border-slate-800 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                
                <div class="text-center mb-12">
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-white">El Ecosistema Completo en 4 Pasos</h2>
                    <p class="text-sm text-slate-400 mt-2">Hacé clic en cualquiera de los módulos para probar la solución real funcionando en Laravel + PostgreSQL.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                    <!-- Paso 1 -->
                    <div class="bg-slate-950 border border-slate-800 rounded-3xl p-6 flex flex-col justify-between hover:border-emerald-500/50 transition-all group">
                        <div>
                            <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center text-xl mb-4 group-hover:scale-110 transition">
                                📱
                            </div>
                            <span class="text-[11px] font-bold text-emerald-400 uppercase tracking-wider">Módulo 1</span>
                            <h3 class="text-lg font-bold text-white mt-1">Landing Mobile del Stand</h3>
                            <p class="text-xs text-slate-400 mt-2 leading-relaxed">
                                Página de registro con logo y colores del expositor. Pide Nombre, WhatsApp, Ciudad y Perfil productivo.
                            </p>
                        </div>
                        <div class="mt-6 pt-4 border-t border-slate-900">
                            <a href="{{ route('expo.stand', 'kurosu') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-400 hover:text-emerald-300">
                                <span>Abrir Formulario QR</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                        </div>
                    </div>

                    <!-- Paso 2 -->
                    <div class="bg-slate-950 border border-slate-800 rounded-3xl p-6 flex flex-col justify-between hover:border-amber-500/50 transition-all group">
                        <div>
                            <div class="w-12 h-12 rounded-2xl bg-amber-500/10 border border-amber-500/20 text-amber-400 flex items-center justify-center text-xl mb-4 group-hover:scale-110 transition">
                                🎡
                            </div>
                            <span class="text-[11px] font-bold text-amber-400 uppercase tracking-wider">Módulo 2</span>
                            <h3 class="text-lg font-bold text-white mt-1">Ruleta de la Suerte</h3>
                            <p class="text-xs text-slate-400 mt-2 leading-relaxed">
                                Animación física en canvas con ticker de audio y premios configurables con stock y cupón de canje antifraude.
                            </p>
                        </div>
                        <div class="mt-6 pt-4 border-t border-slate-900">
                            @php $firstKurosuLead = \App\Models\Stand::where('slug', 'kurosu')->first()?->leads()->first(); @endphp
                            <a href="{{ $firstKurosuLead ? route('expo.ruleta', ['slug' => 'kurosu', 'leadId' => $firstKurosuLead->id]) : route('expo.stand', 'kurosu') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-amber-400 hover:text-amber-300">
                                <span>Probar Giro de Ruleta</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                        </div>
                    </div>

                    <!-- Paso 3 -->
                    <div class="bg-slate-950 border border-slate-800 rounded-3xl p-6 flex flex-col justify-between hover:border-indigo-500/50 transition-all group">
                        <div>
                            <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center text-xl mb-4 group-hover:scale-110 transition">
                                📊
                            </div>
                            <span class="text-[11px] font-bold text-indigo-400 uppercase tracking-wider">Módulo 3</span>
                            <h3 class="text-lg font-bold text-white mt-1">Panel del Expositor</h3>
                            <p class="text-xs text-slate-400 mt-2 leading-relaxed">
                                Desarrollado en Livewire 3: reactivo en tiempo real con validador de cupones, buscador y exportación Excel.
                            </p>
                        </div>
                        <div class="mt-6 pt-4 border-t border-slate-900">
                            <a href="{{ route('expo.panel', 'kurosu') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-indigo-400 hover:text-indigo-300">
                                <span>Ingresar al Panel del Stand</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                        </div>
                    </div>

                    <!-- Paso 4 -->
                    <div class="bg-slate-950 border border-slate-800 rounded-3xl p-6 flex flex-col justify-between hover:border-emerald-500/50 transition-all group">
                        <div>
                            <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center text-xl mb-4 group-hover:scale-110 transition">
                                📺
                            </div>
                            <span class="text-[11px] font-bold text-emerald-400 uppercase tracking-wider">Módulo 4</span>
                            <h3 class="text-lg font-bold text-white mt-1">Sorteo en TV 4K</h3>
                            <p class="text-xs text-slate-400 mt-2 leading-relaxed">
                                Proyectá en la televisión de tu stand un sorteo digital en vivo entre todos los asistentes registrados, con láser y confeti.
                            </p>
                        </div>
                        <div class="mt-6 pt-4 border-t border-slate-900">
                            <a href="{{ route('expo.sorteo', 'kurosu') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-400 hover:text-emerald-300">
                                <span>Lanzar Pantalla de Sorteo</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                        </div>
                    </div>

                </div>

            </div>
        </section>

        <!-- Planes B2B Oficiales para Empresas Expositoras -->
        <section id="planes" class="py-20 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-xs font-bold text-emerald-400 mb-3">
                    <span>💼 Planes Oficiales B2B Soteweb</span>
                </div>
                <h2 class="text-3xl sm:text-5xl font-black text-white tracking-tight">
                    Elegí el Plan Ideal para tu Stand
                </h2>
                <p class="text-sm sm:text-base text-slate-400 mt-4 leading-relaxed">
                    Tarifa única y plana para toda la duración de la feria (Agrodinámica, Expo Santa Rita, Innovar). Sin costos ocultos.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($plans as $plan)
                    <div class="bg-slate-900/90 border {{ $plan->slug === 'stand-pro-ruleta-touch' ? 'border-emerald-500 shadow-2xl shadow-emerald-500/15 ring-2 ring-emerald-500/30' : 'border-slate-800' }} rounded-3xl p-7 sm:p-8 flex flex-col justify-between relative hover:border-slate-700 transition-all group">
                        @if($plan->badge_text)
                            <span class="absolute top-5 right-5 text-[10px] font-black uppercase tracking-wider px-3 py-1 rounded-full bg-emerald-500/15 text-emerald-400 border border-emerald-500/30">
                                {{ $plan->badge_text }}
                            </span>
                        @endif

                        <div>
                            <span class="text-[11px] font-black uppercase tracking-wider text-slate-400">Plan B2B</span>
                            <h3 class="text-xl font-black text-white mt-1">{{ $plan->name }}</h3>
                            <div class="mt-4 mb-3">
                                <div class="text-3xl sm:text-4xl font-black text-white tracking-tight">{{ $plan->formatted_price }}</div>
                                <span class="text-[11px] text-slate-400 font-medium">Tarifa plana por toda la expo</span>
                            </div>
                            <p class="text-xs text-slate-400 leading-relaxed mb-6">{{ $plan->description }}</p>

                            <div class="space-y-3 pt-6 border-t border-slate-800">
                                @foreach($plan->features as $feat)
                                    <div class="flex items-start gap-2.5 text-xs text-slate-300">
                                        <svg class="w-4 h-4 text-emerald-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        <span>{{ $feat }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="mt-8 pt-6 border-t border-slate-800">
                            <a href="{{ route('register', ['plan' => $plan->slug]) }}" 
                               class="w-full py-3.5 px-4 rounded-2xl text-center font-black text-xs uppercase tracking-wider transition-all block {{ $plan->slug === 'stand-pro-ruleta-touch' ? 'bg-emerald-500 hover:bg-emerald-400 text-slate-950 shadow-lg shadow-emerald-500/25 hover:scale-[1.02]' : 'bg-slate-800 hover:bg-slate-700 text-white hover:border-slate-600 border border-slate-700' }}">
                                Registrar Empresa con este Plan &rarr;
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <!-- Banner CTA de Conversión -->
        <section class="py-12 sm:py-16 px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto w-full">
            <div class="bg-gradient-to-r from-emerald-600 via-teal-600 to-emerald-700 rounded-3xl p-8 sm:p-12 text-center text-white relative overflow-hidden shadow-2xl">
                <div class="absolute -top-12 -right-12 w-48 h-48 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
                <h3 class="text-2xl sm:text-4xl font-black tracking-tight relative z-10">
                    ¿Tu empresa va a estar presente en la Expo?
                </h3>
                <p class="text-emerald-100 text-xs sm:text-sm max-w-xl mx-auto mt-3 leading-relaxed relative z-10">
                    Registrate ahora en 2 minutos, cargá tus premios y comenzá a captar clientes con ruleta interactiva desde el primer día de la feria.
                </p>
                <div class="mt-8 flex flex-wrap items-center justify-center gap-4 relative z-10">
                    <a href="{{ route('register') }}" class="px-8 py-4 bg-slate-950 hover:bg-slate-900 text-white font-black text-xs uppercase tracking-widest rounded-2xl shadow-xl transition-all hover:scale-105 flex items-center gap-2">
                        <span>Registrar Mi Empresa Ahora</span>
                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>
                    <a href="https://wa.me/595981123456" target="_blank" class="px-6 py-4 bg-white/15 hover:bg-white/25 text-white font-bold text-xs uppercase tracking-wider rounded-2xl transition-all border border-white/20">
                        Hablar por WhatsApp &rarr;
                    </a>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="mt-auto py-8 bg-slate-900 border-t border-slate-800 text-center text-xs text-slate-500">
            <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p>
                    <strong>SotePass Expo</strong> es una solución desarrollada por <a href="https://soteweb.com" target="_blank" class="text-emerald-400 font-bold hover:underline">Soteweb</a>.
                </p>
                <p class="text-slate-400">
                    Arquitectura: <span class="text-white font-semibold">Laravel 11 • Livewire 3 • Alpine.js • PostgreSQL ({{ config('database.connections.pgsql.database') }})</span>
                </p>
            </div>
        </footer>

    </div>
</x-guest-layout>
