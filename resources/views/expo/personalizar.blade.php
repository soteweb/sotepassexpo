<x-app-layout :stand="$stand">
    <x-slot:title>Personalizar Landing del Visitante | {{ $stand->name }}</x-slot>

    <div class="space-y-6"
         x-data="{ 
             previewName: '{{ addslashes($stand->name) }}', 
             previewTagline: '{{ addslashes($stand->tagline ?? '') }}', 
             previewPrimary: '{{ $stand->primary_color }}', 
             previewSecondary: '{{ $stand->secondary_color ?? '#0f172a' }}', 
             previewSector: '{{ addslashes($stand->sector) }}', 
             previewNumber: '{{ addslashes($stand->stand_number) }}', 
             previewLogo: '{{ $stand->logo_url ? asset($stand->logo_url) : '' }}',
             handleLogoUpload(e) {
                 const file = e.target.files[0];
                 if (file) {
                     this.previewLogo = URL.createObjectURL(file);
                 }
             }
         }">

        <!-- BREADCRUMB & CABECERA DE LA PÁGINA -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs text-slate-400 mb-1">
                    <a href="{{ route('expo.panel', $stand->slug) }}" class="hover:text-slate-700 transition">
                        Panel de {{ $stand->name }}
                    </a>
                    <span>/</span>
                    <span class="text-slate-700 font-bold">Personalizar Landing</span>
                </div>
                <div class="flex items-center gap-2.5">
                    <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Personalización de Marca y Landing</h1>
                    <span class="text-[10px] font-black uppercase px-2.5 py-0.5 rounded-full bg-gold-50 text-gold-800 border border-gold-200">
                        Visual Studio
                    </span>
                </div>
                <p class="text-xs text-slate-500 mt-1">Configura el logo, colores corporativos y eslogan que verán los visitantes al escanear tu QR en la expo.</p>
            </div>

            <div class="flex items-center gap-2.5">
                <a href="{{ route('expo.panel', $stand->slug) }}"
                   class="inline-flex items-center gap-1.5 px-3.5 py-2.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold text-xs rounded-xl transition shadow-2xs">
                    <span>&larr; Volver al Panel</span>
                </a>
                <a href="{{ route('expo.stand', $stand->slug) }}" target="_blank"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs uppercase tracking-wider shadow-sm transition-all cursor-pointer">
                    <span>📱 Ver Landing en Celular &rarr;</span>
                </a>
            </div>
        </div>

        <!-- CONTENIDO PRINCIPAL: FORMULARIO + SIMULADOR SMARTPHONE EN VIVO -->
        <div class="bg-white rounded-[2rem] p-6 sm:p-8 border border-slate-100 shadow-sm">
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- FORMULARIO DE BRANDING (8 Columnas en Desktop) -->
                <form action="{{ route('expo.stand.branding', $stand->slug) }}" method="POST" enctype="multipart/form-data" class="lg:col-span-8 space-y-6 text-xs">
                    @csrf

                    <!-- 1. Logo de la Empresa -->
                    <div class="bg-slate-50/70 rounded-2xl p-5 border border-slate-200/80 space-y-3">
                        <div class="flex items-center justify-between">
                            <label class="block text-xs font-bold text-slate-800">Logo de la Empresa o Marca</label>
                            <span class="text-[10px] text-slate-400 font-medium">PNG, JPG, SVG o WebP (Max 4MB)</span>
                        </div>
                        
                        <div class="flex flex-col sm:flex-row items-center gap-4">
                            <!-- Previsualización del Logo actual -->
                            <div class="w-20 h-20 rounded-2xl bg-white border border-slate-200 shadow-2xs flex items-center justify-center p-2 shrink-0 overflow-hidden">
                                <template x-if="previewLogo">
                                    <img :src="previewLogo" class="max-w-full max-h-full object-contain">
                                </template>
                                <template x-if="!previewLogo">
                                    <span class="text-xs font-black text-slate-400">Sin Logo</span>
                                </template>
                            </div>

                            <!-- Input File -->
                            <div class="flex-1 w-full space-y-2">
                                <input type="file" name="logo" accept="image/*" @change="handleLogoUpload($event)"
                                       class="block w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-gold-50 file:text-gold-800 hover:file:bg-gold-100 cursor-pointer">
                                
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] text-slate-400">O ingresa URL externa:</span>
                                    <input type="url" name="logo_url" x-model="previewLogo" placeholder="https://tuempresa.com/logo.png"
                                           class="flex-1 text-[11px] font-mono py-1.5 px-3 rounded-lg border-slate-200 bg-white">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Paleta de Colores de la Marca -->
                    <div class="bg-slate-50/70 rounded-2xl p-5 border border-slate-200/80 space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-800">Colores Corporativos</label>
                            <p class="text-[11px] text-slate-500 mt-0.5">Se aplican a los degradados de la cabecera, botones de registro y acentos del stand.</p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Color Primario -->
                            <div class="space-y-1.5">
                                <label class="text-[11px] font-bold text-slate-700">Color Primario (Botón y Cabecera)</label>
                                <div class="flex items-center gap-2">
                                    <input type="color" name="primary_color" x-model="previewPrimary"
                                           class="h-10 w-14 rounded-xl border border-slate-200 cursor-pointer p-0.5 bg-white shadow-2xs">
                                    <input type="text" x-model="previewPrimary" name="primary_color"
                                           class="font-mono text-xs font-bold rounded-xl border-slate-200 py-2 px-3 bg-white w-28">
                                </div>
                            </div>

                            <!-- Color Secundario -->
                            <div class="space-y-1.5">
                                <label class="text-[11px] font-bold text-slate-700">Color Secundario (Degradado)</label>
                                <div class="flex items-center gap-2">
                                    <input type="color" name="secondary_color" x-model="previewSecondary"
                                           class="h-10 w-14 rounded-xl border border-slate-200 cursor-pointer p-0.5 bg-white shadow-2xs">
                                    <input type="text" x-model="previewSecondary" name="secondary_color"
                                           class="font-mono text-xs font-bold rounded-xl border-slate-200 py-2 px-3 bg-white w-28">
                                </div>
                            </div>
                        </div>

                        <!-- Paletas Rápidas Inspiradas en el Agro -->
                        <div class="pt-2">
                            <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 block mb-2">Paletas de Referencia Agroindustrial:</span>
                            <div class="flex flex-wrap gap-2">
                                <button type="button" @click="previewPrimary = '#16a34a'; previewSecondary = '#14532d'" 
                                        class="px-2.5 py-1 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 flex items-center gap-1.5 font-bold text-[10px] text-slate-700 transition">
                                    <span class="w-3 h-3 rounded-full bg-[#16a34a]"></span>
                                    <span>John Deere</span>
                                </button>
                                <button type="button" @click="previewPrimary = '#dc2626'; previewSecondary = '#7f1d1d'" 
                                        class="px-2.5 py-1 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 flex items-center gap-1.5 font-bold text-[10px] text-slate-700 transition">
                                    <span class="w-3 h-3 rounded-full bg-[#dc2626]"></span>
                                    <span>Case IH / Massey</span>
                                </button>
                                <button type="button" @click="previewPrimary = '#003399'; previewSecondary = '#001a4d'" 
                                        class="px-2.5 py-1 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 flex items-center gap-1.5 font-bold text-[10px] text-slate-700 transition">
                                    <span class="w-3 h-3 rounded-full bg-[#003399]"></span>
                                    <span>New Holland</span>
                                </button>
                                <button type="button" @click="previewPrimary = '#ea580c'; previewSecondary = '#7c2d12'" 
                                        class="px-2.5 py-1 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 flex items-center gap-1.5 font-bold text-[10px] text-slate-700 transition">
                                    <span class="w-3 h-3 rounded-full bg-[#ea580c]"></span>
                                    <span>Stara</span>
                                </button>
                                <button type="button" @click="previewPrimary = '#65a30d'; previewSecondary = '#365314'" 
                                        class="px-2.5 py-1 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 flex items-center gap-1.5 font-bold text-[10px] text-slate-700 transition">
                                    <span class="w-3 h-3 rounded-full bg-[#65a30d]"></span>
                                    <span>Claas</span>
                                </button>
                                <button type="button" @click="previewPrimary = '#4338ca'; previewSecondary = '#1e1b4b'" 
                                        class="px-2.5 py-1 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 flex items-center gap-1.5 font-bold text-[10px] text-slate-700 transition">
                                    <span class="w-3 h-3 rounded-full bg-[#4338ca]"></span>
                                    <span>SotePass Indigo</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- 3. Textos e Información de Bienvenida -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 mb-1">Nombre Comercial de la Empresa *</label>
                            <input type="text" name="name" x-model="previewName" required
                                   class="w-full text-xs font-bold rounded-xl border-slate-200 focus:border-gold-500 py-2.5 px-3 bg-slate-50/50">
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 mb-1">Sector / Rubro *</label>
                            <input type="text" name="sector" x-model="previewSector" required
                                   class="w-full text-xs font-bold rounded-xl border-slate-200 focus:border-gold-500 py-2.5 px-3 bg-slate-50/50">
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 mb-1">Número o Código del Stand *</label>
                            <input type="text" name="stand_number" x-model="previewNumber" required
                                   class="w-full text-xs font-bold rounded-xl border-slate-200 focus:border-gold-500 py-2.5 px-3 bg-slate-50/50">
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 mb-1">Eslogan o Mensaje de Bienvenida</label>
                            <input type="text" name="tagline" x-model="previewTagline" placeholder="Ej. Innovación que impulsa el campo"
                                   class="w-full text-xs font-bold rounded-xl border-slate-200 focus:border-gold-500 py-2.5 px-3 bg-slate-50/50">
                        </div>
                    </div>

                    <!-- 4. Perfiles y Rubros Personalizables del Formulario -->
                    <div class="bg-slate-50/70 rounded-2xl p-5 border border-slate-200/80 space-y-3">
                        <div class="flex items-center gap-2">
                            <span class="w-7 h-7 rounded-lg bg-gold-100 text-gold-800 flex items-center justify-center font-bold text-xs">👥</span>
                            <div>
                                <label class="block text-xs font-bold text-slate-800">Perfiles de Visitantes en el Formulario</label>
                                <p class="text-[11px] text-slate-500">Define las opciones que verán tus visitantes en el selector de "Actividad / Perfil". Escríbelas separadas por comas.</p>
                            </div>
                        </div>

                        <div>
                            <textarea name="profiles_list" rows="2" 
                                      class="w-full text-xs font-semibold rounded-xl border-slate-200 focus:border-gold-500 p-3 bg-white shadow-2xs leading-relaxed"
                                      placeholder="Productor Agrícola, Ganadero, Contratista Rural, Ingeniero Agrónomo, Comerciante, Visitante General">{{ implode(', ', $stand->getProfileOptions()) }}</textarea>
                            <span class="text-[10px] text-slate-400 mt-1 block">Ejemplo: Productor, Contratista, Comerciante, Mecánico, Inversionista, Otro</span>
                        </div>
                    </div>

                    <!-- Botón de Guardar Cambios -->
                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-[11px] text-slate-400">Los cambios se aplican inmediatamente a la landing y a la ruleta.</span>
                        <button type="submit" 
                                class="px-6 py-3 bg-gold-500 hover:bg-gold-600 text-white font-bold text-xs uppercase tracking-wider rounded-xl transition-all shadow-md shadow-gold-500/20 active:scale-95 cursor-pointer">
                            Guardar Personalización
                        </button>
                    </div>
                </form>

                <!-- PREVISUALIZADOR SMARTPHONE EN VIVO (4 Columnas en Desktop) -->
                <div class="lg:col-span-4 flex flex-col items-center">
                    <div class="text-center mb-2">
                        <span class="text-[11px] font-black uppercase tracking-wider text-slate-400">Vista Previa Móvil en Vivo</span>
                    </div>

                    <!-- Marco Celular Estilo iPhone -->
                    <div class="w-64 rounded-[2.5rem] border-[6px] border-slate-800 bg-white shadow-2xl overflow-hidden relative">
                        <!-- Notch / Altavoz -->
                        <div class="h-4 bg-slate-800 w-24 mx-auto rounded-b-xl mb-1"></div>

                        <!-- Pantalla simulada -->
                        <div class="text-center overflow-hidden">
                            <!-- Cabecera simulada -->
                            <div class="py-5 px-3 relative text-white"
                                 :style="'background: linear-gradient(135deg, ' + previewPrimary + ', ' + previewSecondary + ')'">
                                <div class="text-[8px] font-bold uppercase tracking-wider opacity-80" x-text="previewNumber + ' • ' + previewSector"></div>
                                <div class="text-xs font-black tracking-tight mt-1 truncate" x-text="previewName"></div>
                                <div class="text-[8px] opacity-90 line-clamp-2 mt-0.5 px-1 leading-tight" x-text="previewTagline || 'Bienvenido a nuestro stand'"></div>
                            </div>

                            <!-- Logo simulado -->
                            <div class="relative -mt-4 mb-2 flex justify-center z-10">
                                <div class="w-10 h-10 rounded-xl bg-white p-1 shadow-md border border-white flex items-center justify-center overflow-hidden">
                                    <template x-if="previewLogo">
                                        <img :src="previewLogo" class="max-w-full max-h-full object-contain">
                                    </template>
                                    <template x-if="!previewLogo">
                                        <div class="w-full h-full rounded-lg flex items-center justify-center text-white font-black text-[10px]"
                                             :style="'background-color: ' + previewPrimary">
                                            SP
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- Mini Formulario simulado -->
                            <div class="p-3 pt-0 space-y-1.5 text-left">
                                <div class="text-[9px] font-black text-center text-slate-800">
                                    {{ $stand->allow_spins ? '🎡 Ruleta de Premios' : '🎟️ Gran Sorteo Oficial' }}
                                </div>
                                <div class="h-5 rounded-lg bg-slate-100 border border-slate-200 px-2 flex items-center text-[8px] text-slate-400">
                                    Nombre y Apellido
                                </div>
                                <div class="h-5 rounded-lg bg-slate-100 border border-slate-200 px-2 flex items-center text-[8px] text-slate-400 font-mono">
                                    +595 981 ...
                                </div>
                                <div class="h-5 rounded-lg bg-slate-100 border border-slate-200 px-2 flex items-center text-[8px] text-slate-400">
                                    Ciudad / Región
                                </div>
                                <div class="h-6 rounded-lg text-white font-black text-[9px] flex items-center justify-center shadow-xs"
                                     :style="'background-color: ' + previewPrimary">
                                    {{ $stand->allow_spins ? 'Girar Ruleta →' : 'Participar Sorteo →' }}
                                </div>
                            </div>

                            <div class="py-2 text-[7px] text-slate-400">
                                SotePass Expo
                            </div>
                        </div>

                        <!-- Barra inferior Home iPhone -->
                        <div class="h-1 bg-slate-300 w-20 mx-auto rounded-full mb-1"></div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</x-app-layout>
