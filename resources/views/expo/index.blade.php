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
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-500 flex items-center justify-center font-black text-slate-950 text-xl shadow-lg shadow-emerald-500/30">
                        S
                    </div>
                    <div>
                        <span class="font-extrabold text-white text-lg tracking-tight">SotePass <span class="text-emerald-400">Expo</span></span>
                        <span class="block text-[10px] text-slate-400 -mt-1 font-semibold uppercase tracking-wider">División Empresas & Stands (TALL Stack + PostgreSQL)</span>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('expo.admin') }}" class="px-3.5 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-bold transition flex items-center gap-1.5 border border-slate-700">
                        <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        <span>Panel SuperAdmin</span>
                    </a>
                    <a href="#demo" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs rounded-xl transition shadow-sm">
                        Probar Demo Interactiva
                    </a>
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
                <a href="{{ route('expo.stand', 'kurosu') }}" class="px-8 py-4 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black text-sm rounded-2xl shadow-xl shadow-emerald-500/20 transform transition hover:scale-105 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                    <span>Probar como Visitante del Stand (Kurosu)</span>
                </a>
                <a href="{{ route('expo.panel', 'kurosu') }}" class="px-8 py-4 bg-slate-900 hover:bg-slate-800 text-white font-bold text-sm rounded-2xl border border-slate-700 transition flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    <span>Ver Panel en Vivo (Livewire 3)</span>
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
