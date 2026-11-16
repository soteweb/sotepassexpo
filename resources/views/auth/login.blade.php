<x-guest-layout>
    <x-slot:title>Iniciar Sesión | SotePass Expo</x-slot>

    <div class="min-h-screen flex flex-col justify-center items-center py-10 px-4 sm:px-6 bg-[#f8f7f5] antialiased">
        <!-- Brand Logo SotePass Expo -->
        <div class="mb-6 text-center">
            <a href="{{ route('expo.index') }}" class="inline-flex items-center gap-2.5 group transition-transform hover:scale-105">
                <div class="w-11 h-11 bg-gold-500 rounded-2xl flex items-center justify-center shadow-lg shadow-gold-200 text-white font-bold">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="text-2xl font-black text-slate-900 tracking-tight">
                    Sote<span class="text-gold-700">Pass</span>
                    <span class="text-xs uppercase px-1.5 py-0.5 rounded-md bg-gold-50 text-gold-800 font-black tracking-wider ml-1 border border-gold-200">
                        Expo
                    </span>
                </div>
            </a>
            <p class="text-xs text-slate-500 mt-2 font-medium">Plataforma de Captación y Ruleta B2B para Stands</p>
        </div>

        <!-- Auth Card -->
        <div class="w-full sm:max-w-md bg-white shadow-xl shadow-slate-200/60 border border-slate-200/80 rounded-3xl p-6 sm:p-8">
            <div class="mb-6 text-center">
                <h2 class="text-2xl font-black text-slate-900 tracking-tight">Acceso a tu Panel</h2>
                <p class="text-xs text-slate-500 mt-1 font-medium">Ingresa tus credenciales de empresa o administrador</p>
            </div>

            @if(session('error'))
                <div class="mb-4 p-3 rounded-2xl bg-rose-50 border border-rose-200 text-xs font-bold text-rose-700">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <!-- Correo Electrónico -->
                <div>
                    <x-input-label for="email" value="Correo Electrónico *" />
                    <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="empresa@ejemplo.com" class="w-full" />
                    <x-input-error :messages="$errors->get('email')" />
                </div>

                <!-- Contraseña -->
                <div>
                    <x-input-label for="password" value="Contraseña *" />
                    <x-text-input id="password" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" class="w-full" />
                    <x-input-error :messages="$errors->get('password')" />
                </div>

                <!-- Recordarme -->
                <div class="flex items-center justify-between text-xs pt-1">
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" name="remember" class="rounded border-slate-300 text-gold-600 focus:ring-gold-500">
                        <span class="font-medium text-slate-600">Recordar mi sesión</span>
                    </label>
                </div>

                <div class="pt-2">
                    <button type="submit" 
                            class="w-full py-3.5 px-6 bg-gold-500 hover:bg-gold-600 text-white font-bold text-xs uppercase tracking-widest rounded-2xl shadow-lg shadow-gold-500/25 transition-all duration-200 active:scale-95 cursor-pointer">
                        Ingresar al Panel
                    </button>
                </div>
            </form>

            <!-- Links Footer -->
            <div class="mt-6 pt-5 border-t border-slate-100 text-center">
                <p class="text-xs text-slate-500 font-medium">
                    ¿Todavía no tienes un stand registrado?
                    <a href="{{ route('register') }}" class="text-gold-700 hover:text-gold-800 font-bold underline transition-colors block mt-1">
                        Registrar mi Empresa Expositora &rarr;
                    </a>
                </p>
            </div>
        </div>

        <!-- Footer Soteweb -->
        <div class="mt-8 text-center max-w-md px-4">
            <p class="text-xs text-slate-500 font-medium leading-relaxed">
                SotePass&reg; Expo es un producto digital desarrollado por <a href="https://soteweb.com/" target="_blank" rel="noopener noreferrer" class="text-gold-700 hover:text-gold-800 font-bold underline transition-colors">Soteweb Tecnología</a>
            </p>
        </div>
    </div>
</x-guest-layout>

