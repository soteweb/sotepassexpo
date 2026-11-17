<x-guest-layout :stand="$stand">
    <x-slot:title>{{ $stand->name }} | Registro y Acreditación de Visitantes - {{ $stand->event_name ?? 'Expo Paraguay' }}</x-slot>
    <x-slot:description>Acreditación digital de visitantes para el stand de {{ $stand->name }} ({{ $stand->stand_number }}). Registrate en segundos, validá tu WhatsApp y participá por premios exclusivos en {{ $stand->event_name ?? 'la expo' }}.</x-slot>

    @php
        $isDark = in_array(request('theme'), ['light', 'dark'], true)
            ? request('theme') === 'dark'
            : $stand->isDarkMode();
    @endphp

    <div class="min-h-screen flex flex-col justify-center items-center p-4 sm:p-6 antialiased font-sans transition-colors duration-200 {{ $isDark ? 'bg-[#090d16] text-slate-100' : 'bg-[#f8f9fa] text-slate-800' }}">

        <!-- Contenedor Maestro Corporativo: Rectangular con micro-radio de 8px -->
        <div class="w-full max-w-md rounded-lg border overflow-hidden transition-colors duration-200 {{ $isDark ? 'bg-slate-900 border-slate-800 shadow-2xl' : 'bg-white border-slate-200 shadow-sm' }}">
            
            <!-- Cabecera Institucional Sobria -->
            <div class="p-6 border-b {{ $isDark ? 'bg-slate-900 border-slate-800' : 'bg-white border-slate-200' }}" 
                 style="border-top: 4px solid {{ $stand->primary_color }};">
                
                <div class="flex items-start justify-between gap-4">
                    <div class="min-w-0">
                        <!-- Badge de Ubicación: Micro-radio rectangular -->
                        <div class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-[4px] text-[10px] font-semibold uppercase tracking-wider mb-2 border {{ $isDark ? 'bg-slate-800 text-slate-300 border-slate-700' : 'bg-slate-100 text-slate-600 border-slate-200' }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            <span>{{ $stand->stand_number }} &bull; {{ $stand->sector }}</span>
                            @if($isTotem)
                                <span class="font-bold {{ $isDark ? 'text-white' : 'text-slate-900' }}">&bull; TÓTEM</span>
                            @endif
                        </div>

                        <h1 class="text-xl font-bold tracking-tight leading-snug {{ $isDark ? 'text-white' : 'text-slate-900' }}">
                            {{ $stand->name }}
                        </h1>

                        @if($stand->tagline)
                            <p class="text-xs font-normal mt-0.5 {{ $isDark ? 'text-slate-400' : 'text-slate-500' }}">
                                {{ $stand->tagline }}
                            </p>
                        @endif

                        <div class="mt-2">
                            @if($stand->allow_spins)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-[4px] text-[10px] font-semibold uppercase tracking-wider border {{ $isDark ? 'bg-emerald-950/80 text-emerald-300 border-emerald-800/80' : 'bg-emerald-50 text-emerald-700 border-emerald-200' }}">
                                    <span>Ruleta de Premios Activa</span>
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-[4px] text-[10px] font-semibold uppercase tracking-wider border {{ $isDark ? 'bg-purple-950/80 text-purple-300 border-purple-800/80' : 'bg-purple-50 text-purple-700 border-purple-200' }}">
                                    <span>Gran Sorteo en TV 4K</span>
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Logo Corporativo en Marco Cuadrado Limpio -->
                    @if($stand->logo_url)
                        <div class="w-14 h-14 rounded-md bg-white p-1.5 border {{ $isDark ? 'border-slate-700' : 'border-slate-200' }} shrink-0 flex items-center justify-center">
                            <img src="{{ $stand->logo_url }}" alt="{{ $stand->name }}" class="max-w-full max-h-full object-contain">
                        </div>
                    @else
                        <div class="w-12 h-12 rounded-md flex items-center justify-center font-bold text-white text-base shrink-0"
                             style="background-color: {{ $stand->primary_color }};">
                            {{ strtoupper(substr($stand->slug, 0, 2)) }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Cuerpo del Formulario -->
            <div class="p-6 space-y-5 {{ $isDark ? 'bg-slate-900' : 'bg-white' }}"
                 x-data="{ 
                     isPromotor: false, 
                     promoterName: localStorage.getItem('promoter_name') || '',
                     init() {
                         const urlParams = new URLSearchParams(window.location.search);
                         if (urlParams.get('promotor') === '0') {
                             localStorage.removeItem('promoter_mode_{{ $stand->slug }}');
                             localStorage.removeItem('promoter_name');
                             this.isPromotor = false;
                             this.promoterName = '';
                         } else if (urlParams.has('promotor') && urlParams.get('promotor') !== '0') {
                             localStorage.setItem('promoter_mode_{{ $stand->slug }}', '1');
                             this.isPromotor = true;
                         } else if (localStorage.getItem('promoter_mode_{{ $stand->slug }}') === '1') {
                             this.isPromotor = true;
                         }
                     },
                     disablePromoter() {
                         localStorage.removeItem('promoter_mode_{{ $stand->slug }}');
                         localStorage.removeItem('promoter_name');
                         this.isPromotor = false;
                         this.promoterName = '';
                     }
                 }">
                
                <!-- Título de Acción -->
                <div class="pb-1 border-b {{ $isDark ? 'border-slate-800' : 'border-slate-100' }}">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold uppercase tracking-wider {{ $isDark ? 'text-slate-100' : 'text-slate-900' }}">
                            {{ $stand->allow_spins ? 'Registro & Acceso a Ruleta' : 'Acreditación Oficial de Stand' }}
                        </span>
                        <span class="text-[10px] font-medium {{ $isDark ? 'text-slate-500' : 'text-slate-400' }}">Paso 1 de 2</span>
                    </div>
                    <p class="text-xs mt-0.5 {{ $isDark ? 'text-slate-400' : 'text-slate-500' }}">
                        Complete sus datos para emitir su pase digital certificado.
                    </p>
                </div>

                <form action="{{ route('expo.register', ['slug' => $stand->slug, 'totem' => $isTotem ? 1 : null]) }}" method="POST" class="space-y-4">
                    @csrf

                    <!-- Bloque Exclusivo de Promotor (100% Oculto en Autoregistro de Visitantes; Activo solo con ?promotor=1) -->
                    <div x-show="isPromotor" x-cloak class="p-3 rounded-md border space-y-1.5 {{ $isDark ? 'bg-slate-950/70 border-slate-800' : 'bg-emerald-50/60 border-emerald-200' }}">
                        <div class="flex items-center justify-between">
                            <label class="block text-[11px] font-bold {{ $isDark ? 'text-emerald-400' : 'text-emerald-900' }}">
                                👤 Modo Promotor/a Activo
                            </label>
                            <button type="button" @click="disablePromoter()"
                                    class="text-[10px] font-semibold underline cursor-pointer {{ $isDark ? 'text-slate-400 hover:text-rose-400' : 'text-slate-500 hover:text-rose-600' }}">
                                Salir de modo promotor
                            </button>
                        </div>
                        <input type="text" name="promoter_name" x-model="promoterName" :disabled="!isPromotor"
                               @input="localStorage.setItem('promoter_name', $event.target.value)"
                               placeholder="Ingresa tu nombre de Promotor/a (Ej. Camila)"
                               class="w-full px-3 py-1.5 text-xs font-medium border rounded-[4px] transition {{ $isDark ? 'bg-slate-900 text-white border-slate-700 placeholder-slate-500 focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500' : 'bg-white text-slate-900 border-emerald-300 focus:ring-1 focus:ring-emerald-600 focus:border-emerald-600' }}">
                        <p class="text-[10px] {{ $isDark ? 'text-slate-400' : 'text-emerald-800/80' }}">
                            Tu nombre queda guardado en este dispositivo para agilizar los siguientes registros.
                        </p>
                    </div>

                    <!-- Nombre Completo -->
                    <div>
                        <label class="block text-xs font-semibold mb-1 {{ $isDark ? 'text-slate-200' : 'text-slate-700' }}">Nombre y Apellido *</label>
                        <input type="text" name="full_name" required value="{{ old('full_name') }}" 
                               placeholder="Ej. Juan Pérez" 
                               class="w-full px-3 py-2 text-xs font-medium border rounded-md transition {{ $isDark ? 'bg-slate-950 text-white border-slate-700 placeholder-slate-500 focus:ring-1 focus:ring-slate-400 focus:border-slate-400' : 'bg-white text-slate-900 border-slate-300 focus:ring-1 focus:ring-slate-900 focus:border-slate-900' }}">
                        <x-input-error :messages="$errors->get('full_name')" class="mt-1" />
                    </div>

                    <!-- WhatsApp / Teléfono -->
                    <div>
                        <label class="block text-xs font-semibold mb-1 {{ $isDark ? 'text-slate-200' : 'text-slate-700' }}">WhatsApp / Celular *</label>
                        <div class="flex rounded-md shadow-2xs">
                            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 font-mono text-xs font-medium {{ $isDark ? 'border-slate-700 bg-slate-800/90 text-slate-300' : 'border-slate-300 bg-slate-50 text-slate-500' }}">
                                +595
                            </span>
                            <input type="tel" name="phone" required value="{{ old('phone') }}" 
                                   placeholder="981 123 456"
                                   class="flex-1 px-3 py-2 text-xs font-mono font-medium border rounded-none rounded-r-md transition {{ $isDark ? 'bg-slate-950 text-white border-slate-700 placeholder-slate-500 focus:ring-1 focus:ring-slate-400 focus:border-slate-400' : 'bg-white text-slate-900 border-slate-300 focus:ring-1 focus:ring-slate-900 focus:border-slate-900' }}">
                        </div>
                        <x-input-error :messages="$errors->get('phone')" class="mt-1" />
                    </div>

                    <!-- Ciudad o Región -->
                    <div>
                        <label class="block text-xs font-semibold mb-1 {{ $isDark ? 'text-slate-200' : 'text-slate-700' }}">Ciudad / Región *</label>
                        <input type="text" name="city" required value="{{ old('city') }}" 
                               placeholder="Ej. Naranjal, Alto Paraná" 
                               class="w-full px-3 py-2 text-xs font-medium border rounded-md transition {{ $isDark ? 'bg-slate-950 text-white border-slate-700 placeholder-slate-500 focus:ring-1 focus:ring-slate-400 focus:border-slate-400' : 'bg-white text-slate-900 border-slate-300 focus:ring-1 focus:ring-slate-900 focus:border-slate-900' }}">
                        <x-input-error :messages="$errors->get('city')" class="mt-1" />
                    </div>

                    <!-- Actividad / Perfil -->
                    <div>
                        <label class="block text-xs font-semibold mb-1 {{ $isDark ? 'text-slate-200' : 'text-slate-700' }}">Actividad / Perfil *</label>
                        <select name="profile" required 
                                class="w-full px-3 py-2 text-xs font-medium border rounded-md transition {{ $isDark ? 'bg-slate-950 text-white border-slate-700 focus:ring-1 focus:ring-slate-400 focus:border-slate-400' : 'bg-white text-slate-900 border-slate-300 focus:ring-1 focus:ring-slate-900 focus:border-slate-900' }}">
                            @foreach($stand->getProfileOptions() as $pOption)
                                <option value="{{ $pOption }}" {{ old('profile') === $pOption ? 'selected' : '' }}>
                                    {{ $pOption }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('profile')" class="mt-1" />
                    </div>

                    <!-- Observación -->
                    <div>
                        <label class="block text-xs font-semibold mb-1 {{ $isDark ? 'text-slate-200' : 'text-slate-700' }}">Observación <span class="font-normal {{ $isDark ? 'text-slate-500' : 'text-slate-400' }}">(Opcional)</span></label>
                        <input type="text" name="observation" value="{{ old('observation') }}" 
                               placeholder="Ej. Interés en cotización, maquinaria..." 
                               class="w-full px-3 py-2 text-xs font-medium border rounded-md transition {{ $isDark ? 'bg-slate-950 text-white border-slate-700 placeholder-slate-500 focus:ring-1 focus:ring-slate-400 focus:border-slate-400' : 'bg-white text-slate-900 border-slate-300 focus:ring-1 focus:ring-slate-900 focus:border-slate-900' }}">
                    </div>

                    <!-- Botón de Acción Principal (CTA Ejecutivo Rectangular) -->
                    <div class="pt-2">
                        <button type="submit" 
                                class="w-full py-3 px-5 rounded-md text-white font-semibold text-xs uppercase tracking-wider transition-all duration-150 active:scale-[0.99] flex items-center justify-center gap-2 cursor-pointer shadow-xs hover:brightness-105"
                                style="background-color: {{ $stand->primary_color }};">
                            @if($stand->allow_spins)
                                <span>Continuar a Ruleta de Premios &rarr;</span>
                            @else
                                <span>Obtener Mi Cupón de Sorteo &rarr;</span>
                            @endif
                        </button>
                    </div>

                    <p class="text-[10px] text-center font-normal pt-1 {{ $isDark ? 'text-slate-500' : 'text-slate-400' }}">
                        🔒 Protección de datos certificada por SotePass Expo para {{ $stand->name }}.
                    </p>
                </form>

            </div>

        </div>

        <div class="mt-4 text-center">
            <span class="text-[11px] font-normal {{ $isDark ? 'text-slate-500' : 'text-slate-400' }}">
                SotePass&reg; Expo &bull; Infraestructura B2B para Ferias y Eventos
            </span>
        </div>

    </div>
</x-guest-layout>
