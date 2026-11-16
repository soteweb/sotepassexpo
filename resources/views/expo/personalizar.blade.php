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
                    <span class="text-slate-700 font-semibold">Personalizar Landing</span>
                </div>
                <div class="flex items-center gap-2.5">
                    <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">Personalización de Marca y Landing</h1>
                    <span class="text-[10px] font-semibold uppercase px-2 py-0.5 rounded-md bg-gold-50 text-gold-800 border border-gold-200">
                        Visual Studio
                    </span>
                </div>
                <p class="text-xs text-slate-500 mt-1">Configura el logo, colores corporativos y eslogan que verán los visitantes al escanear tu QR en la expo.</p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('expo.panel', $stand->slug) }}"
                   class="inline-flex items-center gap-1.5 px-3 py-2 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-semibold text-xs rounded-md transition shadow-xs">
                    <span>&larr; Volver al Panel</span>
                </a>
                <a href="{{ route('expo.stand', $stand->slug) }}" target="_blank"
                   class="inline-flex items-center gap-2 px-3.5 py-2 rounded-md bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs uppercase tracking-wider shadow-xs transition-all cursor-pointer">
                    <span>📱 Ver Landing en Celular &rarr;</span>
                </a>
            </div>
        </div>

        <!-- CONTENIDO PRINCIPAL: FORMULARIO + SIMULADOR SMARTPHONE EN VIVO -->
        <div class="bg-white rounded-lg p-6 sm:p-8 border border-slate-200 shadow-xs">
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- FORMULARIO DE BRANDING (8 Columnas en Desktop) -->
                <form action="{{ route('expo.stand.branding', $stand->slug) }}" method="POST" enctype="multipart/form-data" class="lg:col-span-8 space-y-5 text-xs">
                    @csrf

                    <!-- 1. Logo de la Empresa -->
                    <div class="bg-slate-50/70 rounded-md p-4 sm:p-5 border border-slate-200 space-y-3">
                        <div class="flex items-center justify-between">
                            <label class="block text-xs font-semibold text-slate-800">Logo de la Empresa o Marca</label>
                            <span class="text-[10px] text-slate-400 font-medium">PNG, JPG, SVG o WebP (Max 4MB)</span>
                        </div>
                        
                        <div class="flex flex-col sm:flex-row items-center gap-4">
                            <!-- Previsualización del Logo actual -->
                            <div class="w-16 h-16 rounded-md bg-white border border-slate-200 shadow-xs flex items-center justify-center p-2 shrink-0 overflow-hidden">
                                <template x-if="previewLogo">
                                    <img :src="previewLogo" class="max-w-full max-h-full object-contain">
                                </template>
                                <template x-if="!previewLogo">
                                    <span class="text-[11px] font-bold text-slate-400">Sin Logo</span>
                                </template>
                            </div>

                            <!-- Input File -->
                            <div class="flex-1 w-full space-y-2">
                                <input type="file" name="logo" accept="image/*" @change="handleLogoUpload($event)"
                                       class="block w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-slate-900 file:text-white hover:file:bg-slate-800 cursor-pointer">
                                
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] text-slate-400">O ingresa URL externa:</span>
                                    <input type="url" name="logo_url" x-model="previewLogo" placeholder="https://tuempresa.com/logo.png"
                                           class="flex-1 text-[11px] font-mono py-1.5 px-2.5 rounded-md border-slate-300 bg-white">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Paleta de Colores de la Marca -->
                    <div class="bg-slate-50/70 rounded-md p-4 sm:p-5 border border-slate-200 space-y-3.5">
                        <div>
                            <label class="block text-xs font-semibold text-slate-800">Colores Corporativos</label>
                            <p class="text-[11px] text-slate-500 mt-0.5">Se aplican a los degradados de la cabecera, botones de registro y acentos del stand.</p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Color Primario -->
                            <div class="space-y-1">
                                <label class="text-[11px] font-semibold text-slate-700">Color Primario (Botón y Cabecera)</label>
                                <div class="flex items-center gap-2">
                                    <input type="color" name="primary_color" x-model="previewPrimary"
                                           class="h-9 w-12 rounded-md border border-slate-200 cursor-pointer p-0.5 bg-white shadow-xs">
                                    <input type="text" x-model="previewPrimary" name="primary_color"
                                           class="font-mono text-xs font-semibold rounded-md border-slate-300 py-1.5 px-2.5 bg-white w-28">
                                </div>
                            </div>

                            <!-- Color Secundario -->
                            <div class="space-y-1">
                                <label class="text-[11px] font-semibold text-slate-700">Color Secundario (Degradado)</label>
                                <div class="flex items-center gap-2">
                                    <input type="color" name="secondary_color" x-model="previewSecondary"
                                           class="h-9 w-12 rounded-md border border-slate-200 cursor-pointer p-0.5 bg-white shadow-xs">
                                    <input type="text" x-model="previewSecondary" name="secondary_color"
                                           class="font-mono text-xs font-semibold rounded-md border-slate-300 py-1.5 px-2.5 bg-white w-28">
                                </div>
                            </div>
                        </div>

                        <!-- Paleta de Colores Sugerida -->
                        <div class="pt-2">
                            <span class="text-[10px] font-semibold uppercase tracking-wider text-slate-400 block mb-2">Paleta de colores:</span>
                            <div class="flex flex-wrap gap-2">
                                <button type="button" @click="previewPrimary = '#16a34a'; previewSecondary = '#14532d'" 
                                        class="px-2.5 py-1.5 rounded-md border border-slate-200 bg-white hover:bg-slate-50 flex items-center gap-1.5 font-semibold text-[10px] text-slate-700 transition shadow-2xs hover:border-slate-300 cursor-pointer">
                                    <span class="w-3 h-3 rounded-xs shrink-0 border border-black/10" style="background: linear-gradient(135deg, #16a34a 50%, #14532d 50%);"></span>
                                    <span>Verde Esmeralda</span>
                                </button>
                                <button type="button" @click="previewPrimary = '#65a30d'; previewSecondary = '#365314'" 
                                        class="px-2.5 py-1.5 rounded-md border border-slate-200 bg-white hover:bg-slate-50 flex items-center gap-1.5 font-semibold text-[10px] text-slate-700 transition shadow-2xs hover:border-slate-300 cursor-pointer">
                                    <span class="w-3 h-3 rounded-xs shrink-0 border border-black/10" style="background: linear-gradient(135deg, #65a30d 50%, #365314 50%);"></span>
                                    <span>Verde Pradera</span>
                                </button>
                                <button type="button" @click="previewPrimary = '#059669'; previewSecondary = '#064e3b'" 
                                        class="px-2.5 py-1.5 rounded-md border border-slate-200 bg-white hover:bg-slate-50 flex items-center gap-1.5 font-semibold text-[10px] text-slate-700 transition shadow-2xs hover:border-slate-300 cursor-pointer">
                                    <span class="w-3 h-3 rounded-xs shrink-0 border border-black/10" style="background: linear-gradient(135deg, #059669 50%, #064e3b 50%);"></span>
                                    <span>Verde Botánico</span>
                                </button>
                                <button type="button" @click="previewPrimary = '#0284c7'; previewSecondary = '#075985'" 
                                        class="px-2.5 py-1.5 rounded-md border border-slate-200 bg-white hover:bg-slate-50 flex items-center gap-1.5 font-semibold text-[10px] text-slate-700 transition shadow-2xs hover:border-slate-300 cursor-pointer">
                                    <span class="w-3 h-3 rounded-xs shrink-0 border border-black/10" style="background: linear-gradient(135deg, #0284c7 50%, #075985 50%);"></span>
                                    <span>Azul Océano</span>
                                </button>
                                <button type="button" @click="previewPrimary = '#1d4ed8'; previewSecondary = '#1e3a8a'" 
                                        class="px-2.5 py-1.5 rounded-md border border-slate-200 bg-white hover:bg-slate-50 flex items-center gap-1.5 font-semibold text-[10px] text-slate-700 transition shadow-2xs hover:border-slate-300 cursor-pointer">
                                    <span class="w-3 h-3 rounded-xs shrink-0 border border-black/10" style="background: linear-gradient(135deg, #1d4ed8 50%, #1e3a8a 50%);"></span>
                                    <span>Azul Real</span>
                                </button>
                                <button type="button" @click="previewPrimary = '#003399'; previewSecondary = '#001a4d'" 
                                        class="px-2.5 py-1.5 rounded-md border border-slate-200 bg-white hover:bg-slate-50 flex items-center gap-1.5 font-semibold text-[10px] text-slate-700 transition shadow-2xs hover:border-slate-300 cursor-pointer">
                                    <span class="w-3 h-3 rounded-xs shrink-0 border border-black/10" style="background: linear-gradient(135deg, #003399 50%, #001a4d 50%);"></span>
                                    <span>Azul Marino</span>
                                </button>
                                <button type="button" @click="previewPrimary = '#4338ca'; previewSecondary = '#1e1b4b'" 
                                        class="px-2.5 py-1.5 rounded-md border border-slate-200 bg-white hover:bg-slate-50 flex items-center gap-1.5 font-semibold text-[10px] text-slate-700 transition shadow-2xs hover:border-slate-300 cursor-pointer">
                                    <span class="w-3 h-3 rounded-xs shrink-0 border border-black/10" style="background: linear-gradient(135deg, #4338ca 50%, #1e1b4b 50%);"></span>
                                    <span>Índigo Ejecutivo</span>
                                </button>
                                <button type="button" @click="previewPrimary = '#dc2626'; previewSecondary = '#7f1d1d'" 
                                        class="px-2.5 py-1.5 rounded-md border border-slate-200 bg-white hover:bg-slate-50 flex items-center gap-1.5 font-semibold text-[10px] text-slate-700 transition shadow-2xs hover:border-slate-300 cursor-pointer">
                                    <span class="w-3 h-3 rounded-xs shrink-0 border border-black/10" style="background: linear-gradient(135deg, #dc2626 50%, #7f1d1d 50%);"></span>
                                    <span>Rojo Escarlata</span>
                                </button>
                                <button type="button" @click="previewPrimary = '#b91c1c'; previewSecondary = '#450a0a'" 
                                        class="px-2.5 py-1.5 rounded-md border border-slate-200 bg-white hover:bg-slate-50 flex items-center gap-1.5 font-semibold text-[10px] text-slate-700 transition shadow-2xs hover:border-slate-300 cursor-pointer">
                                    <span class="w-3 h-3 rounded-xs shrink-0 border border-black/10" style="background: linear-gradient(135deg, #b91c1c 50%, #450a0a 50%);"></span>
                                    <span>Rojo Carmesí</span>
                                </button>
                                <button type="button" @click="previewPrimary = '#ea580c'; previewSecondary = '#7c2d12'" 
                                        class="px-2.5 py-1.5 rounded-md border border-slate-200 bg-white hover:bg-slate-50 flex items-center gap-1.5 font-semibold text-[10px] text-slate-700 transition shadow-2xs hover:border-slate-300 cursor-pointer">
                                    <span class="w-3 h-3 rounded-xs shrink-0 border border-black/10" style="background: linear-gradient(135deg, #ea580c 50%, #7c2d12 50%);"></span>
                                    <span>Naranja Ámbar</span>
                                </button>
                                <button type="button" @click="previewPrimary = '#d97706'; previewSecondary = '#78350f'" 
                                        class="px-2.5 py-1.5 rounded-md border border-slate-200 bg-white hover:bg-slate-50 flex items-center gap-1.5 font-semibold text-[10px] text-slate-700 transition shadow-2xs hover:border-slate-300 cursor-pointer">
                                    <span class="w-3 h-3 rounded-xs shrink-0 border border-black/10" style="background: linear-gradient(135deg, #d97706 50%, #78350f 50%);"></span>
                                    <span>Dorado Solar</span>
                                </button>
                                <button type="button" @click="previewPrimary = '#0d9488'; previewSecondary = '#134e4a'" 
                                        class="px-2.5 py-1.5 rounded-md border border-slate-200 bg-white hover:bg-slate-50 flex items-center gap-1.5 font-semibold text-[10px] text-slate-700 transition shadow-2xs hover:border-slate-300 cursor-pointer">
                                    <span class="w-3 h-3 rounded-xs shrink-0 border border-black/10" style="background: linear-gradient(135deg, #0d9488 50%, #134e4a 50%);"></span>
                                    <span>Verde Petróleo</span>
                                </button>
                                <button type="button" @click="previewPrimary = '#7c3aed'; previewSecondary = '#4c1d95'" 
                                        class="px-2.5 py-1.5 rounded-md border border-slate-200 bg-white hover:bg-slate-50 flex items-center gap-1.5 font-semibold text-[10px] text-slate-700 transition shadow-2xs hover:border-slate-300 cursor-pointer">
                                    <span class="w-3 h-3 rounded-xs shrink-0 border border-black/10" style="background: linear-gradient(135deg, #7c3aed 50%, #4c1d95 50%);"></span>
                                    <span>Púrpura Noble</span>
                                </button>
                                <button type="button" @click="previewPrimary = '#334155'; previewSecondary = '#0f172a'" 
                                        class="px-2.5 py-1.5 rounded-md border border-slate-200 bg-white hover:bg-slate-50 flex items-center gap-1.5 font-semibold text-[10px] text-slate-700 transition shadow-2xs hover:border-slate-300 cursor-pointer">
                                    <span class="w-3 h-3 rounded-xs shrink-0 border border-black/10" style="background: linear-gradient(135deg, #334155 50%, #0f172a 50%);"></span>
                                    <span>Grafito Titán</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- 3. Textos e Información de Bienvenida -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-700 mb-1">Nombre Comercial de la Empresa *</label>
                            <input type="text" name="name" x-model="previewName" required
                                   class="w-full text-xs font-semibold rounded-md border-slate-300 focus:border-slate-900 focus:ring-1 focus:ring-slate-900 py-2 px-2.5 bg-slate-50/50">
                        </div>

                        <div>
                            <label class="block text-[11px] font-semibold text-slate-700 mb-1">Sector / Rubro *</label>
                            <input type="text" name="sector" x-model="previewSector" required
                                   class="w-full text-xs font-semibold rounded-md border-slate-300 focus:border-slate-900 focus:ring-1 focus:ring-slate-900 py-2 px-2.5 bg-slate-50/50">
                        </div>

                        <div>
                            <label class="block text-[11px] font-semibold text-slate-700 mb-1">Número o Código del Stand *</label>
                            <input type="text" name="stand_number" x-model="previewNumber" required
                                   class="w-full text-xs font-semibold rounded-md border-slate-300 focus:border-slate-900 focus:ring-1 focus:ring-slate-900 py-2 px-2.5 bg-slate-50/50">
                        </div>

                        <div>
                            <label class="block text-[11px] font-semibold text-slate-700 mb-1">Eslogan o Mensaje de Bienvenida</label>
                            <input type="text" name="tagline" x-model="previewTagline" placeholder="Ej. Innovación que impulsa el campo"
                                   class="w-full text-xs font-semibold rounded-md border-slate-300 focus:border-slate-900 focus:ring-1 focus:ring-slate-900 py-2 px-2.5 bg-slate-50/50">
                        </div>
                    </div>

                    <!-- 4. Perfiles y Rubros Personalizables del Formulario -->
                    <div class="bg-slate-50/70 rounded-md p-4 sm:p-5 border border-slate-200 space-y-2.5">
                        <div class="flex items-center gap-2">
                            <span class="w-6 h-6 rounded-md bg-gold-100 text-gold-800 flex items-center justify-center font-bold text-xs">👥</span>
                            <div>
                                <label class="block text-xs font-semibold text-slate-800">Perfiles de Visitantes en el Formulario</label>
                                <p class="text-[11px] text-slate-500">Define las opciones que verán tus visitantes en el selector de "Actividad / Perfil". Escríbelas separadas por comas.</p>
                            </div>
                        </div>

                        <div>
                            <textarea name="profiles_list" rows="2" 
                                      class="w-full text-xs font-medium rounded-md border-slate-300 focus:border-slate-900 focus:ring-1 focus:ring-slate-900 p-2.5 bg-white shadow-xs leading-relaxed"
                                      placeholder="Productor Agrícola, Ganadero, Contratista Rural, Ingeniero Agrónomo, Comerciante, Visitante General">{{ implode(', ', $stand->getProfileOptions()) }}</textarea>
                            <span class="text-[10px] text-slate-400 mt-1 block">Ejemplo: Productor, Contratista, Comerciante, Mecánico, Inversionista, Otro</span>
                        </div>
                    </div>

                    <!-- Botón de Guardar Cambios -->
                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-[11px] text-slate-400">Los cambios se aplican inmediatamente a la landing y a la ruleta.</span>
                        <button type="submit" 
                                class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs uppercase tracking-wider rounded-md transition-all shadow-xs active:scale-[0.99] cursor-pointer">
                            Guardar Personalización
                        </button>
                    </div>
                </form>

                <!-- PREVISUALIZADOR SMARTPHONE EN VIVO (4 Columnas en Desktop) -->
                <div class="lg:col-span-4 flex flex-col items-center">
                    <div class="text-center mb-2">
                        <span class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">Vista Previa Móvil en Vivo</span>
                    </div>

                    <!-- Marco Celular Estilo Moderno Rectangular -->
                    <div class="w-64 rounded-xl border-4 border-slate-800 bg-white shadow-lg overflow-hidden relative">
                        <!-- Barra superior simulada -->
                        <div class="h-3.5 bg-slate-800 w-20 mx-auto rounded-b-md mb-1"></div>

                        <!-- Pantalla simulada -->
                        <div class="text-center overflow-hidden">
                            <!-- Cabecera simulada -->
                            <div class="py-5 px-3 relative text-white"
                                 :style="'background: linear-gradient(135deg, ' + previewPrimary + ', ' + previewSecondary + ')'">
                                <div class="text-[8px] font-semibold uppercase tracking-wider opacity-80" x-text="previewNumber + ' • ' + previewSector"></div>
                                <div class="text-xs font-bold tracking-tight mt-1 truncate" x-text="previewName"></div>
                                <div class="text-[8px] opacity-90 line-clamp-2 mt-0.5 px-1 leading-tight" x-text="previewTagline || 'Bienvenido a nuestro stand'"></div>
                            </div>

                            <!-- Logo simulado -->
                            <div class="relative -mt-4 mb-2 flex justify-center z-10">
                                <div class="w-9 h-9 rounded-md bg-white p-1 shadow-xs border border-white flex items-center justify-center overflow-hidden">
                                    <template x-if="previewLogo">
                                        <img :src="previewLogo" class="max-w-full max-h-full object-contain">
                                    </template>
                                    <template x-if="!previewLogo">
                                        <div class="w-full h-full rounded-sm flex items-center justify-center text-white font-bold text-[9px]"
                                             :style="'background-color: ' + previewPrimary">
                                            SP
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- Mini Formulario simulado -->
                            <div class="p-3 pt-0 space-y-1.5 text-left">
                                <div class="text-[9px] font-bold text-center text-slate-800">
                                    {{ $stand->allow_spins ? '🎡 Ruleta de Premios' : '🎟️ Gran Sorteo Oficial' }}
                                </div>
                                <div class="h-5 rounded-md bg-slate-100 border border-slate-200 px-2 flex items-center text-[8px] text-slate-400">
                                    Nombre y Apellido
                                </div>
                                <div class="h-5 rounded-md bg-slate-100 border border-slate-200 px-2 flex items-center text-[8px] text-slate-400 font-mono">
                                    +595 981 ...
                                </div>
                                <div class="h-5 rounded-md bg-slate-100 border border-slate-200 px-2 flex items-center text-[8px] text-slate-400">
                                    Ciudad / Región
                                </div>
                                <div class="h-6 rounded-md text-white font-semibold text-[9px] flex items-center justify-center shadow-xs"
                                     :style="'background-color: ' + previewPrimary">
                                    {{ $stand->allow_spins ? 'Girar Ruleta →' : 'Participar Sorteo →' }}
                                </div>
                            </div>

                            <div class="py-2 text-[7px] text-slate-400">
                                SotePass Expo
                            </div>
                        </div>

                        <!-- Barra inferior simulada -->
                        <div class="h-1 bg-slate-300 w-16 mx-auto rounded-full mb-1"></div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</x-app-layout>
