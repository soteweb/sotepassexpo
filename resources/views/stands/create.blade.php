<x-guest-layout>
    <x-slot:title>Configurar Mi Stand & Elegir Plan | SotePass Expo</x-slot>

    <div class="min-h-screen bg-[#f8f7f5] py-10 px-4 sm:px-6 lg:px-8 antialiased">
        
        <div class="max-w-5xl mx-auto space-y-8" x-data="{ selectedPlan: {{ $plans->firstWhere('slug', 'stand-pro-ruleta-touch')?->id ?? $plans->first()?->id }} }">
            
            <!-- Header Onboarding -->
            <div class="text-center max-w-2xl mx-auto">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-gold-50 border border-gold-200 text-xs font-bold text-gold-800 mb-3">
                    <span class="w-2 h-2 rounded-full bg-gold-500 animate-pulse"></span>
                    <span>Paso 2 de 2: Alta de Stand y Selección de Plan</span>
                </div>
                <h1 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">
                    Configura tu Stand para la Expo
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-2 font-medium">
                    Hola <strong>{{ $user->name }}</strong> ({{ $user->company_name }}). Elige el paquete que mejor se adapta a tus objetivos comerciales durante la feria.
                </p>
            </div>

            @if(session('success'))
                <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold space-y-1">
                    <p class="font-black">Por favor revisa los siguientes campos:</p>
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('stands.store') }}" class="space-y-8">
                @csrf

                <!-- SECCIÓN 1: SELECCIÓN DE PLAN -->
                <div>
                    <div class="mb-4">
                        <h2 class="text-lg font-black text-slate-900">1. Elige tu Plan de Servicio</h2>
                        <p class="text-xs text-slate-500">Puedes cambiar o mejorar tu plan en cualquier momento comunicándote con Soteweb.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        @foreach($plans as $plan)
                            <div @click="selectedPlan = {{ $plan->id }}"
                                 :class="selectedPlan === {{ $plan->id }} ? 'ring-2 ring-gold-500 border-gold-500 bg-white shadow-xl scale-[1.02]' : 'border-slate-200 bg-white/70 hover:bg-white hover:border-slate-300 shadow-sm'"
                                 class="rounded-3xl p-6 border transition-all duration-200 cursor-pointer flex flex-col justify-between relative overflow-hidden">
                                
                                <div>
                                    <div class="flex items-center justify-between gap-2 mb-3">
                                        <span class="text-xs font-black uppercase tracking-wider text-slate-500">{{ $plan->name }}</span>
                                        @if($plan->badge_text)
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-gold-50 text-gold-800 border border-gold-200">
                                                {{ $plan->badge_text }}
                                            </span>
                                        @endif
                                    </div>

                                    <div class="mb-4">
                                        <div class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                                            {{ $plan->formatted_price }}
                                        </div>
                                        <p class="text-[11px] text-slate-400 font-medium mt-0.5">Tarifa plana por toda la expo</p>
                                    </div>

                                    <p class="text-xs text-slate-600 mb-5 leading-relaxed font-medium">
                                        {{ $plan->description }}
                                    </p>

                                    <!-- Features List -->
                                    <div class="space-y-2.5 pt-4 border-t border-slate-100">
                                        @foreach($plan->features as $feat)
                                            <div class="flex items-start gap-2 text-xs text-slate-700">
                                                <svg class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                <span>{{ $feat }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="mt-6 pt-4 border-t border-slate-100">
                                    <div class="flex items-center gap-2">
                                        <input type="radio" name="plan_id" value="{{ $plan->id }}" 
                                               :checked="selectedPlan === {{ $plan->id }}"
                                               class="w-4 h-4 text-gold-600 focus:ring-gold-500 border-slate-300">
                                        <span class="text-xs font-bold" :class="selectedPlan === {{ $plan->id }} ? 'text-gold-700' : 'text-slate-500'">
                                            Seleccionar Plan
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- SECCIÓN 2: INFORMACIÓN DEL STAND -->
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm space-y-6">
                    <div>
                        <h2 class="text-lg font-black text-slate-900">2. Datos de Identidad del Stand</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Esta información aparecerá en la landing que verán los visitantes al escanear el QR y en la ruleta.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 text-xs">
                        <!-- Nombre del Stand -->
                        <div>
                            <x-input-label value="Nombre de la Empresa o Marca *" />
                            <x-text-input type="text" name="name" :value="old('name', $user->company_name)" required placeholder="Ej. Kurosu & Cia. | John Deere" class="w-full" />
                        </div>

                        <!-- Slug URL -->
                        <div>
                            <x-input-label value="URL Slug del Stand (Único, sin espacios) *" />
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 font-mono text-xs">/stand/</span>
                                <input type="text" name="slug" value="{{ old('slug', \Illuminate\Support\Str::slug($user->company_name)) }}" required placeholder="miempresa"
                                       class="w-full pl-16 pr-4 py-2.5 bg-slate-50/50 hover:bg-slate-50 focus:bg-white text-sm text-slate-900 border border-slate-200 rounded-xl focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all font-mono">
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
                        <div>
                            <x-input-label value="Color Corporativo Principal *" />
                            <div class="flex gap-2.5">
                                <input type="color" name="primary_color" value="{{ old('primary_color', '#16a34a') }}"
                                       class="h-10 w-14 rounded-xl bg-slate-50 border border-slate-200 cursor-pointer p-1">
                                <x-text-input type="text" name="primary_color_text" value="{{ old('primary_color', '#16a34a') }}" readonly class="flex-1 font-mono text-xs" />
                            </div>
                        </div>

                        <!-- PIN Mostrador -->
                        <div>
                            <x-input-label value="PIN de Seguridad del Mostrador (4 dígitos) *" />
                            <x-text-input type="text" name="access_pin" value="{{ old('access_pin', '1234') }}" required placeholder="1234" class="w-full font-mono text-xs" />
                            <p class="text-[10px] text-slate-400 mt-1">Sirve para que tus promotoras validen cupones y descarguen el Excel.</p>
                        </div>
                    </div>

                    <div>
                        <x-input-label value="Eslogan o Mensaje de Bienvenida (Opcional)" />
                        <x-text-input type="text" name="tagline" :value="old('tagline')" placeholder="Ej. Innovación y tecnología para el productor del campo paraguayo." class="w-full" />
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <a href="{{ route('expo.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800 transition-colors">
                            &larr; Volver al Portal Principal
                        </a>
                        <button type="submit" 
                                class="w-full sm:w-auto px-8 py-3.5 bg-gold-500 hover:bg-gold-600 text-white font-bold text-xs uppercase tracking-widest rounded-2xl shadow-lg shadow-gold-500/25 transition-all active:scale-95 cursor-pointer">
                            Activar Mi Stand y Entrar al Panel &rarr;
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>

