<x-guest-layout>
    <x-slot:title>Configurar Stand y Elegir Plan B2B | SotePass Expo</x-slot>
    <x-slot:robots>noindex, nofollow</x-slot>

    <div class="min-h-screen bg-[#f8f9fa] py-10 px-4 sm:px-6 lg:px-8 antialiased font-sans text-slate-800">
        
        <div class="max-w-5xl mx-auto space-y-8" x-data="{ selectedPlan: {{ $initialPlanId ?? ($plans->firstWhere('slug', 'stand-pro-ruleta-touch')?->id ?? $plans->first()?->id) }} }">
            
            <!-- Header Onboarding -->
            <div class="text-center max-w-2xl mx-auto">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-md bg-emerald-50 border border-emerald-200 text-xs font-semibold text-emerald-800 mb-3 shadow-xs">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Paso 2 de 2: Activación de Prueba Gratuita</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">
                    Configura tu Stand para la Expo
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1 font-normal">
                    Hola <strong>{{ $user->name }}</strong> ({{ $user->company_name }}). Configura tu stand para habilitar tu acceso de prueba gratuita al sistema.
                </p>
            </div>

            <!-- Aviso de Prueba Gratuita Sin Compromiso -->
            <div class="p-4 sm:p-5 rounded-lg bg-amber-50/90 border border-amber-300 text-amber-950 flex items-start gap-3.5 shadow-xs">
                <div class="w-9 h-9 rounded-md bg-amber-200/70 border border-amber-300 text-amber-900 flex items-center justify-center font-bold text-base shrink-0">
                    🧪
                </div>
                <div class="text-xs leading-relaxed">
                    <div class="font-bold uppercase tracking-wider text-amber-900">
                        Prueba Gratuita Habilitada &bull; Sin Compromiso de Contratación
                    </div>
                    <p class="text-amber-800 mt-0.5">
                        Al completar este formulario se habilitará tu <strong>Modo Demo Gratuito</strong> para que pruebes el panel, la ruleta y el registro de visitantes. <strong>La elección de un plan aquí no implica compromiso de pago ni contratación obligatoria del servicio</strong>; sirve únicamente para activar las funciones que deseas evaluar. Solo abonarás si decides activar tu stand oficialmente para la feria.
                    </p>
                </div>
            </div>

            @if(session('success'))
                <div class="p-3.5 rounded-md bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center gap-2 shadow-xs">
                    <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="p-3.5 rounded-md bg-rose-50 border border-rose-200 text-rose-800 text-xs font-medium space-y-1 shadow-xs">
                    <p class="font-bold">Por favor revisa los siguientes campos:</p>
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('stands.store') }}" class="space-y-6">
                @csrf

                <!-- SECCIÓN 1: SELECCIÓN DE PLAN -->
                <div>
                    <div class="mb-3">
                        <h2 class="text-base font-bold text-slate-900">1. Elige qué Plan deseas probar (Sin compromiso de pago)</h2>
                        <p class="text-xs text-slate-500">Selecciona el paquete cuyas funciones quieres habilitar en tu prueba gratuita. Puedes cambiar de plan en cualquier momento.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach($plans as $plan)
                            <div @click="selectedPlan = {{ $plan->id }}"
                                 :class="selectedPlan === {{ $plan->id }} ? 'ring-1 ring-emerald-600 border-emerald-600 bg-white shadow-xs' : 'border-slate-200 bg-white hover:border-slate-300 shadow-2xs'"
                                 class="rounded-lg p-5 border transition-all duration-150 cursor-pointer flex flex-col justify-between relative overflow-hidden">
                                
                                <div>
                                    <div class="flex items-center justify-between gap-2 mb-2.5">
                                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">{{ $plan->name }}</span>
                                        @if($plan->badge_text)
                                            <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold uppercase tracking-wider bg-emerald-50 text-emerald-800 border border-emerald-200">
                                                {{ $plan->badge_text }}
                                            </span>
                                        @endif
                                    </div>

                                    <div class="mb-3">
                                        <div class="text-2xl font-bold text-slate-900 tracking-tight">
                                            {{ $plan->formatted_price }}
                                        </div>
                                        <p class="text-[11px] text-slate-400 font-normal mt-0.5">Tarifa plana por toda la expo</p>
                                    </div>

                                    <p class="text-xs text-slate-600 mb-4 leading-relaxed font-normal">
                                        {{ $plan->description }}
                                    </p>

                                    <!-- Features List -->
                                    <div class="space-y-2 pt-3 border-t border-slate-100">
                                        @foreach(($plan->features ?? []) as $feat)
                                            <div class="flex items-start gap-2 text-xs text-slate-700">
                                                <svg class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                <span>{{ $feat }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="mt-5 pt-3 border-t border-slate-100">
                                    <div class="flex items-center gap-2">
                                        <input type="radio" name="plan_id" value="{{ $plan->id }}" 
                                               :checked="selectedPlan === {{ $plan->id }}"
                                               class="w-4 h-4 text-emerald-600 focus:ring-emerald-500 border-slate-300">
                                        <span class="text-xs font-semibold" :class="selectedPlan === {{ $plan->id }} ? 'text-emerald-700' : 'text-slate-500'">
                                            Probar este Plan (Sin compromiso)
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- SECCIÓN 2: INFORMACIÓN DEL STAND -->
                <div class="bg-white rounded-lg p-6 sm:p-7 border border-slate-200 shadow-xs space-y-5">
                    <div>
                        <h2 class="text-base font-bold text-slate-900">2. Datos de Identidad del Stand</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Esta información aparecerá en la landing que verán los visitantes al escanear el QR y en la ruleta.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        <!-- Nombre del Stand -->
                        <div>
                            <x-input-label value="Nombre de la Empresa o Marca *" />
                            <x-text-input type="text" name="name" :value="old('name', $user->company_name)" required placeholder="Ej. SotePass Expo" class="w-full" />
                        </div>

                        <!-- Slug URL -->
                        <div>
                            <x-input-label value="URL Slug del Stand (Único, sin espacios) *" />
                            <div class="flex rounded-md shadow-2xs">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-slate-300 bg-slate-50 text-slate-500 font-mono text-xs font-medium">
                                    /stand/
                                </span>
                                <input type="text" name="slug" value="{{ old('slug', \Illuminate\Support\Str::slug($user->company_name)) }}" required placeholder="miempresa"
                                       class="flex-1 px-3 py-2 bg-white text-xs font-mono font-medium text-slate-900 border border-slate-300 rounded-none rounded-r-md focus:ring-1 focus:ring-slate-900 focus:border-slate-900 transition">
                            </div>
                        </div>

                        <!-- Número de Stand -->
                        <div>
                            <x-input-label value="Número / Ubicación del Stand *" />
                            <x-text-input type="text" name="stand_number" :value="old('stand_number')" required placeholder="Ej. Stand 45 - Pabellón Maquinarias" class="w-full" />
                        </div>

                        <!-- Sector / Rubro -->
                        <div>
                            <x-input-label value="Sector Comercial / Rubro *" />
                            <x-text-input type="text" name="sector" :value="old('sector')" required placeholder="Ej. Maquinarias, Repuestos y Servicios" class="w-full" />
                        </div>

                        <!-- Color Corporativo -->
                        <div class="sm:col-span-2" x-data="{ selectedColor: '{{ old('primary_color', '#059669') }}' }">
                            <x-input-label value="Color Corporativo Principal *" />
                            <div class="flex gap-2 max-w-xs">
                                <input type="color" name="primary_color" x-model="selectedColor"
                                       class="h-9 w-12 rounded-md bg-slate-50 border border-slate-300 cursor-pointer p-0.5">
                                <x-text-input type="text" name="primary_color_text" x-model="selectedColor" readonly class="flex-1 font-mono text-xs" />
                            </div>

                            <!-- Paleta de Colores Sugerida -->
                            <div class="mt-2.5">
                                <span class="text-[10px] font-semibold uppercase tracking-wider text-slate-400 block mb-1.5">Paleta de colores:</span>
                                <div class="flex flex-wrap gap-1.5">
                                    <button type="button" @click="selectedColor = '#16a34a'" class="px-2 py-1 rounded-md border border-slate-200 bg-white hover:bg-slate-50 flex items-center gap-1.5 font-semibold text-[10px] text-slate-700 transition shadow-2xs hover:border-slate-300 cursor-pointer">
                                        <span class="w-2.5 h-2.5 rounded-xs bg-[#16a34a]"></span>
                                        <span>Verde Esmeralda</span>
                                    </button>
                                    <button type="button" @click="selectedColor = '#65a30d'" class="px-2 py-1 rounded-md border border-slate-200 bg-white hover:bg-slate-50 flex items-center gap-1.5 font-semibold text-[10px] text-slate-700 transition shadow-2xs hover:border-slate-300 cursor-pointer">
                                        <span class="w-2.5 h-2.5 rounded-xs bg-[#65a30d]"></span>
                                        <span>Verde Pradera</span>
                                    </button>
                                    <button type="button" @click="selectedColor = '#059669'" class="px-2 py-1 rounded-md border border-slate-200 bg-white hover:bg-slate-50 flex items-center gap-1.5 font-semibold text-[10px] text-slate-700 transition shadow-2xs hover:border-slate-300 cursor-pointer">
                                        <span class="w-2.5 h-2.5 rounded-xs bg-[#059669]"></span>
                                        <span>Verde Botánico</span>
                                    </button>
                                    <button type="button" @click="selectedColor = '#0284c7'" class="px-2 py-1 rounded-md border border-slate-200 bg-white hover:bg-slate-50 flex items-center gap-1.5 font-semibold text-[10px] text-slate-700 transition shadow-2xs hover:border-slate-300 cursor-pointer">
                                        <span class="w-2.5 h-2.5 rounded-xs bg-[#0284c7]"></span>
                                        <span>Azul Océano</span>
                                    </button>
                                    <button type="button" @click="selectedColor = '#1d4ed8'" class="px-2 py-1 rounded-md border border-slate-200 bg-white hover:bg-slate-50 flex items-center gap-1.5 font-semibold text-[10px] text-slate-700 transition shadow-2xs hover:border-slate-300 cursor-pointer">
                                        <span class="w-2.5 h-2.5 rounded-xs bg-[#1d4ed8]"></span>
                                        <span>Azul Real</span>
                                    </button>
                                    <button type="button" @click="selectedColor = '#003399'" class="px-2 py-1 rounded-md border border-slate-200 bg-white hover:bg-slate-50 flex items-center gap-1.5 font-semibold text-[10px] text-slate-700 transition shadow-2xs hover:border-slate-300 cursor-pointer">
                                        <span class="w-2.5 h-2.5 rounded-xs bg-[#003399]"></span>
                                        <span>Azul Marino</span>
                                    </button>
                                    <button type="button" @click="selectedColor = '#4338ca'" class="px-2 py-1 rounded-md border border-slate-200 bg-white hover:bg-slate-50 flex items-center gap-1.5 font-semibold text-[10px] text-slate-700 transition shadow-2xs hover:border-slate-300 cursor-pointer">
                                        <span class="w-2.5 h-2.5 rounded-xs bg-[#4338ca]"></span>
                                        <span>Índigo Ejecutivo</span>
                                    </button>
                                    <button type="button" @click="selectedColor = '#dc2626'" class="px-2 py-1 rounded-md border border-slate-200 bg-white hover:bg-slate-50 flex items-center gap-1.5 font-semibold text-[10px] text-slate-700 transition shadow-2xs hover:border-slate-300 cursor-pointer">
                                        <span class="w-2.5 h-2.5 rounded-xs bg-[#dc2626]"></span>
                                        <span>Rojo Escarlata</span>
                                    </button>
                                    <button type="button" @click="selectedColor = '#b91c1c'" class="px-2 py-1 rounded-md border border-slate-200 bg-white hover:bg-slate-50 flex items-center gap-1.5 font-semibold text-[10px] text-slate-700 transition shadow-2xs hover:border-slate-300 cursor-pointer">
                                        <span class="w-2.5 h-2.5 rounded-xs bg-[#b91c1c]"></span>
                                        <span>Rojo Carmesí</span>
                                    </button>
                                    <button type="button" @click="selectedColor = '#ea580c'" class="px-2 py-1 rounded-md border border-slate-200 bg-white hover:bg-slate-50 flex items-center gap-1.5 font-semibold text-[10px] text-slate-700 transition shadow-2xs hover:border-slate-300 cursor-pointer">
                                        <span class="w-2.5 h-2.5 rounded-xs bg-[#ea580c]"></span>
                                        <span>Naranja Ámbar</span>
                                    </button>
                                    <button type="button" @click="selectedColor = '#d97706'" class="px-2 py-1 rounded-md border border-slate-200 bg-white hover:bg-slate-50 flex items-center gap-1.5 font-semibold text-[10px] text-slate-700 transition shadow-2xs hover:border-slate-300 cursor-pointer">
                                        <span class="w-2.5 h-2.5 rounded-xs bg-[#d97706]"></span>
                                        <span>Dorado Solar</span>
                                    </button>
                                    <button type="button" @click="selectedColor = '#0d9488'" class="px-2 py-1 rounded-md border border-slate-200 bg-white hover:bg-slate-50 flex items-center gap-1.5 font-semibold text-[10px] text-slate-700 transition shadow-2xs hover:border-slate-300 cursor-pointer">
                                        <span class="w-2.5 h-2.5 rounded-xs bg-[#0d9488]"></span>
                                        <span>Verde Petróleo</span>
                                    </button>
                                    <button type="button" @click="selectedColor = '#7c3aed'" class="px-2 py-1 rounded-md border border-slate-200 bg-white hover:bg-slate-50 flex items-center gap-1.5 font-semibold text-[10px] text-slate-700 transition shadow-2xs hover:border-slate-300 cursor-pointer">
                                        <span class="w-2.5 h-2.5 rounded-xs bg-[#7c3aed]"></span>
                                        <span>Púrpura Noble</span>
                                    </button>
                                    <button type="button" @click="selectedColor = '#334155'" class="px-2 py-1 rounded-md border border-slate-200 bg-white hover:bg-slate-50 flex items-center gap-1.5 font-semibold text-[10px] text-slate-700 transition shadow-2xs hover:border-slate-300 cursor-pointer">
                                        <span class="w-2.5 h-2.5 rounded-xs bg-[#334155]"></span>
                                        <span>Grafito Titán</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- PIN Mostrador -->
                        <div class="sm:col-span-2">
                            <x-input-label value="PIN de Seguridad del Mostrador (4 dígitos) *" />
                            <x-text-input type="text" name="access_pin" value="{{ old('access_pin', '1234') }}" required placeholder="1234" class="w-full font-mono text-xs" />
                            <p class="text-[10px] text-slate-400 mt-1">Sirve para que tus promotoras validen cupones y descarguen el Excel.</p>
                        </div>
                    </div>

                    <!-- Datos del Evento / Feria -->
                    <div class="pt-4 border-t border-slate-100">
                        <div class="mb-3">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700">3. Datos de la Feria / Evento</h3>
                            <p class="text-[11px] text-slate-400">Define el nombre y las fechas de duración de la feria para la vigencia del servicio.</p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                            <div>
                                <x-input-label value="Nombre de la Feria / Evento *" />
                                <x-text-input type="text" name="event_name" :value="old('event_name', 'Expo Paraguay 2026')" required placeholder="Ej. Expo Paraguay 2026" class="w-full" />
                            </div>
                            <div>
                                <x-input-label value="Fecha de Inicio de la Feria" />
                                <x-text-input type="date" name="event_start_date" :value="old('event_start_date')" class="w-full font-mono text-xs" />
                            </div>
                            <div>
                                <x-input-label value="Fecha de Finalización *" />
                                <x-text-input type="date" name="event_end_date" :value="old('event_end_date')" class="w-full font-mono text-xs" />
                            </div>
                        </div>
                    </div>

                    <div class="pt-2">
                        <x-input-label value="Eslogan o Mensaje de Bienvenida (Opcional)" />
                        <x-text-input type="text" name="tagline" :value="old('tagline')" placeholder="Ej. Innovación y tecnología para los visitantes de la Expo." class="w-full" />
                    </div>

                    <div class="pt-3 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3">
                        <a href="{{ route('expo.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800 transition-colors">
                            &larr; Volver al Portal Principal
                        </a>
                        <button type="submit" 
                                class="w-full sm:w-auto px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs uppercase tracking-wider rounded-md shadow-xs transition-all active:scale-[0.99] cursor-pointer border border-emerald-500">
                            Comenzar Prueba Gratuita y Entrar al Panel &rarr;
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
