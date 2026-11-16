<x-app-layout :stand="$stand">
    <x-slot:title>Panel de Expositor | {{ $stand->name }}</x-slot>

    <div class="space-y-8" x-data="{ editingPrize: null, showNewPrizeModal: false, editingDraw: null, showNewDrawModal: false, showResetModal: false }">

        <!-- SUBHEADER DEL STAND CON ACCIONES RÁPIDAS -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3.5">
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center font-black text-white text-lg shadow-sm border border-slate-200/50 shrink-0" style="background-color: {{ $stand->primary_color }};">
                    {{ strtoupper(substr($stand->slug, 0, 2)) }}
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">{{ $stand->name }}</h1>
                        <span class="text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-700 border border-slate-200">
                            {{ $stand->stand_number }}
                        </span>
                        @if($stand->plan)
                            <span class="text-[11px] font-black px-2.5 py-0.5 rounded-full bg-gold-50 text-gold-800 border border-gold-200">
                                ✨ {{ $stand->plan->name }}
                            </span>
                        @endif
                    </div>
                    <p class="text-xs text-slate-500 mt-0.5">{{ $stand->sector }} • Base de Datos Aislada y Seguridad Multitenant</p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <!-- Switch Modo Ruleta / Solo Datos -->
                <form action="{{ route('expo.stand.toggle-ruleta', $stand->slug) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" 
                            class="inline-flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-bold transition-all shadow-2xs border cursor-pointer {{ $stand->allow_spins ? 'bg-emerald-50 text-emerald-800 border-emerald-300 hover:bg-emerald-100' : 'bg-slate-100 text-slate-700 border-slate-300 hover:bg-slate-200' }}"
                            title="Haz clic para activar o desactivar la Ruleta interactiva">
                        <span>{{ $stand->allow_spins ? '🎡 Ruleta: ACTIVADA' : '📋 Ruleta: DESACTIVADA (Solo Sorteo)' }}</span>
                        <span class="text-[10px] px-1.5 py-0.5 rounded font-black bg-white border {{ $stand->allow_spins ? 'border-emerald-300 text-emerald-700' : 'border-slate-300 text-slate-600' }}">
                            Cambiar
                        </span>
                    </button>
                </form>

                <!-- QR Mostrador -->
                <a href="{{ route('expo.stand', $stand->slug) }}" target="_blank"
                   class="inline-flex items-center gap-2 px-3.5 py-2 bg-white border border-slate-200 text-slate-700 hover:text-slate-900 hover:bg-slate-50 rounded-xl text-xs font-bold transition-colors shadow-2xs">
                    <span>📱 QR Mostrador</span>
                </a>

                <!-- Personalizar Landing del Visitante -->
                <a href="{{ route('expo.stand.personalizar', $stand->slug) }}"
                   class="inline-flex items-center gap-2 px-3.5 py-2 bg-white border border-slate-200 text-slate-700 hover:text-slate-900 hover:bg-slate-50 rounded-xl text-xs font-bold transition-colors shadow-2xs">
                    <span>🎨 Personalizar Landing</span>
                </a>

                <!-- Ruleta Móvil/Tótem -->
                @if($stand->allow_spins)
                    <a href="{{ route('expo.stand', ['slug' => $stand->slug, 'totem' => 1]) }}" target="_blank"
                       class="inline-flex items-center gap-2 px-3.5 py-2 bg-indigo-50 border border-indigo-200 text-indigo-700 hover:bg-indigo-100 rounded-xl text-xs font-bold transition-colors shadow-2xs">
                        <span>🎡 Tótem Ruleta</span>
                    </a>
                @endif

                <!-- Pantalla TV 4K -->
                @if($stand->plan?->has_tv_draw ?? true)
                    <a href="{{ route('expo.sorteo', $stand->slug) }}" target="_blank"
                       class="inline-flex items-center gap-2 px-3.5 py-2 bg-amber-50 border border-amber-200 text-amber-800 hover:bg-amber-100 rounded-xl text-xs font-bold transition-colors shadow-2xs">
                        <span>📺 Sorteo TV 4K</span>
                    </a>
                @endif

                <!-- Exportar CSV / Excel -->
                <a href="{{ route('expo.export', $stand->slug) }}"
                   class="inline-flex items-center gap-2 px-3.5 py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl text-xs font-bold transition-all shadow-sm shadow-emerald-500/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Excel</span>
                </a>
            </div>
        </div>

        <!-- NOTIFICACIONES FLASH -->
        @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center justify-between shadow-2xs">
                <span>{{ session('success') }}</span>
                <button type="button" @click="$el.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800">✕</button>
            </div>
        @endif
        @if(session('error'))
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold flex items-center justify-between shadow-2xs">
                <span>{{ session('error') }}</span>
                <button type="button" @click="$el.parentElement.remove()" class="text-rose-500 hover:text-rose-800">✕</button>
            </div>
        @endif
        @if(session('warning'))
            <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-800 text-xs font-bold flex items-center justify-between shadow-2xs">
                <span>{{ session('warning') }}</span>
                <button type="button" @click="$el.parentElement.remove()" class="text-amber-500 hover:text-amber-800">✕</button>
            </div>
        @endif

        @if($stand->status === 'pending' || ! $stand->is_active)
            <div class="p-5 rounded-3xl bg-amber-50 border-2 border-amber-300 text-amber-900 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 shadow-sm">
                <div class="flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-2xl bg-amber-100 text-amber-800 flex items-center justify-center font-black text-lg shrink-0">
                        ⏳
                    </div>
                    <div>
                        <div class="text-xs font-black uppercase tracking-wider text-amber-900">
                            {{ $stand->status === 'suspended' ? 'Stand Suspendido Temporalmente' : 'Stand en Revisión / Pendiente de Aprobación' }}
                        </div>
                        <div class="text-xs text-amber-800 mt-0.5 leading-relaxed">
                            {{ $stand->status === 'suspended' 
                                ? 'La captación pública de este stand está pausada. Comunícate con Soteweb para reactivarla.' 
                                : 'Tu stand fue registrado con éxito. El equipo de Soteweb activará la captación de leads y ruleta tras verificar tu solicitud. Mientras tanto, puedes configurar tus premios y ajustar tus datos.' }}
                        </div>
                    </div>
                </div>
                <a href="https://wa.me/595981123456" target="_blank" class="shrink-0 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-xs transition-all">
                    WhatsApp Soporte &rarr;
                </a>
            </div>
        @endif

        <!-- TARJETAS DE MÉTRICAS EN VIVO -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Leads -->
            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex items-center justify-between">
                <div>
                    <div class="text-[11px] font-black text-slate-400 uppercase tracking-wider">Contactos / Leads</div>
                    <div class="mt-1 text-3xl font-black text-slate-900 tracking-tight">{{ number_format($totalLeads) }}</div>
                    <div class="text-[11px] font-semibold text-emerald-600 mt-1">Visitantes verificados</div>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-100 font-bold text-xl">
                    👥
                </div>
            </div>

            <!-- Giros -->
            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex items-center justify-between">
                <div>
                    <div class="text-[11px] font-black text-slate-400 uppercase tracking-wider">Giros de Ruleta</div>
                    <div class="mt-1 text-3xl font-black text-amber-600 tracking-tight">{{ number_format($totalSpins) }}</div>
                    <div class="text-[11px] font-semibold text-amber-600 mt-1">Interacciones gamificadas</div>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center border border-amber-100 font-bold text-xl">
                    🎡
                </div>
            </div>

            <!-- Cupones Canjeados -->
            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex items-center justify-between">
                <div>
                    <div class="text-[11px] font-black text-slate-400 uppercase tracking-wider">Premios Entregados</div>
                    <div class="mt-1 text-3xl font-black text-indigo-600 tracking-tight">{{ number_format($redeemedSpins) }}</div>
                    <div class="text-[11px] font-semibold text-indigo-600 mt-1">Canjeados en mostrador</div>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center border border-indigo-100 font-bold text-xl">
                    🎁
                </div>
            </div>

            <!-- Conversión -->
            @php $rate = $totalLeads > 0 ? round(($totalSpins / $totalLeads) * 100, 1) : 0; @endphp
            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex items-center justify-between">
                <div>
                    <div class="text-[11px] font-black text-slate-400 uppercase tracking-wider">Tasa de Participación</div>
                    <div class="mt-1 text-3xl font-black text-violet-600 tracking-tight">{{ $rate }}%</div>
                    <div class="text-[11px] font-semibold text-violet-600 mt-1">Leads que giraron ruleta</div>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-violet-50 text-violet-600 flex items-center justify-center border border-violet-100 font-bold text-xl">
                    ⚡
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- VALIDADOR DE CUPONES EN MOSTRADOR (COLUMNA 1) -->
            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="w-8 h-8 rounded-xl bg-gold-50 text-gold-700 flex items-center justify-center font-bold text-sm">🎟️</span>
                        <h2 class="text-base font-black text-slate-900">Validar Cupón de Mostrador</h2>
                    </div>
                    <p class="text-xs text-slate-500 leading-relaxed mb-4">
                        Cuando un visitante gane un premio y se acerque a retirar su gorra o termo, ingresa aquí su código para validarlo y registrar la entrega.
                    </p>

                    <form action="{{ route('expo.redeem', $stand->slug) }}" method="POST" class="space-y-3">
                        @csrf
                        <div>
                            <label class="block text-[11px] font-black uppercase tracking-wider text-slate-400 mb-1">Código del Cupón</label>
                            <input type="text" name="coupon_code" placeholder="Ej. SP-KU-4589" required
                                   class="w-full uppercase font-mono font-bold text-sm rounded-2xl border-slate-200 focus:border-gold-500 focus:ring-gold-500 bg-slate-50 py-3 px-4">
                        </div>
                        <button type="submit"
                                class="w-full py-3 px-4 bg-gold-500 hover:bg-gold-600 active:scale-[0.98] text-white font-bold text-xs uppercase tracking-wider rounded-2xl transition-all shadow-md shadow-gold-500/20 cursor-pointer">
                            ✓ Canjear y Entregar Premio
                        </button>
                    </form>
                </div>

                <div class="mt-4 pt-4 border-t border-slate-100 text-[11px] text-slate-400">
                    <span class="font-bold text-slate-600">Tip de seguridad:</span> Cada cupón es de un solo uso para prevenir entregas duplicadas.
                </div>
            </div>

            <!-- GESTOR DE PREMIOS Y STOCK (COLUMNA 2 & 3) -->
            <div class="lg:col-span-2 bg-white rounded-3xl p-6 border border-slate-100 shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
                    <div class="flex items-center gap-2">
                        <span class="w-8 h-8 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center font-bold text-sm">🎁</span>
                        <div>
                            <h2 class="text-base font-black text-slate-900">Premios y Sectores de la Ruleta</h2>
                            <p class="text-xs text-slate-500">Ajusta el stock, probabilidades o agrega más regalos y sectores de 'Seguí Participando'.</p>
                        </div>
                    </div>
                    <button type="button" @click="showNewPrizeModal = true"
                            class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-gold-500 hover:bg-gold-600 text-white font-bold text-xs shadow-xs transition-all cursor-pointer shrink-0">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        <span>+ Agregar Premio / Sector</span>
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100 text-xs">
                        <thead>
                            <tr class="text-slate-400 font-black uppercase text-[10px] tracking-wider text-left">
                                <th class="pb-3">Premio</th>
                                <th class="pb-3 text-center">Stock Restante</th>
                                <th class="pb-3 text-center">Peso (%)</th>
                                <th class="pb-3 text-center">Tipo</th>
                                <th class="pb-3 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-semibold text-slate-700">
                            @foreach($prizes as $prize)
                                <tr class="hover:bg-slate-50/50">
                                    <form action="{{ route('expo.prize.update', ['slug' => $stand->slug, 'prizeId' => $prize->id]) }}" method="POST">
                                        @csrf
                                        <td class="py-3">
                                            <div class="flex items-center gap-2">
                                                <input type="color" name="color" value="{{ $prize->color ?? '#6366f1' }}" 
                                                       class="w-7 h-7 rounded-lg border border-slate-200 cursor-pointer p-0.5 shrink-0 hover:scale-105 transition" 
                                                       title="Clic para cambiar el color de este sector en la ruleta">
                                                <input type="text" name="name" value="{{ $prize->name }}" required
                                                       class="text-xs font-bold text-slate-800 bg-transparent border-b border-transparent hover:border-slate-300 focus:border-gold-500 focus:bg-white rounded px-1 py-0.5 w-full">
                                            </div>
                                        </td>
                                        <td class="py-3 text-center">
                                            <input type="number" name="remaining_stock" value="{{ $prize->remaining_stock }}" min="0" required
                                                   class="w-16 text-center font-mono font-bold text-xs bg-slate-50 rounded-xl border-slate-200 py-1">
                                        </td>
                                        <td class="py-3 text-center">
                                            <input type="number" name="probability_weight" value="{{ $prize->probability_weight }}" min="0" max="100" required
                                                   class="w-16 text-center font-mono font-bold text-xs bg-slate-50 rounded-xl border-slate-200 py-1">
                                        </td>
                                        <td class="py-3 text-center">
                                            @if($prize->is_real_prize)
                                                <span class="px-2 py-0.5 rounded-md text-[10px] font-black uppercase bg-emerald-50 text-emerald-700 border border-emerald-200">Real</span>
                                            @else
                                                <span class="px-2 py-0.5 rounded-md text-[10px] font-black uppercase bg-slate-100 text-slate-500">Consuelo</span>
                                            @endif
                                        </td>
                                        <td class="py-3 text-right space-x-1 whitespace-nowrap">
                                            <button type="submit" class="px-2.5 py-1 bg-slate-100 hover:bg-gold-500 hover:text-white text-slate-700 rounded-lg font-bold text-[11px] transition-colors cursor-pointer">
                                                Guardar
                                            </button>
                                    </form>
                                            <form action="{{ route('expo.prize.destroy', ['slug' => $stand->slug, 'prizeId' => $prize->id]) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar este sector de la ruleta?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors cursor-pointer" title="Eliminar premio">
                                                    🗑️
                                                </button>
                                            </form>
                                        </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <!-- GESTIÓN INTEGRAL DE PREMIOS DEL SORTEO FINAL (MÚLTIPLES PREMIOS) -->
        <div class="bg-white rounded-[2rem] p-6 sm:p-8 border border-slate-100 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 pb-6 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-700 flex items-center justify-center font-black text-xl shadow-xs">
                        🎁
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h2 class="text-base sm:text-lg font-black text-slate-900 tracking-tight">Premios del Sorteo Final</h2>
                            <span class="text-[10px] font-black uppercase px-2 py-0.5 rounded bg-indigo-50 text-indigo-700 border border-indigo-200">
                                {{ $draws->count() }} {{ $draws->count() === 1 ? 'Premio' : 'Premios' }}
                            </span>
                        </div>
                        <p class="text-xs text-slate-500 mt-0.5">Agrega, edita o elimina los premios que se sortearán en pantalla grande al final de la jornada o cierre de feria.</p>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <button type="button" 
                            @click="showNewDrawModal = true"
                            class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200 font-bold text-xs rounded-xl transition cursor-pointer">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        <span>+ Agregar Premio de Sorteo</span>
                    </button>

                    <a href="{{ route('expo.sorteo', $stand->slug) }}" target="_blank"
                       class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-sm transition-all cursor-pointer">
                        <span>📺 Pantalla Sorteo Final &rarr;</span>
                    </a>
                </div>
            </div>

            <!-- Tabla de Premios del Sorteo Final -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-slate-100 text-[10px] font-black uppercase tracking-wider text-slate-400">
                            <th class="pb-3 pl-2"># Orden</th>
                            <th class="pb-3">Título del Sorteo</th>
                            <th class="pb-3">Descripción del Premio</th>
                            <th class="pb-3">Estado / Ganador</th>
                            <th class="pb-3 text-right pr-2">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($draws as $index => $d)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="py-3.5 pl-2 font-mono font-bold text-slate-400">
                                    {{ $index + 1 }}º
                                </td>
                                <td class="py-3.5 font-bold text-slate-900">
                                    {{ $d->title }}
                                </td>
                                <td class="py-3.5 font-semibold text-slate-700">
                                    {{ $d->prize_description }}
                                </td>
                                <td class="py-3.5">
                                    @if($d->status === 'completed' && $d->winner)
                                        <div class="inline-flex flex-col">
                                            <span class="inline-flex items-center gap-1 text-[11px] font-black text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-lg">
                                                <span>✓ Ganador: {{ $d->winner->full_name }}</span>
                                            </span>
                                            <span class="text-[10px] text-slate-400 mt-0.5 font-mono">
                                                {{ $d->winner->city }} • {{ $d->draw_date?->format('d/m H:i') }}
                                            </span>
                                        </div>
                                    @else
                                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-amber-700 bg-amber-50 border border-amber-200 px-2 py-0.5 rounded-lg">
                                            <span>🟡 Pendiente de sorteo</span>
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 text-right pr-2">
                                    <div class="inline-flex items-center gap-1">
                                        <button type="button" 
                                                @click="editingDraw = { id: {{ $d->id }}, title: '{{ addslashes($d->title) }}', prize_description: '{{ addslashes($d->prize_description) }}' }"
                                                class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-[11px] transition cursor-pointer">
                                            Editar
                                        </button>
                                        <form action="{{ route('expo.draw.destroy', ['slug' => $stand->slug, 'drawId' => $d->id]) }}" method="POST" class="inline"
                                              onsubmit="return confirm('¿Seguro que deseas eliminar este premio del sorteo final?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="p-1 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition cursor-pointer"
                                                    title="Eliminar Premio">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-6 text-center text-slate-400">
                                    No hay premios configurados aún para el sorteo final. Haz clic en <strong>+ Agregar Premio de Sorteo</strong>.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- TABLA DE VISITANTES Y LEADS CAPTADOS EN TIEMPO REAL -->
        <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-base font-black text-slate-900">Últimos Visitantes Registrados</h2>
                    <p class="text-xs text-slate-500">Contactos captados con WhatsApp verificado y perfil agronómico/comercial.</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center px-3 py-1 rounded-xl text-xs font-bold bg-slate-100 text-slate-700">
                        {{ $leads->total() }} registrados en total
                    </span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100">
                    <thead>
                        <tr class="bg-slate-50/70 text-left text-[11px] font-black text-slate-400 uppercase tracking-wider">
                            <th class="px-6 py-3.5">Fecha/Hora</th>
                            <th class="px-6 py-3.5">Nombre Completo</th>
                            <th class="px-6 py-3.5">WhatsApp</th>
                            <th class="px-6 py-3.5">Ciudad</th>
                            <th class="px-6 py-3.5">Perfil</th>
                            <th class="px-6 py-3.5">Hectáreas</th>
                            <th class="px-6 py-3.5 text-center">Premio Ruleta</th>
                            <th class="px-6 py-3.5 text-center">Estado Cupón</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs font-semibold text-slate-700">
                        @forelse($leads as $lead)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-slate-400 font-mono text-[11px]">
                                    {{ $lead->created_at->format('d/m H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-bold text-slate-900">
                                    {{ $lead->full_name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-mono text-slate-600">
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $lead->phone) }}" target="_blank" class="hover:text-emerald-600 inline-flex items-center gap-1">
                                        <span>💬 {{ $lead->phone }}</span>
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-slate-600">
                                    {{ $lead->city }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-0.5 rounded-lg bg-slate-100 text-slate-700 text-[11px]">
                                        {{ $lead->profile }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-mono text-slate-500">
                                    {{ $lead->hectares ?: '—' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($lead->spin && $lead->spin->prize)
                                        <span class="inline-flex items-center gap-1 font-bold text-slate-900">
                                            🎁 {{ $lead->spin->prize->name }}
                                        </span>
                                    @else
                                        <span class="text-slate-400 text-[11px]">No giró</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($lead->spin)
                                        @if($lead->spin->is_redeemed)
                                            <span class="px-2.5 py-1 rounded-xl text-[10px] font-black uppercase bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                ✓ Canjeado
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 rounded-xl text-[10px] font-black uppercase bg-amber-50 text-amber-700 border border-amber-200 font-mono">
                                                {{ $lead->spin->coupon_code }}
                                            </span>
                                        @endif
                                    @else
                                        <span class="text-slate-300">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center text-slate-400 font-medium">
                                    Aún no hay visitantes registrados en este stand. Abre el enlace "📱 QR Mostrador" para comenzar a captar leads.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($leads->hasPages())
                <div class="p-4 border-t border-slate-100">
                    {{ $leads->links() }}
                </div>
            @endif
        </div>

        <!-- ZONA DE MANTENIMIENTO: REINICIAR STAND PARA NUEVO EVENTO / FERIA -->
        <div class="bg-gradient-to-r from-slate-50 to-amber-50/40 rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
            <div class="space-y-1.5 max-w-2xl">
                <div class="flex items-center gap-2">
                    <span class="w-8 h-8 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center text-sm font-bold">🔄</span>
                    <h3 class="text-base font-black text-slate-900 tracking-tight">Preparar Stand para un Nuevo Evento</h3>
                </div>
                <p class="text-xs text-slate-600 leading-relaxed">
                    ¿Vas a reutilizar este stand en una nueva feria o edición comercial? Asegúrate de <strong>descargar primero el Excel</strong> con los datos captados. Luego puedes reiniciar el padrón para vaciar los leads, reponer el stock inicial de premios y dejar el stand listo desde cero.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-3 shrink-0">
                <a href="{{ route('expo.export', $stand->slug) }}" 
                   class="px-4 py-2.5 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold text-xs shadow-2xs transition flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Descargar Respaldo Excel</span>
                </a>

                <button type="button" @click="showResetModal = true"
                        class="px-4 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs shadow-sm transition flex items-center gap-1.5 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    <span>Reiniciar para Nuevo Evento</span>
                </button>
            </div>
        </div>

        <!-- MODAL: CONFIRMACIÓN DE RESETEO DE STAND PARA NUEVA FERIA -->
        <div x-show="showResetModal" 
             x-cloak 
             class="fixed inset-0 z-50 overflow-y-auto" 
             role="dialog" 
             aria-modal="true">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" @click="showResetModal = false"></div>

            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative w-full max-w-md bg-white rounded-3xl p-6 sm:p-8 shadow-2xl border border-slate-100 space-y-5"
                     @click.stop>
                    
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <span class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-sm font-bold">⚠️</span>
                            <h3 class="text-base font-black text-slate-900">Reiniciar Stand para Nueva Feria</h3>
                        </div>
                        <button type="button" @click="showResetModal = false" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center text-sm font-bold cursor-pointer">
                            ✕
                        </button>
                    </div>

                    <div class="p-3.5 rounded-2xl bg-rose-50 border border-rose-100 text-rose-800 text-xs leading-relaxed space-y-1">
                        <p class="font-bold">Esta acción realizará lo siguiente:</p>
                        <ul class="list-disc list-inside space-y-0.5 text-[11px] text-rose-700">
                            <li>Eliminará todos los <strong>contactos / leads</strong> captados en la feria actual.</li>
                            <li>Eliminará el historial de giros de ruleta y cupones canjeados.</li>
                            <li>Restaurará el <strong>stock de premios</strong> a sus valores iniciales.</li>
                            <li>Reiniciará los sorteos finalizados para volver a sortear.</li>
                        </ul>
                    </div>

                    <form action="{{ route('expo.stand.reset', $stand->slug) }}" method="POST" class="space-y-4 text-xs">
                        @csrf
                        <div>
                            <label class="block text-[11px] font-black uppercase tracking-wider text-slate-500 mb-1">
                                Para confirmar, escribe la palabra <strong class="text-rose-600">REINICIAR</strong>:
                            </label>
                            <input type="text" name="confirm_text" required placeholder="REINICIAR"
                                   class="w-full text-xs font-mono font-bold uppercase rounded-xl border-slate-200 focus:border-rose-500 focus:ring-rose-500 py-2.5 px-3">
                        </div>

                        <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-3">
                            <button type="button" @click="showResetModal = false" class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold hover:bg-slate-50 transition cursor-pointer">
                                Cancelar
                            </button>
                            <button type="submit" class="px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold shadow-md shadow-rose-600/20 transition cursor-pointer">
                                Sí, Reiniciar Stand
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- MODAL: AGREGAR NUEVO PREMIO / SECTOR A LA RULETA -->
        <div x-show="showNewPrizeModal" 
             x-cloak 
             class="fixed inset-0 z-50 overflow-y-auto" 
             role="dialog" 
             aria-modal="true">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" @click="showNewPrizeModal = false"></div>

            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative w-full max-w-md bg-white rounded-3xl p-6 sm:p-8 shadow-2xl border border-slate-100 space-y-6"
                     @click.stop>
                    
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <div>
                            <span class="text-[10px] font-black uppercase tracking-wider text-gold-700">Ruleta de la Suerte</span>
                            <h3 class="text-lg font-black text-slate-900">Agregar Premio / Sector</h3>
                        </div>
                        <button type="button" @click="showNewPrizeModal = false" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center text-sm font-bold cursor-pointer">
                            ✕
                        </button>
                    </div>

                    <form action="{{ route('expo.prize.store', $stand->slug) }}" method="POST" class="space-y-4 text-xs">
                        @csrf

                        <div>
                            <label class="block text-[11px] font-black uppercase tracking-wider text-slate-400 mb-1">Nombre del Premio o Sector *</label>
                            <input type="text" name="name" required placeholder="Ej. Mochila Oficial, o ¡Seguí Participando!"
                                   class="w-full text-xs font-bold rounded-xl border-slate-200 focus:border-gold-500 focus:ring-gold-500 py-2.5 px-3">
                            <p class="text-[10px] text-slate-400 mt-1">Si escribes 'Seguí Participando', no generará cupón de canje en mostrador.</p>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[11px] font-black uppercase tracking-wider text-slate-400 mb-1">Stock Inicial *</label>
                                <input type="number" name="remaining_stock" value="20" min="0" required
                                       class="w-full text-xs font-mono font-bold rounded-xl border-slate-200 focus:border-gold-500 focus:ring-gold-500 py-2.5 px-3">
                                <span class="text-[10px] text-slate-400">Usa 9999 para consuelo.</span>
                            </div>
                            <div>
                                <label class="block text-[11px] font-black uppercase tracking-wider text-slate-400 mb-1">Probabilidad / Peso (%) *</label>
                                <input type="number" name="probability_weight" value="15" min="0" max="100" required
                                       class="w-full text-xs font-mono font-bold rounded-xl border-slate-200 focus:border-gold-500 focus:ring-gold-500 py-2.5 px-3">
                                <span class="text-[10px] text-slate-400">Mayor peso = más chances.</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[11px] font-black uppercase tracking-wider text-slate-400 mb-1">Color del Sector</label>
                                <div class="flex items-center gap-2">
                                    @php
                                        $palette = ['#4f46e5', '#d97706', '#059669', '#dc2626', '#7c3aed', '#0891b2', '#ea580c', '#475569'];
                                        $nextColor = $palette[$prizes->count() % count($palette)];
                                    @endphp
                                    <input type="color" name="color" value="{{ $nextColor }}" class="h-9 w-12 rounded-xl border border-slate-200 cursor-pointer p-0.5">
                                    <span class="text-[11px] text-slate-500 font-mono">Selector</span>
                                </div>
                            </div>
                            <div>
                                <label class="block text-[11px] font-black uppercase tracking-wider text-slate-400 mb-1">Tipo de Sector</label>
                                <select name="is_real_prize" class="w-full text-xs font-bold rounded-xl border-slate-200 focus:border-gold-500 py-2 px-2.5">
                                    <option value="1">Premio Real (Con Cupón)</option>
                                    <option value="0">Consuelo (Seguí Participando)</option>
                                </select>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                            <button type="button" @click="showNewPrizeModal = false" class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold hover:bg-slate-50 transition cursor-pointer">
                                Cancelar
                            </button>
                            <button type="submit" class="px-5 py-2.5 rounded-xl bg-gold-500 hover:bg-gold-600 text-white font-bold shadow-md transition cursor-pointer">
                                + Agregar a la Ruleta
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- MODAL: AGREGAR NUEVO PREMIO AL SORTEO FINAL -->
        <div x-show="showNewDrawModal" 
             x-cloak
             class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-100 relative"
                 @click.away="showNewDrawModal = false">
                
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-700 flex items-center justify-center font-bold text-sm">
                            🎁
                        </div>
                        <h3 class="text-base font-black text-slate-900">Nuevo Premio de Sorteo Final</h3>
                    </div>
                    <button type="button" @click="showNewDrawModal = false" class="text-slate-400 hover:text-slate-600 p-1">
                        ✕
                    </button>
                </div>

                <form action="{{ route('expo.draw.store', $stand->slug) }}" method="POST" class="space-y-4 text-xs">
                    @csrf
                    <div>
                        <label class="block text-[11px] font-black uppercase tracking-wider text-slate-400 mb-1">Título del Sorteo *</label>
                        <input type="text" name="title" required placeholder="Ej. 1º Gran Premio, o 2º Premio Especial"
                               class="w-full text-xs font-bold rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                    </div>

                    <div>
                        <label class="block text-[11px] font-black uppercase tracking-wider text-slate-400 mb-1">Descripción del Premio *</label>
                        <input type="text" name="prize_description" required placeholder="Ej. Smart TV 65 pulgadas 4K UHD, o Kit de Herramientas"
                               class="w-full text-xs font-bold rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                        <button type="button" @click="showNewDrawModal = false" class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold hover:bg-slate-50 transition cursor-pointer">
                            Cancelar
                        </button>
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold shadow-md transition cursor-pointer">
                            + Guardar Premio
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL: EDITAR PREMIO DE SORTEO FINAL -->
        <div x-show="editingDraw !== null" 
             x-cloak
             class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-100 relative"
                 @click.away="editingDraw = null">
                
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-700 flex items-center justify-center font-bold text-sm">
                            ✏️
                        </div>
                        <h3 class="text-base font-black text-slate-900">Editar Premio de Sorteo Final</h3>
                    </div>
                    <button type="button" @click="editingDraw = null" class="text-slate-400 hover:text-slate-600 p-1">
                        ✕
                    </button>
                </div>

                <form :action="'{{ url('/panel/' . $stand->slug . '/sorteos') }}/' + (editingDraw ? editingDraw.id : '')" method="POST" class="space-y-4 text-xs">
                    @csrf
                    <div>
                        <label class="block text-[11px] font-black uppercase tracking-wider text-slate-400 mb-1">Título del Sorteo *</label>
                        <input type="text" name="title" x-model="editingDraw.title" required
                               class="w-full text-xs font-bold rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                    </div>

                    <div>
                        <label class="block text-[11px] font-black uppercase tracking-wider text-slate-400 mb-1">Descripción del Premio *</label>
                        <input type="text" name="prize_description" x-model="editingDraw.prize_description" required
                               class="w-full text-xs font-bold rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                        <button type="button" @click="editingDraw = null" class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold hover:bg-slate-50 transition cursor-pointer">
                            Cancelar
                        </button>
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold shadow-md transition cursor-pointer">
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
