<x-guest-layout>
    <x-slot:title>Bienvenido a {{ $stand->name }} | SotePass Expo</x-slot>

    <div class="min-h-screen flex flex-col justify-center items-center p-3 sm:p-6 bg-[#f8f7f5] antialiased">

        <div class="w-full max-w-md bg-white rounded-[2.5rem] border border-slate-200/80 shadow-2xl overflow-hidden relative">
            
            <!-- HEADER DE MARCA (COLORES Y DEGRADADO CORPORATIVO) -->
            <div class="relative pt-8 pb-14 px-6 text-center overflow-hidden" 
                 style="background: linear-gradient(135deg, {{ $stand->primary_color }} 0%, {{ $stand->secondary_color ?? '#0f172a' }} 100%);">
                
                <!-- Efectos de luz sutiles de fondo -->
                <div class="absolute -right-16 -top-16 w-48 h-48 bg-white/15 rounded-full blur-2xl pointer-events-none"></div>
                <div class="absolute -left-16 -bottom-16 w-48 h-48 bg-black/25 rounded-full blur-2xl pointer-events-none"></div>

                <!-- Badge Ubicación / Stand -->
                <div class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full bg-black/25 backdrop-blur-md text-[11px] font-bold text-white tracking-wide uppercase mb-3 border border-white/20 shadow-xs">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>{{ $stand->stand_number }} &bull; {{ $stand->sector }}</span>
                    @if($isTotem)
                        <span class="text-amber-300 ml-1 font-black">&bull; TÓTEM</span>
                    @endif
                </div>

                <!-- Nombre de la Empresa / Marca -->
                <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight leading-tight drop-shadow-sm px-2">
                    {{ $stand->name }}
                </h1>

                <!-- Eslogan o Mensaje de Bienvenida -->
                @if($stand->tagline)
                    <p class="text-xs text-white/90 font-medium mt-1.5 max-w-xs mx-auto leading-relaxed">
                        {{ $stand->tagline }}
                    </p>
                @endif
            </div>

            <!-- LOGO FLOTANTE DE LA EMPRESA (TIPO APP PREMIUM) -->
            <div class="relative -mt-10 mb-2 flex justify-center z-20">
                @if($stand->logo_url)
                    <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-3xl bg-white p-2.5 shadow-xl shadow-black/10 border-2 border-white flex items-center justify-center overflow-hidden transform hover:scale-105 transition-transform duration-300">
                        <img src="{{ $stand->logo_url }}" 
                             alt="{{ $stand->name }}" 
                             class="max-w-full max-h-full object-contain">
                    </div>
                @else
                    <div class="w-20 h-20 rounded-3xl flex items-center justify-center font-black text-white text-2xl shadow-xl shadow-black/10 border-2 border-white transform hover:scale-105 transition-transform duration-300"
                         style="background: linear-gradient(135deg, {{ $stand->primary_color }}, {{ $stand->secondary_color ?? '#0f172a' }});">
                        {{ strtoupper(substr($stand->slug, 0, 2)) }}
                    </div>
                @endif
            </div>

            <!-- CUERPO PRINCIPAL DEL FORMULARIO -->
            <div class="p-6 sm:p-8 pt-3 bg-white relative z-10 space-y-6"
                 x-data="{ 
                     isPromotor: {{ request()->has('promotor') ? 'true' : 'false' }} || !!localStorage.getItem('promoter_name'), 
                     promoterName: localStorage.getItem('promoter_name') || '' 
                 }">
                
                <!-- Encabezado de la Acción (Ruleta vs Sorteo Final) -->
                <div class="text-center">
                    @if($stand->allow_spins)
                        <div class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-amber-50 text-amber-800 border border-amber-200 text-[11px] font-black uppercase tracking-wider mb-1.5">
                            <span>🎡 Ruleta de Premios Activa</span>
                        </div>
                        <h2 class="text-xl font-black text-slate-900 tracking-tight">¡Girá la Ruleta de la Suerte!</h2>
                        <p class="text-xs text-slate-500 mt-1 font-medium leading-relaxed max-w-xs mx-auto">
                            Completá tus datos en 20 segundos y descubrí al instante qué regalo te llevás de nuestro stand.
                        </p>
                    @else
                        <div class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-indigo-50 text-indigo-800 border border-indigo-200 text-[11px] font-black uppercase tracking-wider mb-1.5">
                            <span>🎟️ Gran Sorteo en TV 4K</span>
                        </div>
                        <h2 class="text-xl font-black text-slate-900 tracking-tight">¡Participá del Sorteo Oficial!</h2>
                        <p class="text-xs text-slate-500 mt-1 font-medium leading-relaxed max-w-xs mx-auto">
                            Registrate para recibir tu cupón digital y participar en vivo del sorteo en nuestra pantalla gigante.
                        </p>
                    @endif
                </div>

                <!-- Formulario de Captación Mobile -->
                <form action="{{ route('expo.register', ['slug' => $stand->slug, 'totem' => $isTotem ? 1 : null]) }}" method="POST" class="space-y-4">
                    @csrf

                    <!-- Toggle Discreto para Registro Asistido por Promotor/a (Solo se activa si se pulsa) -->
                    <div class="flex items-center justify-between pb-1.5 border-b border-slate-100">
                        <button type="button" @click="isPromotor = !isPromotor" 
                                class="inline-flex items-center gap-1.5 text-[11px] font-bold text-slate-500 hover:text-slate-800 transition cursor-pointer">
                            <span class="w-2.5 h-2.5 rounded-full border border-slate-300 flex items-center justify-center transition-colors" :class="isPromotor ? 'bg-emerald-500 border-emerald-500' : 'bg-slate-200'"></span>
                            <span x-text="isPromotor ? '✓ Registro Asistido por Promotor/a' : '¿Cargado por Promotor/a?'"></span>
                        </button>
                        <template x-if="isPromotor && promoterName">
                            <span class="text-[10px] font-bold text-emerald-800 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-lg" x-text="promoterName"></span>
                        </template>
                    </div>

                    <!-- Campo Nombre del Promotor/a (Opcional - Memorizado en el dispositivo) -->
                    <div x-show="isPromotor" x-cloak class="p-3.5 rounded-2xl bg-emerald-50/70 border border-emerald-200/80 space-y-1.5 transition-all">
                        <label class="block text-[11px] font-bold text-emerald-950">Nombre del Promotor/a (Stand)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-emerald-600 text-xs">
                                👤
                            </span>
                            <input type="text" name="promoter_name" x-model="promoterName"
                                   @input="localStorage.setItem('promoter_name', $event.target.value)"
                                   placeholder="Ej. Camila, Lucas, etc."
                                   class="w-full pl-8 pr-3 py-2 bg-white text-xs font-bold text-slate-800 border border-emerald-300 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                        </div>
                        <p class="text-[9px] text-emerald-700">Queda memorizado en esta tablet para los siguientes visitantes.</p>
                    </div>

                    <!-- Nombre Completo -->
                    <div>
                        <x-input-label value="Nombre y Apellido *" class="text-xs font-bold text-slate-700" />
                        <div class="relative mt-1">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                👤
                            </span>
                            <input type="text" name="full_name" required value="{{ old('full_name') }}" 
                                   autocapitalize="words" autocomplete="name"
                                   placeholder="Ej. Ing. Carlos Ferreira" 
                                   class="w-full pl-10 pr-4 py-3 bg-slate-50/70 hover:bg-slate-50 focus:bg-white text-sm font-semibold text-slate-900 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all">
                        </div>
                        <x-input-error :messages="$errors->get('full_name')" />
                    </div>

                    <!-- WhatsApp / Celular con Prefijo Paraguay -->
                    <div>
                        <x-input-label value="Número de WhatsApp *" class="text-xs font-bold text-slate-700" />
                        <div class="relative mt-1">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-emerald-700 font-bold text-xs">
                                💬 +595
                            </div>
                            <input type="tel" name="phone" required value="{{ old('phone') }}" 
                                   inputmode="tel" autocomplete="tel"
                                   placeholder="981 123 456"
                                   class="w-full pl-20 pr-4 py-3 bg-slate-50/70 hover:bg-slate-50 focus:bg-white text-sm font-mono font-bold text-slate-900 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all">
                        </div>
                        <x-input-error :messages="$errors->get('phone')" />
                    </div>

                    <!-- Ciudad o Región -->
                    <div>
                        <x-input-label value="Ciudad / Región *" class="text-xs font-bold text-slate-700" />
                        <div class="relative mt-1">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                📍
                            </span>
                            <input type="text" name="city" required value="{{ old('city') }}" 
                                   placeholder="Ej. Naranjal, Alto Paraná" 
                                   class="w-full pl-10 pr-4 py-3 bg-slate-50/70 hover:bg-slate-50 focus:bg-white text-sm font-semibold text-slate-900 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all">
                        </div>
                        <x-input-error :messages="$errors->get('city')" />
                    </div>

                    <!-- Actividad / Perfil (Personalizable por la empresa) -->
                    <div>
                        <x-input-label value="Actividad / Perfil *" class="text-xs font-bold text-slate-700" />
                        <select name="profile" required 
                                class="w-full mt-1 py-3 px-3 bg-slate-50/70 hover:bg-slate-50 focus:bg-white text-xs font-bold text-slate-800 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all">
                            @foreach($stand->getProfileOptions() as $pOption)
                                <option value="{{ $pOption }}" {{ old('profile') === $pOption ? 'selected' : '' }}>
                                    {{ $pOption }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('profile')" />
                    </div>

                    <!-- Observación (Opcional) -->
                    <div>
                        <x-input-label value="Observación" class="text-xs font-bold text-slate-700" />
                        <div class="relative mt-1">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                📝
                            </span>
                            <input type="text" name="observation" value="{{ old('observation') }}" 
                                   placeholder="Ej. Interesado en cotizar cosechadora, semillas, servicios..." 
                                   class="w-full pl-10 pr-4 py-3 bg-slate-50/70 hover:bg-slate-50 focus:bg-white text-sm font-semibold text-slate-900 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all">
                        </div>
                    </div>

                    <!-- Botón de Acción Principal (Personalizado al color de la marca) -->
                    <div class="pt-3">
                        <button type="submit" 
                                class="w-full py-4 px-6 rounded-2xl text-white font-black text-sm uppercase tracking-wider shadow-xl transition-all duration-200 active:scale-95 flex items-center justify-center gap-2 cursor-pointer hover:brightness-110"
                                style="background-color: {{ $stand->primary_color }}; box-shadow: 0 10px 25px -5px {{ $stand->primary_color }}66;">
                            @if($stand->allow_spins)
                                <span>Girar la Ruleta Ahora &rarr;</span>
                            @else
                                <span>Obtener Mi Cupón de Sorteo &rarr;</span>
                            @endif
                        </button>
                    </div>

                    <!-- Garantía de Privacidad Comercial -->
                    <p class="text-[10px] text-center text-slate-400 font-medium leading-relaxed pt-1">
                        🔒 Tus datos están protegidos por <strong>{{ $stand->name }}</strong> bajo normativa de confidencialidad de la Expo.
                    </p>
                </form>

            </div>

        </div>

        <!-- Footer Oficial SotePass Expo -->
        <div class="mt-6 text-center">
            <p class="text-xs text-slate-400 font-medium flex items-center justify-center gap-1.5">
                <span>Tecnología provista por</span>
                <a href="{{ route('expo.index') }}" class="text-slate-700 hover:text-gold-700 font-extrabold transition-colors">
                    SotePass&reg; Expo
                </a>
            </p>
        </div>

    </div>
</x-guest-layout>
