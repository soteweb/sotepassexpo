<x-app-layout>
    <x-slot:title>Administración Global | SotePass Expo (Soteweb)</x-slot>

    <div class="space-y-8" x-data="{ openCreateModal: false, editingPlan: null, planName: '', planPrice: 0, planDesc: '', planBadge: '', planAction: '' }">

        <!-- Top Header & Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Administración de Expositores</h1>
                    <span class="px-2 py-0.5 rounded-lg text-[10px] font-black uppercase tracking-wider bg-gold-50 text-gold-800 border border-gold-200">
                        SuperAdmin
                    </span>
                </div>
                <p class="text-slate-500 mt-1 font-medium text-xs sm:text-sm">Supervisión integral de empresas, stands activos, métricas de captación y ruletas interactivas.</p>
            </div>
            <div class="flex items-center gap-2">
                <button @click="openCreateModal = true" 
                        type="button"
                        class="inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-gold-500 border border-transparent rounded-2xl font-bold text-xs text-white uppercase tracking-widest hover:bg-gold-600 active:scale-95 focus:outline-none focus:ring-2 focus:ring-gold-300 transition-all duration-200 shadow-lg shadow-gold-500/25 shrink-0 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>Nuevo Stand / Empresa</span>
                </button>
            </div>
        </div>

        <!-- Harmonious Metrics Banner (Estilo SotePass) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            
            <!-- Metric 1: Stands / Empresas Activas (Gold / Indigo) -->
            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex items-center justify-between hover:shadow-md hover:border-gold-300 transition-all">
                <div>
                    <div class="text-[11px] font-black text-slate-400 uppercase tracking-wider">Empresas Expositoras</div>
                    <div class="mt-1 text-3xl font-black text-slate-900 tracking-tight">{{ $totalStands }}</div>
                    <div class="text-[11px] font-semibold text-gold-700 mt-1">Stands con ruleta activa</div>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-gold-50 text-gold-700 flex items-center justify-center border border-gold-100 shrink-0">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
            </div>

            <!-- Metric 2: Total Leads Captados (Emerald) -->
            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex items-center justify-between hover:shadow-md hover:border-emerald-100 transition-all">
                <div>
                    <div class="text-[11px] font-black text-slate-400 uppercase tracking-wider">Total Leads Captados</div>
                    <div class="mt-1 text-3xl font-black text-emerald-600 tracking-tight">{{ number_format($totalLeads) }}</div>
                    <div class="text-[11px] font-semibold text-emerald-600 mt-1">Visitantes verificados</div>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-100 shrink-0">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>

            <!-- Metric 3: Giros en Ruleta (Amber) -->
            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex items-center justify-between hover:shadow-md hover:border-amber-200 transition-all">
                <div>
                    <div class="text-[11px] font-black text-slate-400 uppercase tracking-wider">Giros en Ruleta</div>
                    <div class="mt-1 text-3xl font-black text-amber-600 tracking-tight">{{ number_format($totalSpins) }}</div>
                    <div class="text-[11px] font-semibold text-amber-600 mt-1">Interacciones gamificadas</div>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-amber-50 text-amber-700 flex items-center justify-center border border-amber-100 shrink-0">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                </div>
            </div>

            <!-- Metric 4: Tasa de Conversión (Violet) -->
            @php $convRate = $totalLeads > 0 ? round(($totalSpins / $totalLeads) * 100, 1) : 0; @endphp
            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex items-center justify-between hover:shadow-md hover:border-violet-100 transition-all">
                <div>
                    <div class="text-[11px] font-black text-slate-400 uppercase tracking-wider">Tasa de Conversión</div>
                    <div class="mt-1 text-3xl font-black text-violet-600 tracking-tight">{{ $convRate }}%</div>
                    <div class="text-[11px] font-semibold text-violet-600 mt-1">Leads que jugaron ruleta</div>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-violet-50 text-violet-600 flex items-center justify-center border border-violet-100 shrink-0">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
            </div>

        </div>

        <!-- Planes B2B Activos (SaaS Tiers Soteweb) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($plans as $plan)
                <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm relative overflow-hidden flex flex-col justify-between hover:shadow-md hover:border-gold-200 transition-all">
                    @if($plan->badge_text)
                        <span class="absolute top-4 right-4 text-[9px] font-black uppercase tracking-wider px-2 py-0.5 rounded-full bg-gold-100 text-gold-900 border border-gold-300">
                            {{ $plan->badge_text }}
                        </span>
                    @endif
                    <div>
                        <div class="text-[10px] font-black uppercase tracking-wider text-slate-400">Plan Oficial B2B</div>
                        <div class="text-base font-black text-slate-900 mt-0.5">{{ $plan->name }}</div>
                        <div class="text-xl font-black text-gold-600 mt-1">{{ $plan->formatted_price }}</div>
                        <p class="text-xs text-slate-500 mt-2 leading-relaxed">{{ $plan->description }}</p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                        <span class="text-slate-400 font-medium">Stands: <strong class="text-slate-700">{{ $plan->stands_count }}</strong></span>
                        <button type="button" 
                                @click="editingPlan = {{ $plan->id }}; planName = '{{ addslashes($plan->name) }}'; planPrice = {{ $plan->price }}; planDesc = '{{ addslashes($plan->description) }}'; planBadge = '{{ addslashes($plan->badge_text ?? '') }}'; planAction = '{{ route('expo.admin.plans.update', $plan->id) }}'"
                                class="text-gold-700 hover:text-gold-900 font-bold hover:underline text-[11px] flex items-center gap-1 cursor-pointer">
                            <span>✏️ Ajustar Tarifa</span>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        @php
            $pendingStands = $stands->where('status', 'pending');
        @endphp
        @if($pendingStands->isNotEmpty())
            <!-- Alerta de Stands Pendientes de Aprobación -->
            <div class="bg-amber-50 border-2 border-amber-300 rounded-3xl p-5 flex flex-col sm:flex-row items-center justify-between gap-4 shadow-sm">
                <div class="flex items-center gap-3.5">
                    <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-800 flex items-center justify-center font-black text-xl shrink-0">
                        ⏳
                    </div>
                    <div>
                        <h4 class="text-sm font-black text-amber-950">
                            {{ $pendingStands->count() }} empresa(s) registrada(s) pendiente(s) de aprobación
                        </h4>
                        <p class="text-xs text-amber-800 mt-0.5">
                            Revisa el listado abajo y presiona el botón verde <strong>"Aprobar Stand"</strong> para habilitar su ruleta y captación de leads en la feria.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Tabla de Empresas Expositoras (Estilo SotePass Table) -->
        <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h3 class="text-lg font-black text-slate-900">Listado de Stands y Clientes Activos</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Base de datos en PostgreSQL con aislamiento y acceso independiente por stand.</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center px-3 py-1.5 rounded-xl text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">
                        Total: {{ $stands->count() }} expositores
                    </span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100">
                    <thead>
                        <tr class="bg-slate-50/70">
                            <th class="px-6 py-3.5 text-left text-[11px] font-black text-slate-400 uppercase tracking-wider rounded-l-xl">Empresa / Stand</th>
                            <th class="px-6 py-3.5 text-left text-[11px] font-black text-slate-400 uppercase tracking-wider">Plan Contratado</th>
                            <th class="px-6 py-3.5 text-left text-[11px] font-black text-slate-400 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-3.5 text-left text-[11px] font-black text-slate-400 uppercase tracking-wider">Titular / Empresa</th>
                            <th class="px-6 py-3.5 text-left text-[11px] font-black text-slate-400 uppercase tracking-wider">Sector</th>
                            <th class="px-6 py-3.5 text-center text-[11px] font-black text-slate-400 uppercase tracking-wider">Leads</th>
                            <th class="px-6 py-3.5 text-center text-[11px] font-black text-slate-400 uppercase tracking-wider">Giros Ruleta</th>
                            <th class="px-6 py-3.5 text-left text-[11px] font-black text-slate-400 uppercase tracking-wider">Política Ruleta</th>
                            <th class="px-6 py-3.5 text-right text-[11px] font-black text-slate-400 uppercase tracking-wider rounded-r-xl">Acciones Rápidas</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($stands as $stand)
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <!-- Empresa -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl flex items-center justify-center font-black text-white text-sm shadow-2xs shrink-0" style="background-color: {{ $stand->primary_color }};">
                                            {{ strtoupper(substr($stand->slug, 0, 2)) }}
                                        </div>
                                        <div>
                                            <a href="{{ route('expo.panel', $stand->slug) }}" class="font-bold text-slate-900 group-hover:text-gold-700 transition-colors block text-sm">
                                                {{ $stand->name }}
                                            </a>
                                            <div class="flex items-center gap-2 mt-0.5">
                                                <span class="text-xs font-mono font-bold text-slate-500 bg-slate-100 px-1.5 py-0.2 rounded border border-slate-200">
                                                    {{ $stand->stand_number }}
                                                </span>
                                                <span class="text-xs text-slate-400 font-mono">/{{ $stand->slug }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Plan Contratado -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($stand->plan)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-bold bg-gold-50 text-gold-800 border border-gold-200">
                                            ✨ {{ $stand->plan->name }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-xs font-medium bg-slate-100 text-slate-500">
                                            Sin Plan
                                        </span>
                                    @endif
                                </td>

                                <!-- Estado -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($stand->status === 'active' || ($stand->is_active && $stand->status !== 'suspended' && $stand->status !== 'pending'))
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                            Activo
                                        </span>
                                    @elseif($stand->status === 'pending' || (! $stand->is_active && $stand->status !== 'suspended'))
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-bold bg-amber-50 text-amber-800 border border-amber-300">
                                            <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                                            Pendiente
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                            <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                                            Suspendido
                                        </span>
                                    @endif
                                </td>

                                <!-- Titular / Empresa -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-xs font-bold text-slate-800">{{ $stand->user?->name ?? 'Admin Soteweb' }}</div>
                                    <div class="text-[11px] text-slate-400 font-mono">{{ $stand->user?->company_name ?: ($stand->user?->email ?? 'contacto@soteweb.com') }}</div>
                                </td>

                                <!-- Sector -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                        {{ $stand->sector }}
                                    </span>
                                </td>

                                <!-- Leads -->
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="text-sm font-black text-slate-900">{{ number_format($stand->leads_count) }}</span>
                                    <span class="text-[11px] text-slate-400 block">contactos</span>
                                </td>

                                <!-- Giros Ruleta -->
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="text-sm font-black text-amber-600">{{ number_format($stand->spins_count) }}</span>
                                    <span class="text-[11px] text-slate-400 block">jugadas</span>
                                </td>

                                <!-- Política Ruleta -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $policyText = match($stand->spin_policy) {
                                            'once_per_day' => '📅 1 por día',
                                            'unlimited' => '⚡ Ilimitado',
                                            default => '🔒 1 por evento',
                                        };
                                        $policyClass = match($stand->spin_policy) {
                                            'once_per_day' => 'bg-amber-50 text-amber-700 border-amber-200',
                                            'unlimited' => 'bg-violet-50 text-violet-700 border-violet-200',
                                            default => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        };
                                    @endphp
                                    <span class="px-2.5 py-1 inline-flex text-[10px] font-black uppercase tracking-wider rounded-xl border {{ $policyClass }}">
                                        {{ $policyText }}
                                    </span>
                                </td>

                                <!-- Acciones Rápidas -->
                                <td class="px-6 py-4 whitespace-nowrap text-right text-xs font-bold space-x-1.5">
                                    @if($stand->status === 'pending' || ! $stand->is_active)
                                        <!-- Botón Aprobar -->
                                        <form method="POST" action="{{ route('expo.admin.stand.status', $stand->id) }}" class="inline">
                                            @csrf
                                            <input type="hidden" name="status" value="active">
                                            <button type="submit" 
                                                    class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-xs transition-all cursor-pointer"
                                                    title="Aprobar y habilitar este stand para la feria">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                                <span>Aprobar Stand</span>
                                            </button>
                                        </form>
                                    @else
                                        <!-- Botón Suspender -->
                                        <form method="POST" action="{{ route('expo.admin.stand.status', $stand->id) }}" class="inline" onsubmit="return confirm('¿Deseas suspender temporalmente este stand?');">
                                            @csrf
                                            <input type="hidden" name="status" value="suspended">
                                            <button type="submit" 
                                                    class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-xl text-slate-500 hover:text-rose-600 hover:bg-rose-50 border border-slate-200 hover:border-rose-200 text-xs font-bold transition-all cursor-pointer"
                                                    title="Suspender temporalmente stand">
                                                <span>⏸ Suspender</span>
                                            </button>
                                        </form>
                                    @endif

                                    <!-- Abrir Panel del Stand -->
                                    <a href="{{ route('expo.panel', $stand->slug) }}" 
                                       class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-gold-50 text-gold-800 hover:bg-gold-100 border border-gold-200/80 transition-colors shadow-2xs"
                                       title="Ver Panel CRM del Stand">
                                        <span>Panel &rarr;</span>
                                    </a>

                                    <!-- Abrir Ruleta -->
                                    <a href="{{ route('expo.stand', $stand->slug) }}" target="_blank"
                                       class="inline-flex items-center p-1.5 rounded-xl text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition-colors border border-transparent"
                                       title="Abrir QR Registro del Stand">
                                        📱
                                    </a>

                                    <!-- Abrir Sorteo TV -->
                                    <a href="{{ route('expo.sorteo', $stand->slug) }}" target="_blank"
                                       class="inline-flex items-center p-1.5 rounded-xl text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition-colors border border-transparent"
                                       title="Abrir Sorteo en Pantalla">
                                        📺
                                    </a>

                                    <!-- Eliminar Stand (Exclusivo Master) -->
                                    <form method="POST" action="{{ route('expo.admin.stand.destroy', $stand->id) }}" class="inline" onsubmit="return confirm('¿Estás seguro de que deseas ELIMINAR definitivamente el stand de {{ addslashes($stand->name) }} y todos sus datos asociados?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="inline-flex items-center p-1.5 rounded-xl text-slate-400 hover:text-red-600 hover:bg-red-50 transition-colors cursor-pointer"
                                                title="Eliminar Cliente / Stand">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-12 text-sm text-slate-400 text-center font-medium">
                                    No hay stands registrados todavía. Comienza creando el primero con el botón superior.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- MODAL: REGISTRAR NUEVA EMPRESA EXPOSITORA (Estilo SotePass) -->
        <div x-show="openCreateModal" 
             x-cloak 
             class="fixed inset-0 z-50 flex items-center justify-center p-4">
            
            <!-- Backdrop -->
            <div @click="openCreateModal = false" 
                 x-show="openCreateModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs"></div>

            <!-- Modal Content -->
            <div x-show="openCreateModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="bg-white rounded-3xl p-6 sm:p-8 shadow-2xl border border-slate-200/80 max-w-2xl w-full relative z-10">
                
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-2.5">
                        <div class="w-10 h-10 rounded-2xl bg-gold-50 text-gold-700 flex items-center justify-center border border-gold-100 font-bold">
                            ✨
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-slate-900">Dar de Alta Empresa Expositora</h3>
                            <p class="text-xs text-slate-500">Configura un nuevo stand con landing propia, ruleta y base de datos.</p>
                        </div>
                    </div>
                    <button @click="openCreateModal = false" type="button" class="p-1.5 text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded-xl transition-colors">
                        ✕
                    </button>
                </div>

                <form action="{{ route('expo.admin.create') }}" method="POST" class="mt-6 space-y-4 text-xs">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <x-input-label value="Nombre de la Empresa *" />
                            <x-text-input type="text" name="name" required placeholder="Ej. Kurosu & Cia. S.A." class="w-full" />
                        </div>

                        <div>
                            <x-input-label value="Slug URL (Único, sin espacios) *" />
                            <x-text-input type="text" name="slug" required placeholder="Ej. kurosu" class="w-full font-mono text-xs" />
                        </div>

                        <div>
                            <x-input-label value="Plan Contratado (SaaS) *" />
                            <select name="plan_id" class="w-full rounded-2xl border-slate-200 text-xs font-semibold focus:border-gold-500 focus:ring-gold-500 bg-slate-50 py-2.5">
                                @foreach($plans as $plan)
                                    <option value="{{ $plan->id }}" {{ $loop->iteration == 2 ? 'selected' : '' }}>
                                        {{ $plan->name }} ({{ $plan->formatted_price }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <x-input-label value="Usuario Titular / Empresa *" />
                            <select name="user_id" class="w-full rounded-2xl border-slate-200 text-xs font-semibold focus:border-gold-500 focus:ring-gold-500 bg-slate-50 py-2.5">
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ $user->id === Auth::id() ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }}) [{{ $user->role }}]
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <x-input-label value="Número de Stand *" />
                            <x-text-input type="text" name="stand_number" required placeholder="Ej. Stand 45 - Pabellón Maquinarias" class="w-full" />
                        </div>

                        <div>
                            <x-input-label value="Sector / Rubro *" />
                            <x-text-input type="text" name="sector" required placeholder="Ej. Maquinarias Agrícolas y Servicios" class="w-full" />
                        </div>

                        <div>
                            <x-input-label value="Color Corporativo (Hex) *" />
                            <div class="flex gap-2">
                                <input type="color" name="primary_color" value="#4f46e5"
                                       class="h-10 w-14 rounded-xl bg-slate-50 border border-slate-200 cursor-pointer p-1">
                                <x-text-input type="text" value="#4f46e5" readonly class="flex-1 font-mono text-xs" />
                            </div>
                        </div>

                        <div>
                            <x-input-label value="Política de Giros en Ruleta *" />
                            <select name="spin_policy" class="w-full rounded-2xl border-slate-200 text-xs font-semibold focus:border-gold-500 focus:ring-gold-500 bg-slate-50 py-2.5">
                                <option value="once_per_day">📅 1 giro por día (Recomendado)</option>
                                <option value="once_per_event">🔒 1 giro en todo el evento</option>
                                <option value="unlimited">⚡ Giros ilimitados (Demo/Pruebas)</option>
                            </select>
                        </div>

                        <div class="sm:col-span-2">
                            <x-input-label value="PIN de Acceso Mostrador (4 dígitos) *" />
                            <x-text-input type="text" name="access_pin" value="1234" required class="w-full font-mono text-xs" />
                        </div>
                    </div>

                    <div class="pt-2">
                        <x-input-label value="Eslogan o Mensaje de Bienvenida" />
                        <x-text-input type="text" name="tagline" placeholder="Ej. Tecnología líder para el campo paraguayo." class="w-full" />
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <button @click="openCreateModal = false" type="button" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 font-bold text-xs rounded-xl transition-colors cursor-pointer">
                            Cancelar
                        </button>
                        <button type="submit" class="px-6 py-2.5 bg-gold-500 hover:bg-gold-600 text-white font-bold text-xs uppercase tracking-wider rounded-xl transition-all shadow-md shadow-gold-500/20 cursor-pointer">
                            + Crear y Configurar Stand
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL: EDITAR PLAN B2B (MASTER / DUEÑO DEL SAAS) -->
        <div x-show="editingPlan !== null" 
             x-cloak 
             class="fixed inset-0 z-50 overflow-y-auto" 
             role="dialog" 
             aria-modal="true">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" @click="editingPlan = null"></div>

            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative w-full max-w-lg bg-white rounded-3xl p-6 sm:p-8 shadow-2xl border border-slate-100 space-y-6"
                     @click.stop>
                    
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                        <div>
                            <span class="text-[10px] font-black uppercase tracking-wider text-gold-700">Ajuste de Precios Master</span>
                            <h3 class="text-lg font-black text-slate-900" x-text="'Editar ' + planName"></h3>
                        </div>
                        <button type="button" @click="editingPlan = null" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center text-sm font-bold cursor-pointer">
                            ✕
                        </button>
                    </div>

                    <form method="POST" :action="planAction" class="space-y-4 text-xs">
                        @csrf

                        <div>
                            <x-input-label value="Nombre del Plan *" />
                            <x-text-input type="text" name="name" x-model="planName" required class="w-full text-xs" />
                        </div>

                        <div>
                            <x-input-label value="Precio en Guaraníes (PYG) *" />
                            <x-text-input type="number" name="price" x-model="planPrice" required step="50000" min="0" class="w-full font-mono text-xs" />
                            <p class="text-[10px] text-slate-400 mt-1">Ej: 800000 para Gs. 800.000 (sin puntos).</p>
                        </div>

                        <div>
                            <x-input-label value="Etiqueta / Badge (Opcional)" />
                            <x-text-input type="text" name="badge_text" x-model="planBadge" placeholder="Ej: MÁS POPULAR" class="w-full text-xs" />
                        </div>

                        <div>
                            <x-input-label value="Descripción Comercial" />
                            <textarea name="description" x-model="planDesc" rows="3" class="w-full rounded-xl border-slate-200 text-xs focus:border-gold-500 focus:ring-gold-500"></textarea>
                        </div>

                        <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                            <button type="button" @click="editingPlan = null" class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold hover:bg-slate-50 transition cursor-pointer">
                                Cancelar
                            </button>
                            <button type="submit" class="px-5 py-2.5 rounded-xl bg-gold-500 hover:bg-gold-600 text-white font-bold shadow-md transition cursor-pointer">
                                Guardar Nueva Tarifa
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
