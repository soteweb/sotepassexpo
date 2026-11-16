<x-guest-layout>
    <x-slot:title>Iniciar Sesión | SotePass Expo</x-slot>

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
            <p class="text-xs text-slate-500 mt-1 font-normal">Plataforma de Captación y Ruleta B2B para Stands</p>
        </div>

        <!-- Auth Card -->
        <div class="w-full sm:max-w-md bg-white shadow-sm border border-slate-200 rounded-lg p-6 sm:p-8">
            <div class="mb-5 text-center">
                <h2 class="text-xl font-bold text-slate-900 tracking-tight">Acceso a tu Panel</h2>
                <p class="text-xs text-slate-500 mt-0.5 font-normal">Ingresa tus credenciales de empresa o administrador</p>
            </div>

            @if(session('error'))
                <div class="mb-4 p-3 rounded-md bg-rose-50 border border-rose-200 text-xs font-semibold text-rose-700">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <!-- Correo Electrónico -->
                <div>
                    <x-input-label for="email" value="Correo Electrónico *" />
                    <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="empresa@ejemplo.com" class="w-full" />
                    <x-input-error :messages="$errors->get('email')" class="mt-1" />
                </div>

                <!-- Contraseña -->
                <div>
                    <x-input-label for="password" value="Contraseña *" />
                    <x-text-input id="password" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" class="w-full" />
                    <x-input-error :messages="$errors->get('password')" class="mt-1" />
                </div>

                <!-- Recordarme -->
                <div class="flex items-center justify-between text-xs pt-1">
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" name="remember" class="w-3.5 h-3.5 rounded-[3px] border-slate-300 text-indigo-600 focus:ring-indigo-500">
                        <span class="font-normal text-slate-600">Recordar mi sesión</span>
                    </label>
                </div>

                <div class="pt-2">
                    <button type="submit" 
                            class="w-full py-3 px-5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs uppercase tracking-wider rounded-md shadow-xs transition-all duration-150 active:scale-[0.99] flex items-center justify-center gap-2 cursor-pointer border border-indigo-500">
                        Ingresar al Panel
                    </button>
                </div>
            </form>

            <!-- Links Footer -->
            <div class="mt-6 pt-4 border-t border-slate-100 text-center">
                <p class="text-xs text-slate-500 font-normal">
                    ¿Todavía no tienes un stand registrado?
                    <a href="{{ route('register') }}" class="text-indigo-600 hover:text-indigo-700 font-semibold transition-colors block mt-1">
                        Registrar mi Empresa Expositora &rarr;
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
