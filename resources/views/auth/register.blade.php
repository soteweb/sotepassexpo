<x-guest-layout>
    <x-slot:title>Registro de Empresa Expositora | SotePass Expo</x-slot>

    <div class="min-h-screen flex flex-col justify-center items-center py-10 px-4 sm:px-6 bg-[#f8f9fa] antialiased font-sans text-slate-800">
        <!-- Brand Logo SotePass Expo -->
        <div class="mb-6 text-center">
            <a href="{{ route('expo.index') }}" class="inline-flex items-center gap-2.5 group transition-transform hover:scale-105">
                <div class="w-10 h-10 bg-indigo-600 rounded-md flex items-center justify-center shadow-xs text-white font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="text-xl font-bold text-slate-900 tracking-tight">
                    Sote<span class="text-indigo-600">Pass</span>
                    <span class="text-[10px] uppercase px-1.5 py-0.5 rounded-md bg-indigo-50 text-indigo-700 font-semibold tracking-wider ml-1 border border-indigo-200">
                        Expo
                    </span>
                </div>
            </a>
            <p class="text-xs text-slate-500 mt-1 font-normal">Registro de Empresas Expositoras y Stands B2B</p>
        </div>

        <!-- Auth Card -->
        <div class="w-full sm:max-w-lg bg-white shadow-sm border border-slate-200 rounded-lg p-6 sm:p-8">
            <div class="mb-5 text-center">
                <h2 class="text-xl font-bold text-slate-900 tracking-tight">Registrar Empresa</h2>
                <p class="text-xs text-slate-500 mt-0.5 font-normal">Crea tu cuenta de expositor para habilitar tu stand, ruleta y captación de leads</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-4 text-xs">
                @csrf
                <input type="hidden" name="plan" value="{{ request('plan') }}">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Nombre del Responsable -->
                    <div>
                        <x-input-label for="name" value="Nombre del Responsable *" />
                        <x-text-input id="name" type="text" name="name" :value="old('name')" required autofocus placeholder="Ej. Ing. Carlos Ferreira" class="w-full" />
                        <x-input-error :messages="$errors->get('name')" class="mt-1" />
                    </div>

                    <!-- Nombre de la Empresa -->
                    <div>
                        <x-input-label for="company_name" value="Nombre de la Empresa / Marca *" />
                        <x-text-input id="company_name" type="text" name="company_name" :value="old('company_name')" required placeholder="Ej. AgroInsumos del Este S.A." class="w-full" />
                        <x-input-error :messages="$errors->get('company_name')" class="mt-1" />
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- WhatsApp -->
                    <div>
                        <x-input-label for="phone" value="WhatsApp de Contacto *" />
                        <x-text-input id="phone" type="tel" name="phone" :value="old('phone')" required placeholder="0981 123 456" class="w-full font-mono" />
                        <x-input-error :messages="$errors->get('phone')" class="mt-1" />
                    </div>

                    <!-- Email Corporativo -->
                    <div>
                        <x-input-label for="email" value="Correo Electrónico Corporativo *" />
                        <x-text-input id="email" type="email" name="email" :value="old('email')" required placeholder="contacto@empresa.com" class="w-full" />
                        <x-input-error :messages="$errors->get('email')" class="mt-1" />
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Contraseña -->
                    <div>
                        <x-input-label for="password" value="Contraseña *" />
                        <x-text-input id="password" type="password" name="password" required autocomplete="new-password" placeholder="Mínimo 8 caracteres" class="w-full" />
                        <x-input-error :messages="$errors->get('password')" class="mt-1" />
                    </div>

                    <!-- Confirmar Contraseña -->
                    <div>
                        <x-input-label for="password_confirmation" value="Confirmar Contraseña *" />
                        <x-text-input id="password_confirmation" type="password" name="password_confirmation" required placeholder="Repite la contraseña" class="w-full" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" 
                            class="w-full py-3 px-5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs uppercase tracking-wider rounded-md shadow-xs transition-all duration-150 active:scale-[0.99] flex items-center justify-center gap-2 cursor-pointer border border-indigo-500">
                        Crear Cuenta y Configurar Mi Stand &rarr;
                    </button>
                </div>
            </form>

            <!-- Links Footer -->
            <div class="mt-6 pt-4 border-t border-slate-100 text-center">
                <p class="text-xs text-slate-500 font-normal">
                    ¿Ya tienes una cuenta registrada?
                    <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-700 font-semibold transition-colors">
                        Iniciar Sesión
                    </a>
                </p>
            </div>
        </div>

        <!-- Footer Soteweb -->
        <div class="mt-6 text-center max-w-md px-4">
            <span class="text-[11px] text-slate-400 font-normal leading-relaxed">
                SotePass&reg; Expo &bull; Infraestructura B2B desarrollada por <a href="https://soteweb.com/" target="_blank" rel="noopener noreferrer" class="text-slate-600 hover:text-slate-900 font-medium underline transition-colors">Soteweb</a>
            </span>
        </div>
    </div>
</x-guest-layout>
