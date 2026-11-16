<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#f8f7f5]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'SotePass Expo | Captación y Ruleta B2B para Stands' }}</title>

    <!-- Google Fonts: Plus Jakarta Sans & Share Tech Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&family=Share+Tech+Mono&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS CDN con la paleta oficial y estilos de SotePass -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'Inter', 'system-ui', 'sans-serif'],
                        mono: ['"Share Tech Mono"', 'monospace'],
                    },
                    colors: {
                        gold: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            200: '#c7d2fe',
                            300: '#a5b4fc',
                            400: '#818cf8',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            800: '#3730a3',
                            900: '#312e81',
                            950: '#1e1b4b',
                        }
                    },
                    boxShadow: {
                        '2xs': '0 1px 2px 0 rgba(0, 0, 0, 0.05)',
                        'xs': '0 1px 3px 0 rgba(0, 0, 0, 0.07), 0 1px 2px 0 rgba(0, 0, 0, 0.04)',
                    }
                }
            }
        }
    </script>

    <!-- Canvas Confetti -->
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>

    <!-- Alpine.js (Garantiza reactividad instantánea del sidebar y menús desplegables) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .font-digital {
            font-family: 'Share Tech Mono', monospace;
        }
        [x-cloak] { display: none !important; }
    </style>

    @livewireStyles
</head>
<body class="h-full font-sans antialiased text-slate-800 bg-[#f8f7f5] overflow-x-hidden" 
      x-data="{ 
          sidebarOpen: false, 
          sidebarCollapsed: localStorage.getItem('sidebar_collapsed') === 'true',
          toggleSidebar() {
              if (window.innerWidth >= 1024) {
                  this.sidebarCollapsed = !this.sidebarCollapsed;
                  localStorage.setItem('sidebar_collapsed', this.sidebarCollapsed);
              } else {
                  this.sidebarOpen = !this.sidebarOpen;
              }
          }
      }">

    <div class="min-h-screen flex">
        <!-- Sidebar Base44 Style Rail / Full -->
        @include('layouts.navigation', ['stand' => $stand ?? null])

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0 transition-all duration-300 ease-in-out">
            
            <!-- Topbar Header -->
            <header class="h-16 bg-white border-b border-slate-200/80 sticky top-0 z-30 flex items-center justify-between px-3 sm:px-6 lg:px-8 shadow-xs">
                <div class="flex items-center gap-2 sm:gap-3 min-w-0">
                    <!-- Mobile Hamburger Button -->
                    <button @click="sidebarOpen = true" 
                            type="button" 
                            class="lg:hidden p-2 rounded-md text-slate-500 hover:text-slate-800 hover:bg-slate-100 focus:outline-none transition-colors cursor-pointer shrink-0"
                            aria-label="Abrir menú lateral">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <!-- Desktop Toggle Button -->
                    <button @click="toggleSidebar()" 
                            type="button" 
                            class="hidden lg:flex items-center justify-center p-2 rounded-md text-slate-500 hover:text-slate-800 hover:bg-slate-100 focus:outline-none transition-colors cursor-pointer shrink-0"
                            :title="sidebarCollapsed ? 'Expandir barra lateral' : 'Contraer barra lateral'"
                            aria-label="Alternar menú lateral">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <rect x="3" y="3" width="18" height="18" rx="2" stroke-width="2"/>
                            <path d="M9 3v18" stroke-width="2"/>
                        </svg>
                    </button>

                    <!-- Context Title / Breadcrumb -->
                    <div class="flex items-center gap-1.5 sm:gap-2 text-xs font-semibold text-slate-500 min-w-0">
                        <a href="{{ route('expo.admin') }}" class="hover:text-slate-900 transition-colors flex items-center gap-1 shrink-0">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            <span class="hidden xs:inline">SotePass Expo</span>
                        </a>
                        <span class="text-slate-300">/</span>
                        <span class="text-slate-800 font-bold truncate max-w-[150px] sm:max-w-[260px] md:max-w-none">
                            @if(request()->routeIs('expo.admin'))
                                Panel Global de Expositores
                            @elseif(request()->routeIs('expo.panel'))
                                {{ $stand->name ?? 'Panel del Stand' }}
                            @elseif(request()->routeIs('expo.index'))
                                Portal Hub
                            @else
                                Dashboard
                            @endif
                        </span>
                    </div>
                </div>

                <!-- Topbar Right Quick Actions -->
                <div class="flex items-center gap-2 sm:gap-2.5">
                    @if(isset($stand))
                        <!-- Botones rápidos del Stand -->
                        <a href="{{ route('expo.stand', $stand->slug) }}" target="_blank"
                            class="hidden md:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md border border-slate-200 text-xs font-semibold text-slate-600 hover:text-slate-900 hover:bg-slate-50 transition-colors"
                            title="Abrir QR / Landing de Registro">
                            <span>📱 Landing QR</span>
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </a>

                        <a href="{{ route('expo.sorteo', $stand->slug) }}" target="_blank"
                            class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md bg-amber-50 border border-amber-200 text-xs font-semibold text-amber-800 hover:bg-amber-100 transition-colors"
                            title="Abrir Sorteo en TV 4K">
                            <span>📺 Sorteo TV</span>
                        </a>
                    @else
                        <a href="{{ route('expo.index') }}" target="_blank"
                            class="hidden md:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md border border-slate-200 text-xs font-semibold text-slate-600 hover:text-slate-900 hover:bg-slate-50 transition-colors"
                            title="Abrir Portal Hub">
                            <span>Ver Portal</span>
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </a>
                    @endif

                    <!-- User Profile Dropdown (Estilo SotePass Oficial) -->
                    @auth
                        <div class="relative" x-data="{ userMenuOpen: false }">
                            <button @click="userMenuOpen = !userMenuOpen" 
                                    type="button" 
                                    class="flex items-center gap-2 p-1 rounded-md hover:bg-slate-100 transition-colors focus:outline-none cursor-pointer"
                                    aria-label="Menú de usuario"
                                    :aria-expanded="userMenuOpen">
                                
                                <div class="w-8 h-8 rounded-md bg-slate-900 text-white flex items-center justify-center font-bold text-xs shadow-xs shrink-0">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                </div>

                                <div class="hidden sm:block text-left pr-1">
                                    <div class="text-xs font-bold text-slate-900 leading-tight truncate max-w-[120px]">
                                        {{ auth()->user()->name }}
                                    </div>
                                    <div class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider leading-tight">
                                        {{ auth()->user()->isAdmin() ? 'SuperAdmin' : (isset($stand) ? $stand->stand_number : 'Expositor') }}
                                    </div>
                                </div>

                                <svg class="w-3.5 h-3.5 text-slate-400 transition-transform duration-200" :class="{ 'rotate-180': userMenuOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="userMenuOpen" 
                                 x-cloak
                                 @click.away="userMenuOpen = false"
                                 x-transition:enter="transition ease-out duration-150"
                                 x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave="transition ease-in duration-100"
                                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                                 class="absolute right-0 mt-2 w-64 bg-white rounded-lg p-2 shadow-xl border border-slate-200 z-50">
                                
                                <div class="px-3 py-2.5 border-b border-slate-100 mb-1">
                                    <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Conectado como</p>
                                    <p class="text-xs font-bold text-slate-900 truncate mt-0.5">{{ auth()->user()->email }}</p>
                                    <div class="mt-1 flex items-center gap-1.5">
                                        <span class="inline-block px-2 py-0.5 rounded-md text-[9px] font-semibold uppercase tracking-wider {{ auth()->user()->isAdmin() ? 'bg-slate-100 text-slate-800 border border-slate-200' : 'bg-emerald-50 text-emerald-800 border border-emerald-200' }}">
                                            {{ auth()->user()->isAdmin() ? 'Administrador Soteweb' : (auth()->user()->company_name ?: 'Expositor') }}
                                        </span>
                                    </div>
                                </div>

                                <div class="space-y-0.5">
                                    @if(auth()->user()->isAdmin())
                                        <a href="{{ route('expo.admin') }}" 
                                           class="flex items-center gap-2.5 px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 hover:text-slate-900 rounded-md transition-colors">
                                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                            <span>Panel Global</span>
                                        </a>
                                    @elseif(auth()->user()->stand)
                                        <a href="{{ route('expo.panel', auth()->user()->stand->slug) }}" 
                                           class="flex items-center gap-2.5 px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 hover:text-slate-900 rounded-md transition-colors">
                                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                            <span>Mi Stand ({{ auth()->user()->stand->name }})</span>
                                        </a>
                                    @endif

                                    <a href="{{ route('expo.index') }}" target="_blank"
                                       class="flex items-center gap-2.5 px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 hover:text-slate-900 rounded-md transition-colors">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                        <span>Ver Portal Público</span>
                                    </a>
                                </div>

                                <div class="pt-1.5 mt-1 border-t border-slate-100">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" 
                                                class="w-full flex items-center gap-2.5 px-3 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50 rounded-md transition-colors cursor-pointer">
                                            <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                            <span>Cerrar Sesión</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 bg-slate-900 text-white rounded-md text-xs font-semibold hover:bg-black transition-colors">
                            Ingresar
                        </a>
                    @endauth
                </div>
            </header>

            <!-- Custom Header if passed -->
            @if (isset($header))
                <div class="bg-white border-b border-slate-200 py-4 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            @endif

            <!-- Page Content Slot -->
            <main class="flex-1 py-6 px-4 sm:px-6 lg:px-8">
                @if (session('error'))
                    <div class="mb-6 rounded-md bg-rose-50 border border-rose-200 p-4 text-rose-800 flex items-center justify-between shadow-xs" x-data="{ show: true }" x-show="show">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <span class="text-xs font-semibold">{{ session('error') }}</span>
                        </div>
                        <button @click="show = false" type="button" class="text-rose-400 hover:text-rose-600 transition-colors p-1 rounded-md">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                @endif

                @if (session('message') || session('success'))
                    <div class="mb-6 rounded-md bg-emerald-50 border border-emerald-200 p-4 text-emerald-800 flex items-center justify-between shadow-xs" x-data="{ show: true }" x-show="show">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-xs font-semibold">{{ session('message') ?? session('success') }}</span>
                        </div>
                        <button @click="show = false" type="button" class="text-emerald-400 hover:text-emerald-600 transition-colors p-1 rounded-md">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                @endif

                {{ $slot }}
            </main>

            <!-- Footer Attribution (Estilo SotePass) -->
            <footer class="py-4 px-6 border-t border-[#f0eee9] text-center bg-white/60">
                <p class="text-xs text-slate-500 font-medium">
                    SotePass&reg; Expo es un producto digital desarrollado por <a href="https://soteweb.com/" target="_blank" rel="noopener noreferrer" class="text-gold-700 hover:text-gold-800 font-bold underline transition-colors">Soteweb Tecnología</a>
                </p>
            </footer>
        </div>
    </div>

    @livewireScripts
</body>
</html>
