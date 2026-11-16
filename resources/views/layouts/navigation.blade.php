@php
    $authUser = auth()->user();
    $currentStand = $stand ?? $authUser?->stand;
    $allStands = ($authUser && $authUser->isAdmin()) 
        ? \App\Models\Stand::with('plan')->where('is_active', true)->orderBy('name')->get() 
        : collect();
@endphp

<!-- Mobile Sidebar Backdrop (Off-canvas) -->
<div x-show="sidebarOpen" 
     x-cloak
     x-transition:enter="transition-opacity ease-linear duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-linear duration-300"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @click="sidebarOpen = false"
     class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs z-40 lg:hidden">
</div>

<!-- Sidebar Container (Base44 SaaS Style - Expands to w-64, Collapses to w-16 Mini Rail) -->
<aside :class="{
           'translate-x-0': sidebarOpen, 
           '-translate-x-full': !sidebarOpen, 
           'lg:w-16': sidebarCollapsed, 
           'lg:w-64': !sidebarCollapsed
       }"
       class="fixed inset-y-0 left-0 z-50 w-64 bg-[#fcfbfa] text-slate-800 flex flex-col transition-all duration-300 ease-in-out border-r border-[#eae8e4] shadow-xl lg:shadow-none font-sans select-none overflow-x-hidden lg:static lg:h-screen lg:sticky lg:top-0 lg:shrink-0 lg:translate-x-0 lg:z-30">
        
        <!-- 1. Brand Header -->
        <div class="h-16 flex items-center border-b border-[#f0eee9] shrink-0"
             :class="sidebarCollapsed ? 'lg:justify-center px-3' : 'justify-between px-4'">
            
            <a href="{{ $authUser && $authUser->isAdmin() ? route('expo.admin') : route('dashboard') }}" 
               class="flex items-center gap-2.5 group focus:outline-none" 
               :title="sidebarCollapsed ? 'SotePass Expo' : ''">
                <!-- SotePass Gold/Indigo Badge -->
                <div class="w-8 h-8 bg-slate-900 rounded-md flex items-center justify-center shadow-xs group-hover:scale-105 transition-transform shrink-0 text-white">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div x-show="!sidebarCollapsed" class="flex items-center gap-1.5 whitespace-nowrap transition-opacity">
                    <span class="text-base font-bold text-slate-900 tracking-tight">
                        Sote<span class="text-gold-700">Pass</span>
                    </span>
                    <span class="px-1.5 py-0.5 rounded-sm text-[9px] font-semibold uppercase tracking-wider bg-gold-50 text-gold-800 border border-gold-200">
                        Expo
                    </span>
                </div>
            </a>

            <!-- Close Button (X on mobile, Rail Toggle on Desktop) -->
            <button @click="toggleSidebar()" 
                    type="button"
                    class="p-1.5 text-slate-400 hover:text-slate-700 hover:bg-[#f2f0eb] rounded-md transition-colors cursor-pointer"
                    :class="sidebarCollapsed ? 'lg:hidden' : 'block'"
                    title="Cerrar / Contraer menú">
                <svg class="w-5 h-5 lg:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                <svg class="w-4 h-4 hidden lg:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <rect x="3" y="3" width="18" height="18" rx="2" stroke-width="2"/>
                    <path d="M9 3v18" stroke-width="2"/>
                </svg>
            </button>
        </div>

        <!-- 2. Navigation List -->
        <div @click="if (window.innerWidth < 1024) sidebarOpen = false" 
             class="flex-1 overflow-y-auto px-2.5 py-4 space-y-4" style="scrollbar-width: thin;">

            <!-- SECCIÓN: MI STAND EXPOSITOR -->
            @if($currentStand)
                <div class="space-y-1">
                    <div x-show="!sidebarCollapsed" class="px-3 flex items-center justify-between mb-1.5">
                        <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">Mi Stand</p>
                        @if($currentStand->plan)
                            <span class="text-[9px] font-semibold uppercase tracking-wider text-gold-700 bg-gold-50 px-1.5 py-0.2 rounded-sm border border-gold-200">
                                {{ $currentStand->plan->name }}
                            </span>
                        @endif
                    </div>

                    @if($authUser && $authUser->stands->count() > 1)
                        <div x-show="!sidebarCollapsed" class="px-2 pb-2">
                            <label class="block text-[10px] font-semibold uppercase tracking-wider text-slate-400 mb-1">Cambiar de Stand / Evento</label>
                            <select onchange="if(this.value) window.location.href = this.value" 
                                    class="w-full text-xs font-semibold rounded-md border-slate-300 bg-white py-1.5 px-2 focus:border-slate-900 focus:ring-1 focus:ring-slate-900 shadow-xs cursor-pointer">
                                @foreach($authUser->stands as $userStand)
                                    <option value="{{ route('expo.panel', $userStand->slug) }}" {{ (isset($currentStand) && $currentStand->id === $userStand->id) ? 'selected' : '' }}>
                                        {{ $userStand->name }} ({{ $userStand->stand_number }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <!-- Panel de Control Stand -->
                    @php $isPanel = request()->routeIs('expo.panel') && request()->route('slug') === $currentStand->slug; @endphp
                    <a href="{{ route('expo.panel', $currentStand->slug) }}"
                       title="Panel del Stand"
                       class="flex items-center rounded-md text-[13px] transition-colors {{ $isPanel ? 'bg-[#edeae4] font-semibold text-slate-900 shadow-xs' : 'text-slate-700 hover:bg-[#f4f2ee] font-medium' }}"
                       :class="sidebarCollapsed ? 'lg:justify-center lg:w-10 lg:h-10 lg:mx-auto px-0' : 'gap-2.5 px-3 py-2'">
                        <div class="w-4 h-4 shrink-0 flex items-center justify-center font-bold text-[10px]" style="color: {{ $currentStand->primary_color }};">
                            ●
                        </div>
                        <span x-show="!sidebarCollapsed" class="truncate whitespace-nowrap">{{ $currentStand->name }}</span>
                    </a>

                    <!-- Personalizar Landing del Visitante -->
                    @php $isPersonalizar = request()->routeIs('expo.stand.personalizar') && request()->route('slug') === $currentStand->slug; @endphp
                    <a href="{{ route('expo.stand.personalizar', $currentStand->slug) }}"
                       title="Personalizar Landing y Marca"
                       class="flex items-center rounded-md text-[13px] transition-colors {{ $isPersonalizar ? 'bg-[#edeae4] font-semibold text-slate-900 shadow-xs' : 'text-slate-700 hover:bg-[#f4f2ee] font-medium' }}"
                       :class="sidebarCollapsed ? 'lg:justify-center lg:w-10 lg:h-10 lg:mx-auto px-0' : 'gap-2.5 px-3 py-2'">
                        <div class="w-4 h-4 shrink-0 flex items-center justify-center text-sm">
                            🎨
                        </div>
                        <span x-show="!sidebarCollapsed" class="truncate whitespace-nowrap">Personalizar Landing</span>
                    </a>

                    <!-- Landing QR -->
                    <a href="{{ route('expo.stand', $currentStand->slug) }}" 
                       target="_blank"
                       title="Página de Captación / QR Mostrador"
                       class="flex items-center rounded-md text-[13px] text-slate-700 hover:bg-[#f4f2ee] font-medium transition-colors"
                       :class="sidebarCollapsed ? 'lg:justify-center lg:w-10 lg:h-10 lg:mx-auto px-0' : 'justify-between px-3 py-2'">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <span class="w-4 h-4 shrink-0 flex items-center justify-center text-sm">📱</span>
                            <span x-show="!sidebarCollapsed" class="truncate whitespace-nowrap">Landing QR Visitante</span>
                        </div>
                        <svg x-show="!sidebarCollapsed" class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    </a>

                    <!-- Ruleta Interactiva (Si el plan lo incluye o tiene spins) -->
                    @if($currentStand->allow_spins)
                        @php $firstLead = $currentStand->leads()->first(); @endphp
                        <a href="{{ $firstLead ? route('expo.ruleta', ['slug' => $currentStand->slug, 'leadId' => $firstLead->id]) : route('expo.stand', $currentStand->slug) }}" 
                           target="_blank"
                           title="Ruleta de Premios (Pantalla Completa / Tótem)"
                           class="flex items-center rounded-md text-[13px] text-slate-700 hover:bg-[#f4f2ee] font-medium transition-colors"
                           :class="sidebarCollapsed ? 'lg:justify-center lg:w-10 lg:h-10 lg:mx-auto px-0' : 'justify-between px-3 py-2'">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <span class="w-4 h-4 shrink-0 flex items-center justify-center text-sm">🎡</span>
                                <span x-show="!sidebarCollapsed" class="truncate whitespace-nowrap">Ruleta en Vivo</span>
                            </div>
                            <svg x-show="!sidebarCollapsed" class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </a>
                    @endif

                    <!-- Sorteo TV 4K -->
                    <a href="{{ route('expo.sorteo', $currentStand->slug) }}" 
                       target="_blank"
                       title="Sorteo Oficial en Pantalla TV 4K"
                       class="flex items-center rounded-md text-[13px] text-slate-700 hover:bg-[#f4f2ee] font-medium transition-colors"
                       :class="sidebarCollapsed ? 'lg:justify-center lg:w-10 lg:h-10 lg:mx-auto px-0' : 'justify-between px-3 py-2'">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <span class="w-4 h-4 shrink-0 flex items-center justify-center text-sm">📺</span>
                            <span x-show="!sidebarCollapsed" class="truncate whitespace-nowrap">Sorteo en TV 4K</span>
                        </div>
                        <svg x-show="!sidebarCollapsed" class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    </a>
                </div>
            @elseif($authUser && !$authUser->isAdmin())
                <!-- Si el usuario no tiene stand todavía -->
                <div class="space-y-1">
                    <a href="{{ route('stands.create') }}"
                       class="flex items-center rounded-md text-[13px] bg-gold-50 text-gold-800 font-semibold p-3 border border-gold-200">
                        <span>+ Dar de Alta Mi Stand</span>
                    </a>
                </div>
            @endif

            <!-- SECCIÓN: ADMINISTRACIÓN SOTEWEB (Solo Admin) -->
            @if($authUser && $authUser->isAdmin())
                <div class="pt-2 border-t border-[#f0eee9] space-y-1">
                    <p x-show="!sidebarCollapsed" class="px-3 text-[10px] font-semibold uppercase tracking-wider text-slate-400 mb-1">
                        SuperAdmin Soteweb
                    </p>

                    @php $isAdmin = request()->routeIs('expo.admin'); @endphp
                    <a href="{{ route('expo.admin') }}"
                       title="Administración Global"
                       class="flex items-center rounded-md text-[13px] transition-colors {{ $isAdmin ? 'bg-[#edeae4] font-semibold text-slate-900 shadow-xs' : 'text-slate-700 hover:bg-[#f4f2ee] font-medium' }}"
                       :class="sidebarCollapsed ? 'lg:justify-center lg:w-10 lg:h-10 lg:mx-auto px-0' : 'justify-between px-3 py-2'">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 shrink-0 {{ $isAdmin ? 'text-slate-900' : 'text-slate-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            <span x-show="!sidebarCollapsed" class="truncate whitespace-nowrap">Panel Global</span>
                        </div>
                        <span x-show="!sidebarCollapsed" class="px-1.5 py-0.5 text-[10px] font-bold rounded-sm bg-slate-100 text-slate-700 border border-slate-200">
                            {{ $allStands->count() }}
                        </span>
                    </a>
                </div>

                <!-- SECCIÓN: CAMBIAR DE STAND (Solo Admin puede ver todos) -->
                @if($allStands->count() > 0)
                    <div class="pt-2 border-t border-[#f0eee9] space-y-1">
                        <p x-show="!sidebarCollapsed" class="px-3 text-[10px] font-semibold uppercase tracking-wider text-slate-400 mb-1">
                            Todos los Expositores ({{ $allStands->count() }})
                        </p>
                        @foreach($allStands as $s)
                            @php $isActiveStand = $currentStand && $currentStand->id === $s->id; @endphp
                            <a href="{{ route('expo.panel', $s->slug) }}"
                               title="{{ $s->name }} ({{ $s->stand_number }})"
                               class="flex items-center rounded-md text-[12px] transition-colors {{ $isActiveStand ? 'bg-[#edeae4] font-semibold text-slate-900' : 'text-slate-600 hover:bg-[#f4f2ee] font-normal' }}"
                               :class="sidebarCollapsed ? 'lg:justify-center lg:w-10 lg:h-10 lg:mx-auto px-0' : 'justify-between px-3 py-1.5'">
                                <div class="flex items-center gap-2 min-w-0">
                                    <span class="w-2 h-2 rounded-full shrink-0" style="background-color: {{ $s->primary_color }};"></span>
                                    <span x-show="!sidebarCollapsed" class="truncate">{{ $s->name }}</span>
                                </div>
                                <span x-show="!sidebarCollapsed" class="text-[10px] text-slate-400 shrink-0 font-mono">{{ $s->stand_number }}</span>
                            </a>
                        @endforeach
                    </div>
                @endif
            @endif

            <!-- SECCIÓN: AYUDA Y SOPORTE TÉCNICO -->
            <div class="pt-2 border-t border-[#f0eee9] space-y-1">
                <p x-show="!sidebarCollapsed" class="px-3 text-[10px] font-semibold uppercase tracking-wider text-slate-400 mb-1">Soporte Técnico</p>

                <a href="https://wa.me/595981123456?text={{ urlencode('Hola Soteweb, solicito asistencia técnica para SotePass Expo.') }}" target="_blank"
                   title="Mesa de Ayuda WhatsApp"
                   class="flex items-center rounded-md text-[13px] text-slate-700 hover:bg-[#f4f2ee] font-medium transition-colors group"
                   :class="sidebarCollapsed ? 'lg:justify-center lg:w-10 lg:h-10 lg:mx-auto px-0' : 'justify-between px-3 py-2'">
                    <div class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 shrink-0 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <span x-show="!sidebarCollapsed" class="truncate whitespace-nowrap">Mesa de Ayuda</span>
                    </div>
                    <svg x-show="!sidebarCollapsed" class="w-3.5 h-3.5 text-slate-400 group-hover:text-slate-600 transition-transform group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

        </div>

    </aside>
