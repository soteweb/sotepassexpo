<x-guest-layout>
    <x-slot:title>SotePass Expo | Captación Digital de Leads y Ruleta para Stands</x-slot>

    <div class="min-h-screen bg-[#fcfbfa] text-slate-800 flex flex-col antialiased selection:bg-indigo-500 selection:text-white">

        <!-- Top Announcement Bar -->
        <div class="bg-slate-900 text-slate-200 text-xs font-semibold py-2 px-4 text-center border-b border-slate-800">
            <span class="inline-flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>Plataforma Oficial B2B de Soteweb: <strong>SotePass Expo</strong> — Captá contactos calificados con WhatsApp y prospección en tu stand.</span>
            </span>
        </div>

        <!-- Header / Navbar Propio de la Landing (Tono Claro y Nítido) -->
        <header class="border-b border-slate-200/80 bg-white/90 backdrop-blur-md sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-18 flex items-center justify-between">
                
                <!-- Brand Logo SotePass Expo -->
                <a href="{{ route('expo.index') }}" class="flex items-center gap-3 group">
                    <div class="w-9 h-9 rounded-md bg-indigo-600 flex items-center justify-center font-bold text-white text-lg shadow-xs group-hover:scale-105 transition-transform">
                        S
                    </div>
                    <div>
                        <span class="font-bold text-slate-900 text-base sm:text-lg tracking-tight">SotePass <span class="text-indigo-600">Expo</span></span>
                        <span class="block text-[10px] text-slate-400 -mt-1 font-semibold uppercase tracking-wider">Captación Digital para Stands</span>
                    </div>
                </a>

                <!-- Enlaces y Acciones -->
                <div class="flex items-center gap-3 sm:gap-4">
                    <a href="#solucion" class="hidden md:inline-flex px-3 py-1.5 text-xs font-semibold text-slate-600 hover:text-indigo-600 transition">
                        ¿Cómo Funciona?
                    </a>
                    <a href="#demo" class="hidden sm:inline-flex px-3 py-1.5 text-xs font-semibold text-slate-600 hover:text-indigo-600 transition">
                        Demos en Vivo
                    </a>
                    <a href="#planes" class="hidden sm:inline-flex px-3 py-1.5 text-xs font-semibold text-slate-600 hover:text-indigo-600 transition">
                        Planes B2B
                    </a>

                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('expo.admin') }}" class="px-3.5 py-2 rounded-md bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold transition flex items-center gap-1.5 shadow-xs">
                                <svg class="w-3.5 h-3.5 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                <span>Panel SuperAdmin</span>
                            </a>
                        @else
                            <a href="{{ route('dashboard') }}" class="px-3.5 py-2 rounded-md bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold transition flex items-center gap-1.5 shadow-xs">
                                <span>Mi Panel Expositor &rarr;</span>
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="px-3 py-2 text-xs font-semibold text-slate-700 hover:text-indigo-600 transition">
                            Iniciar Sesión
                        </a>
                        <a href="{{ route('register') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs rounded-md transition shadow-xs flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                            <span>Registrar Empresa</span>
                        </a>
                    @endauth
                </div>
            </div>
        </header>

        <!-- HERO SECTION ESTILO SOTEGYM (TONO CLARO, 2 COLUMNAS CON FOTO DE TÓTEM TOUCH VERTICAL) -->
        <section class="relative pt-12 pb-20 sm:pt-16 sm:pb-28 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto overflow-hidden">
            
            <!-- Fondos y resplandores suaves de tono claro -->
            <div class="absolute -top-24 right-10 w-96 h-96 bg-indigo-100/50 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute top-1/2 left-0 w-80 h-80 bg-amber-100/40 rounded-full blur-3xl pointer-events-none"></div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center relative z-10">
                
                <!-- COLUMNA IZQUIERDA: PROPUESTA DE VALOR Y CONVERSIÓN -->
                <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                    
                    <!-- Badge de Feria / Novedad -->
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-md bg-indigo-50 border border-indigo-100 text-xs font-semibold text-indigo-700 shadow-xs">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span>Para Empresas en Agrodinámica, Innovar y Ferias Comerciales</span>
                    </div>

                    <!-- Titular de Gran Impacto -->
                    <h1 class="text-3xl sm:text-5xl lg:text-6xl font-bold text-slate-900 tracking-tight leading-[1.12]">
                        Dejá de regalar merchandising sin retorno.<br class="hidden sm:inline">
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 via-indigo-700 to-indigo-900">
                            Captá clientes reales con Ruleta y Sorteo Final.
                        </span>
                    </h1>

                    <!-- Bajada Comercial Clara -->
                    <p class="text-base sm:text-lg text-slate-600 max-w-2xl mx-auto lg:mx-0 leading-relaxed font-normal">
                        Cada visitante escanea el código QR de tu stand o interactúa con tu pantalla vertical, completa sus datos en 20 segundos para girar la ruleta y recibís al instante su <strong>WhatsApp verificado, ciudad y perfil de productor</strong> directo en tu CRM y Excel.
                    </p>

                    <!-- Botones de Acción (CTAs B2B Rectangulares) -->
                    <div class="pt-2 flex flex-wrap items-center justify-center lg:justify-start gap-3">
                        <a href="{{ route('register') }}" 
                           class="px-6 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs sm:text-sm uppercase tracking-wider rounded-md shadow-xs transition flex items-center gap-2 cursor-pointer border border-indigo-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                            <span>Registrar Mi Empresa / Stand</span>
                        </a>

                        <a href="#demo" 
                           class="px-5 py-3.5 bg-white hover:bg-slate-50 text-slate-800 font-semibold text-xs sm:text-sm rounded-md border border-slate-200 shadow-xs transition flex items-center gap-2">
                            <span>Ver Demo en Vivo 🎡</span>
                        </a>

                        <a href="#planes" class="px-3 py-3.5 text-xs font-semibold text-slate-500 hover:text-indigo-600 transition flex items-center gap-1">
                            <span>Ver Planes B2B &darr;</span>
                        </a>
                    </div>

                    <!-- Indicadores de Confianza / Puntos Fuertes -->
                    <div class="pt-6 border-t border-slate-200/80 grid grid-cols-2 sm:grid-cols-3 gap-4 text-left">
                        <div class="flex items-center gap-2.5">
                            <div class="w-6 h-6 rounded-md bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs font-bold">
                                ✓
                            </div>
                            <span class="text-xs font-semibold text-slate-700">+95% Conversión</span>
                        </div>
                        <div class="flex items-center gap-2.5">
                            <div class="w-6 h-6 rounded-md bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs font-bold">
                                ✓
                            </div>
                            <span class="text-xs font-semibold text-slate-700">Sin papel ni planillas</span>
                        </div>
                        <div class="flex items-center gap-2.5 col-span-2 sm:col-span-1">
                            <div class="w-6 h-6 rounded-md bg-amber-50 text-amber-600 flex items-center justify-center text-xs font-bold">
                                ✓
                            </div>
                            <span class="text-xs font-semibold text-slate-700">Sorteo Final en Vivo</span>
                        </div>
                    </div>

                </div>

                <!-- COLUMNA DERECHA: FOTO REAL DE PERSONA USANDO RULETA EN TÓTEM TOUCH VERTICAL -->
                <div class="lg:col-span-5 relative flex justify-center">
                    
                    <!-- Marco Dispositivo / Escaparate Tecnológico Limpio B2B -->
                    <div class="relative w-full max-w-md bg-white p-2.5 rounded-lg shadow-sm border border-slate-200 transition duration-300">
                        
                        <!-- Imagen del Tótem con Persona Interactuando -->
                        <div class="relative rounded-md overflow-hidden aspect-[4/5] bg-slate-100 border border-slate-100">
                            <img src="{{ asset('images/hero-totem-ruleta.jpg') }}" 
                                 alt="Persona interactuando con ruleta touch vertical en stand de expo" 
                                 class="w-full h-full object-cover">

                            <!-- Sutil gradiente sobre la imagen para legibilidad -->
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-transparent to-transparent"></div>

                            <!-- Etiqueta en la foto -->
                            <div class="absolute bottom-4 left-4 right-4 text-white">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold uppercase tracking-wider bg-emerald-500 text-white">
                                        Experiencia en Stand
                                    </span>
                                    <span class="text-xs font-semibold text-slate-200">Tótem Touch Vertical</span>
                                </div>
                                <p class="text-xs text-white/90 font-medium leading-tight">
                                    El visitante toca la pantalla, gira la ruleta y sus datos quedan certificados.
                                </p>
                            </div>
                        </div>

                        <!-- Card Flotante 1: Lead Captado en Vivo -->
                        <div class="absolute -bottom-4 -left-3 sm:-left-5 bg-white/95 backdrop-blur-md rounded-lg p-3 shadow-sm border border-slate-200 flex items-center gap-2.5 text-left">
                            <div class="w-9 h-9 rounded-md bg-emerald-50 text-emerald-600 flex items-center justify-center text-base font-bold shrink-0">
                                💬
                            </div>
                            <div>
                                <span class="text-[9px] font-semibold text-slate-400 uppercase tracking-wider block">Nuevo Contacto</span>
                                <span class="text-xs font-bold text-slate-900 block">Carlos Ferreira</span>
                                <span class="text-[10px] text-emerald-700 font-semibold font-mono">+595 981 • Alto Paraná</span>
                            </div>
                        </div>

                        <!-- Card Flotante 2: Cupón de Premio Entregado -->
                        <div class="absolute -top-3 -right-2 sm:-right-4 bg-white/95 backdrop-blur-md rounded-lg p-2.5 shadow-sm border border-slate-200 flex items-center gap-2 text-left">
                            <div class="w-8 h-8 rounded-md bg-amber-50 text-amber-600 flex items-center justify-center text-sm font-bold shrink-0">
                                🎁
                            </div>
                            <div>
                                <span class="text-[9px] font-semibold text-slate-400 uppercase tracking-wider block">Premio Canjeado</span>
                                <span class="text-[11px] font-bold text-slate-900">Gorra Oficial de Marca</span>
                            </div>
                        </div>

                    </div>

                </div>

            </div>

        </section>

        <!-- HUB DE DEMOSTRACIÓN INTERACTIVA (4 MÓDULOS DEL SISTEMA) -->
        <section id="demo" class="py-20 bg-slate-50 border-y border-slate-200/80 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                
                <div class="text-center max-w-3xl mx-auto mb-14">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md bg-indigo-50 border border-indigo-100 text-xs font-semibold text-indigo-700 mb-3 shadow-xs">
                        ⚡ Demostración en Vivo
                    </span>
                    <h2 class="text-3xl sm:text-4xl font-bold text-slate-900 tracking-tight">El Ecosistema Completo en 4 Pasos</h2>
                    <p class="text-sm text-slate-500 mt-2">Hacé clic en cualquiera de los módulos para probar la solución real funcionando en vivo.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                    <!-- Paso 1: Landing Mobile del Stand -->
                    <div class="bg-white border border-slate-200 rounded-lg p-6 flex flex-col justify-between hover:border-indigo-400 hover:shadow-sm transition-all group">
                        <div>
                            <div class="w-11 h-11 rounded-md bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl mb-4 group-hover:scale-105 transition">
                                📱
                            </div>
                            <span class="text-[11px] font-semibold text-indigo-600 uppercase tracking-wider">Módulo 1</span>
                            <h3 class="text-base sm:text-lg font-bold text-slate-900 mt-1">Landing Mobile del Stand</h3>
                            <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                                Formulario ultra-rápido en celular con el logo y colores del stand. Pide Nombre, WhatsApp, Ciudad y Perfil productivo.
                            </p>
                        </div>
                        <div class="mt-6 pt-4 border-t border-slate-100">
                            <a href="{{ route('expo.stand', $demoSlug) }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-semibold text-indigo-600 hover:text-indigo-800">
                                <span>Abrir DEMO SotePass Expo</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                        </div>
                    </div>

                    <!-- Paso 2: Ruleta Interactiva -->
                    <div class="bg-white border border-slate-200 rounded-lg p-6 flex flex-col justify-between hover:border-amber-400 hover:shadow-sm transition-all group">
                        <div>
                            <div class="w-11 h-11 rounded-md bg-amber-50 text-amber-600 flex items-center justify-center text-xl mb-4 group-hover:scale-105 transition">
                                🎡
                            </div>
                            <span class="text-[11px] font-semibold text-amber-600 uppercase tracking-wider">Módulo 2</span>
                            <h3 class="text-base sm:text-lg font-bold text-slate-900 mt-1">Ruleta de la Suerte</h3>
                            <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                                Animación física con ticker de sonido, premios configurables, control de stock y cupones de canje antifraude.
                            </p>
                        </div>
                        <div class="mt-6 pt-4 border-t border-slate-100">
                            @php 
                                $demoStandModel = \App\Models\Stand::where('slug', $demoSlug)->first();
                                $demoLead = $demoStandModel?->leads()->first();
                            @endphp
                            <a href="{{ $demoLead ? route('expo.ruleta', ['slug' => $demoSlug, 'leadId' => $demoLead->id]) : route('expo.stand', $demoSlug) }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-semibold text-amber-600 hover:text-amber-800">
                                <span>Girar Ruleta DEMO</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                        </div>
                    </div>

                    <!-- Paso 3: Panel CRM del Stand -->
                    <div class="bg-white border border-slate-200 rounded-lg p-6 flex flex-col justify-between hover:border-indigo-400 hover:shadow-sm transition-all group">
                        <div>
                            <div class="w-11 h-11 rounded-md bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl mb-4 group-hover:scale-105 transition">
                                📊
                            </div>
                            <span class="text-[11px] font-semibold text-emerald-600 uppercase tracking-wider">Módulo 3</span>
                            <h3 class="text-base sm:text-lg font-bold text-slate-900 mt-1">Panel del Expositor</h3>
                            <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                                Validador de cupones en mostrador, personalizador de marca con simulador de celular, métricas en vivo y exportación Excel.
                            </p>
                        </div>
                        <div class="mt-6 pt-4 border-t border-slate-100">
                            <a href="{{ route('expo.panel', $demoSlug) }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-600 hover:text-emerald-800">
                                <span>Ingresar al Panel DEMO</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                        </div>
                    </div>

                    <!-- Paso 4: Sorteo Final en Vivo -->
                    <div class="bg-white border border-slate-200 rounded-lg p-6 flex flex-col justify-between hover:border-purple-400 hover:shadow-sm transition-all group">
                        <div>
                            <div class="w-11 h-11 rounded-md bg-purple-50 text-purple-600 flex items-center justify-center text-xl mb-4 group-hover:scale-105 transition">
                                🏆
                            </div>
                            <span class="text-[11px] font-semibold text-purple-600 uppercase tracking-wider">Módulo 4</span>
                            <h3 class="text-base sm:text-lg font-bold text-slate-900 mt-1">Sorteo Final en Pantalla</h3>
                            <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                                Proyectá en la televisión o pantalla gigante de tu stand el sorteo final con múltiples premios, láser, sonido y confeti.
                            </p>
                        </div>
                        <div class="mt-6 pt-4 border-t border-slate-100">
                            <a href="{{ route('expo.sorteo', $demoSlug) }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-semibold text-purple-600 hover:text-purple-800">
                                <span>Abrir Sorteo Final DEMO</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                        </div>
                    </div>

                </div>

            </div>
        </section>

        <!-- SECCIÓN: CÓMO FUNCIONA / BENEFICIOS -->
        <section id="solucion" class="py-20 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-xs font-semibold uppercase tracking-wider text-indigo-700 bg-indigo-50 px-3 py-1 rounded-md border border-indigo-100 shadow-xs">
                    Ventajas para tu Empresa
                </span>
                <h2 class="text-3xl sm:text-5xl font-bold text-slate-900 tracking-tight mt-3">
                    Diseñado para el Ritmo Real de la Expo
                </h2>
                <p class="text-slate-500 text-sm sm:text-base mt-3">
                    En una feria con 50.000 personas no hay tiempo de anotar a mano ni de lidiar con hojas extraviadas.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white rounded-lg p-7 border border-slate-200 shadow-xs space-y-3">
                    <div class="w-11 h-11 rounded-md bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl font-bold">
                        ⚡
                    </div>
                    <h3 class="text-base sm:text-lg font-bold text-slate-900">100% Autogestionable</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Cargas tus propios premios, subes tu logo oficial, eliges tus colores y configuras tus probabilidades directamente desde el panel.
                    </p>
                </div>

                <div class="bg-white rounded-lg p-7 border border-slate-200 shadow-xs space-y-3">
                    <div class="w-11 h-11 rounded-md bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl font-bold">
                        🔒
                    </div>
                    <h3 class="text-base sm:text-lg font-bold text-slate-900">Base de Datos Aislada y Privada</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Tus prospectos son 100% tuyos. Ningún otro expositor puede ver tus contactos ni tus métricas de captación comercial.
                    </p>
                </div>

                <div class="bg-white rounded-lg p-7 border border-slate-200 shadow-xs space-y-3">
                    <div class="w-11 h-11 rounded-md bg-amber-50 text-amber-600 flex items-center justify-center text-xl font-bold">
                        📥
                    </div>
                    <h3 class="text-base sm:text-lg font-bold text-slate-900">Exportación Instantánea a Excel</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Descarga en 1 clic el listado con números de WhatsApp listos para tus asesores de ventas y campañas de remarketing.
                    </p>
                </div>
            </div>
        </section>

        <!-- PLANES B2B OFICIALES (TONO CLARO Y TRANSPARENTE) -->
        <section id="planes" class="py-20 bg-slate-50 border-t border-slate-200/80 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-md bg-indigo-50 border border-indigo-100 text-xs font-semibold text-indigo-700 mb-3 shadow-xs">
                        <span>💼 Tarifas Planas B2B Soteweb</span>
                    </div>
                    <h2 class="text-3xl sm:text-5xl font-bold text-slate-900 tracking-tight">
                        Elegí el Plan Ideal para tu Stand
                    </h2>
                    <p class="text-sm sm:text-base text-slate-500 mt-4 leading-relaxed">
                        Tarifa plana por toda la duración de la feria (Agrodinámica, Expo Santa Rita, Innovar). Sin comisiones por contacto ni costos ocultos.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach($plans as $plan)
                        <div class="bg-white border {{ $plan->slug === 'stand-pro-ruleta-touch' ? 'border-indigo-600 shadow-sm ring-1 ring-indigo-600/30' : 'border-slate-200' }} rounded-lg p-6 sm:p-7 flex flex-col justify-between relative hover:shadow-sm transition-all">
                            @if($plan->badge_text)
                                <span class="absolute top-5 right-5 text-[10px] font-semibold uppercase tracking-wider px-2.5 py-0.5 rounded-md bg-indigo-50 text-indigo-700 border border-indigo-200">
                                    {{ $plan->badge_text }}
                                </span>
                            @endif

                            <div>
                                <span class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">Plan Oficial</span>
                                <h3 class="text-lg sm:text-xl font-bold text-slate-900 mt-1">{{ $plan->name }}</h3>
                                <div class="mt-4 mb-3">
                                    <div class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">{{ $plan->formatted_price }}</div>
                                    <span class="text-[11px] text-slate-400 font-medium">Pago único por toda la semana de feria</span>
                                </div>
                                <p class="text-xs text-slate-500 leading-relaxed mb-6">{{ $plan->description }}</p>

                                <div class="space-y-3 pt-6 border-t border-slate-100">
                                    @foreach($plan->features as $feat)
                                        <div class="flex items-start gap-2.5 text-xs text-slate-700">
                                            <svg class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                            <span>{{ str_replace('Sorteo en TV 4K', 'Sorteo Final en Pantalla', $feat) }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="mt-8 pt-6 border-t border-slate-100">
                                <a href="{{ route('register', ['plan' => $plan->slug]) }}" 
                                   class="w-full py-3 px-4 rounded-md text-center font-semibold text-xs uppercase tracking-wider transition-all block {{ $plan->slug === 'stand-pro-ruleta-touch' ? 'bg-indigo-600 hover:bg-indigo-700 text-white shadow-xs' : 'bg-slate-900 hover:bg-slate-800 text-white shadow-xs' }}">
                                    Registrar Empresa con este Plan &rarr;
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- BANNER DE CONVERSIÓN FINAL -->
        <section class="py-16 px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto w-full">
            <div class="bg-gradient-to-r from-indigo-700 via-indigo-600 to-indigo-800 rounded-lg p-8 sm:p-12 text-center text-white relative overflow-hidden shadow-sm border border-indigo-500">
                <div class="absolute -top-12 -right-12 w-48 h-48 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
                <h3 class="text-2xl sm:text-4xl font-bold tracking-tight relative z-10">
                    ¿Tu empresa va a estar presente en la Expo?
                </h3>
                <p class="text-indigo-100 text-xs sm:text-sm max-w-xl mx-auto mt-3 leading-relaxed relative z-10">
                    Creá tu cuenta en 2 minutos, configurá tus premios y comenzá a captar clientes con ruleta interactiva y sorteo final desde el primer día de la feria.
                </p>
                <div class="mt-8 flex flex-wrap items-center justify-center gap-3 relative z-10">
                    <a href="{{ route('register') }}" class="px-6 py-3.5 bg-white hover:bg-slate-50 text-slate-950 font-semibold text-xs uppercase tracking-wider rounded-md shadow-xs transition-all flex items-center gap-2 cursor-pointer border border-slate-200">
                        <span>Registrar Mi Empresa Ahora</span>
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>
                    <a href="https://wa.me/595981123456" target="_blank" class="px-5 py-3.5 bg-white/15 hover:bg-white/25 text-white font-semibold text-xs uppercase tracking-wider rounded-md transition-all border border-white/20">
                        Hablar con un Asesor &rarr;
                    </a>
                </div>
            </div>
        </section>

        <!-- FOOTER CLARO Y PROFESIONAL -->
        <footer class="mt-auto py-8 bg-white border-t border-slate-200 text-center text-xs text-slate-500">
            <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p>
                    <strong>SotePass Expo</strong> es una plataforma SaaS desarrollada por <a href="https://soteweb.com" target="_blank" class="text-indigo-600 font-bold hover:underline">Soteweb</a>.
                </p>
                <p class="text-slate-400">
                    Infraestructura: <span class="text-slate-700 font-semibold">Laravel 11 • Livewire 3 • Alpine.js • PostgreSQL</span>
                </p>
            </div>
        </footer>

    </div>
</x-guest-layout>