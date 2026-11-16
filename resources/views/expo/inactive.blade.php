<x-guest-layout>
    <x-slot:title>{{ $stand->name }} | Stand en Preparación</x-slot>

    <div class="min-h-screen bg-[#f8f7f5] flex items-center justify-center p-4 antialiased">
        <div class="max-w-md w-full bg-white rounded-3xl p-8 border border-slate-200 shadow-xl text-center space-y-6">
            <div class="w-20 h-20 mx-auto rounded-3xl flex items-center justify-center text-white text-2xl font-black shadow-md"
                 style="background-color: {{ $stand->primary_color }};">
                {{ strtoupper(substr($stand->slug, 0, 2)) }}
            </div>

            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-amber-50 text-amber-800 border border-amber-200">
                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                    Stand en Preparación
                </span>
                <h1 class="text-2xl font-black text-slate-900 mt-3">
                    {{ $stand->name }}
                </h1>
                <p class="text-xs font-mono font-bold text-slate-400 mt-1">
                    {{ $stand->stand_number }} &bull; {{ $stand->sector }}
                </p>
            </div>

            <p class="text-sm text-slate-600 leading-relaxed font-medium">
                La experiencia interactiva y la ruleta de este stand se encuentran temporalmente en proceso de habilitación por la organización de la feria.
            </p>

            <div class="pt-4 border-t border-slate-100 flex flex-col gap-2.5">
                <a href="{{ route('expo.index') }}" 
                   class="w-full py-3 px-4 rounded-2xl bg-gold-500 hover:bg-gold-600 text-white font-bold text-xs uppercase tracking-wider shadow-md transition-all">
                    Explorar la Feria SotePass Expo &rarr;
                </a>
                <p class="text-[11px] text-slate-400">
                    ¿Eres el expositor de este stand? <a href="{{ route('login') }}" class="text-gold-700 font-bold hover:underline">Inicia sesión</a> en tu panel.
                </p>
            </div>
        </div>
    </div>
</x-guest-layout>

