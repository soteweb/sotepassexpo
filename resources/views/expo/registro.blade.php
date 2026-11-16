<x-guest-layout>
    <x-slot:title>Bienvenido a {{ $stand->name }} | SotePass Expo</x-slot>

    <div class="min-h-screen flex flex-col justify-center items-center p-4 sm:p-6 bg-[#f8f7f5] antialiased">

        <div class="w-full max-w-md bg-white rounded-3xl border border-slate-200/80 shadow-xl overflow-hidden relative">
            
            <!-- Header con Identidad del Stand y SotePass -->
            <div class="relative p-6 pb-8 text-center overflow-hidden" style="background: linear-gradient(135deg, {{ $stand->primary_color }} 0%, {{ $stand->secondary_color }} 100%);">
                <div class="absolute -right-12 -top-12 w-40 h-40 bg-white/10 rounded-full blur-xl pointer-events-none"></div>
                <div class="absolute -left-12 -bottom-12 w-40 h-40 bg-black/20 rounded-full blur-xl pointer-events-none"></div>

                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-black/30 backdrop-blur-md text-[11px] font-bold text-white tracking-wider uppercase mb-3 border border-white/20">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>SotePass Expo • {{ $stand->stand_number }}</span>
                    @if($isTotem)
                        <span class="text-amber-300 ml-1">• TÓTEM</span>
                    @endif
                </div>

                <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight leading-tight drop-shadow-sm">
                    {{ $stand->name }}
                </h1>
                <p class="text-xs text-white/90 font-medium mt-1">
                    {{ $stand->sector }}
                </p>
            </div>

            <!-- Cuerpo del Registro (Estilo SotePass) -->
            <div class="p-6 -mt-4 bg-white rounded-t-3xl relative z-10 space-y-5">
                
                <div class="text-center">
                    <div class="w-12 h-12 bg-gold-50 text-gold-700 rounded-2xl flex items-center justify-center mx-auto mb-2 border border-gold-100 text-xl font-bold shadow-2xs">
                        🎁
                    </div>
                    <h2 class="text-lg font-black text-slate-900 tracking-tight">¡Girá la Ruleta de Premios!</h2>
                    <p class="text-xs text-slate-500 mt-1 font-medium leading-relaxed">
                        Registrate en 30 segundos, descubrí tu premio instantáneo y participá del gran sorteo oficial del stand.
                    </p>
                </div>

                <!-- Formulario de Captación -->
                <form action="{{ route('expo.register', ['slug' => $stand->slug, 'totem' => $isTotem ? 1 : null]) }}" method="POST" class="space-y-4">
                    @csrf

                    <!-- Nombre Completo -->
                    <div>
                        <x-input-label value="Nombre y Apellido *" />
                        <x-text-input type="text" name="full_name" required value="{{ old('full_name') }}" placeholder="Ej. Carlos Ferreira" class="w-full" />
                        <x-input-error :messages="$errors->get('full_name')" />
                    </div>

                    <!-- WhatsApp -->
                    <div>
                        <x-input-label value="Número de WhatsApp *" />
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-emerald-600 font-bold text-xs">
                                💬 +595
                            </div>
                            <input type="tel" name="phone" required value="{{ old('phone') }}" placeholder="981 123 456"
                                class="w-full pl-20 pr-4 py-2.5 bg-slate-50/50 hover:bg-slate-50 focus:bg-white text-sm text-slate-900 border border-slate-200 rounded-xl focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all font-mono">
                        </div>
                        <x-input-error :messages="$errors->get('phone')" />
                    </div>

                    <!-- Ciudad / Localidad -->
                    <div>
                        <x-input-label value="Ciudad o Región *" />
                        <x-text-input type="text" name="city" required value="{{ old('city') }}" placeholder="Ej. Naranjal, Alto Paraná" class="w-full" />
                        <x-input-error :messages="$errors->get('city')" />
                    </div>

                    <!-- Perfil de Producción -->
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <x-input-label value="Perfil de Actividad *" />
                            <select name="profile" required class="w-full py-2.5 bg-slate-50/50 hover:bg-slate-50 focus:bg-white text-xs font-semibold text-slate-800 border border-slate-200 rounded-xl focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all">
                                <option value="Productor Agrícola">Productor Agrícola</option>
                                <option value="Ganadero">Ganadero</option>
                                <option value="Contratista Rural">Contratista Rural</option>
                                <option value="Ingeniero Agrónomo">Ing. Agrónomo / Técnico</option>
                                <option value="Estudiante / Público">Visitante / Estudiante</option>
                            </select>
                        </div>
                        <div>
                            <x-input-label value="Hectáreas Aprox." />
                            <select name="hectares" class="w-full py-2.5 bg-slate-50/50 hover:bg-slate-50 focus:bg-white text-xs font-semibold text-slate-800 border border-slate-200 rounded-xl focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all">
                                <option value="">Sin especificar</option>
                                <option value="1 a 50 ha">1 a 50 ha</option>
                                <option value="50 a 300 ha">50 a 300 ha</option>
                                <option value="300 a 1.000 ha">300 a 1.000 ha</option>
                                <option value="+1.000 ha">+1.000 ha</option>
                            </select>
                        </div>
                    </div>

                    <!-- Botón de Registro & Acceso a la Ruleta -->
                    <div class="pt-2">
                        <button type="submit" 
                                class="w-full py-3.5 px-6 rounded-2xl text-white font-black text-sm uppercase tracking-wider shadow-lg transition-all duration-200 active:scale-95 flex items-center justify-center gap-2 cursor-pointer"
                                style="background-color: {{ $stand->primary_color }};">
                            <span>Continuar a la Ruleta</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                    </div>

                    <p class="text-[10px] text-center text-slate-400 font-medium">
                        Tus datos son resguardados por {{ $stand->name }} bajo normativa de privacidad comercial.
                    </p>
                </form>

            </div>

        </div>

        <!-- Footer SotePass -->
        <div class="mt-6 text-center">
            <p class="text-xs text-slate-500 font-medium">
                Tecnología provista por <a href="{{ route('expo.index') }}" class="text-gold-700 hover:text-gold-800 font-bold underline transition-colors">SotePass&reg; Expo</a>
            </p>
        </div>

    </div>
</x-guest-layout>
