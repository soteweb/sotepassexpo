<x-guest-layout :stand="$stand">
    <x-slot:title>{{ $stand->name }} | Participación Finalizada - {{ $stand->event_name ?? 'Expo Paraguay' }}</x-slot>
    <x-slot:robots>noindex, follow</x-slot>

    <div class="min-h-screen bg-[#f8f7f5] flex items-center justify-center p-4 antialiased">
        <div class="max-w-md w-full bg-white rounded-lg p-6 sm:p-8 border border-slate-200 shadow-sm text-center space-y-6">
            <div class="w-16 h-16 mx-auto rounded-md flex items-center justify-center text-white text-xl font-bold shadow-xs"
                 style="background-color: {{ $stand->primary_color }};">
                {{ strtoupper(substr($stand->slug, 0, 2)) }}
            </div>

            <div>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md text-[11px] font-semibold uppercase tracking-wider bg-slate-100 text-slate-700 border border-slate-200">
                    Feria / Evento Finalizado
                </span>
                <h1 class="text-xl sm:text-2xl font-bold text-slate-900 mt-3 tracking-tight">
                    {{ $stand->name }}
                </h1>
                <p class="text-xs font-mono font-semibold text-slate-400 mt-1">
                    {{ $stand->event_name ?? 'Expo Paraguay' }} &bull; {{ $stand->stand_number }}
                </p>
            </div>

            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed font-normal">
                La participación interactiva y ruleta de este stand ha concluido con el cierre oficial de la feria. ¡Agradecemos sinceramente tu visita y preferencia!
            </p>

            <div class="pt-4 border-t border-slate-100 flex flex-col gap-2.5">
                <a href="{{ route('expo.index') }}" 
                   class="w-full py-2.5 px-4 rounded-md bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs uppercase tracking-wider shadow-xs transition-all">
                    Portal SotePass Expo &rarr;
                </a>
                <p class="text-[11px] text-slate-400">
                    ¿Eres el expositor de este stand? <a href="{{ route('login') }}" class="text-slate-800 font-semibold hover:underline">Inicia sesión en tu panel</a> para descargar tu base de datos en Excel.
                </p>
            </div>
        </div>
    </div>
</x-guest-layout>

