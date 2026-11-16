<x-guest-layout>
    <x-slot:title>¡Registro Confirmado! | {{ $stand->name }}</x-slot>

    <div class="min-h-screen bg-[#f8f7f5] flex items-center justify-center p-4 antialiased">
        <div class="max-w-md w-full bg-white rounded-lg p-6 sm:p-8 border border-slate-200 shadow-sm text-center space-y-6">
            
            <!-- Logo Stand Oficial -->
            <div class="flex justify-center">
                @if($stand->logo_url)
                    <div class="w-16 h-16 rounded-md bg-white p-2 shadow-xs border border-slate-200 flex items-center justify-center overflow-hidden">
                        <img src="{{ asset($stand->logo_url) }}" alt="{{ $stand->name }}" class="max-w-full max-h-full object-contain">
                    </div>
                @else
                    <div class="w-16 h-16 rounded-md flex items-center justify-center text-white text-xl font-bold shadow-xs"
                         style="background-color: {{ $stand->primary_color }};">
                        {{ strtoupper(substr($stand->slug, 0, 2)) }}
                    </div>
                @endif
            </div>

            <div>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[11px] font-semibold uppercase tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    <span>Registro Exitoso</span>
                </span>
                <h1 class="text-xl sm:text-2xl font-bold text-slate-900 mt-2 tracking-tight">
                    ¡Gracias por tu Visita!
                </h1>
                <p class="text-xs text-slate-500 mt-1">
                    Hola <strong class="text-slate-800">{{ $lead->full_name }}</strong>, tus datos han sido registrados en <strong>{{ $stand->name }}</strong>.
                </p>
            </div>

            <!-- TICKET DIGITAL DE PARTICIPACIÓN EN SORTEO -->
            <div class="bg-slate-50/80 rounded-md p-5 border border-dashed border-slate-300 relative text-left space-y-3">
                <div class="flex items-center justify-between pb-3 border-b border-slate-200">
                    <span class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">Cupón de Sorteo</span>
                    <span class="font-mono text-xs font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200">
                        #TICKET-{{ str_pad($lead->id, 5, '0', STR_PAD_LEFT) }}
                    </span>
                </div>

                <div class="space-y-1.5 text-xs">
                    <div class="flex justify-between">
                        <span class="text-slate-400 font-medium">Participante:</span>
                        <span class="font-semibold text-slate-800">{{ $lead->full_name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400 font-medium">WhatsApp:</span>
                        <span class="font-mono font-semibold text-slate-800">{{ $lead->phone }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400 font-medium">Stand:</span>
                        <span class="font-semibold text-slate-800">{{ $stand->stand_number }} ({{ $stand->sector }})</span>
                    </div>
                    @if($draw)
                        <div class="pt-2 border-t border-slate-200 flex justify-between items-center">
                            <span class="text-slate-400 font-medium">Premio en Juego:</span>
                            <span class="font-bold text-slate-900 text-right text-[11px]">{{ $draw->prize_description }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Aviso del Sorteo en TV -->
            <div class="p-3.5 rounded-md bg-emerald-50/70 border border-emerald-100 text-emerald-950 text-xs flex items-center gap-3 text-left">
                <div class="w-9 h-9 rounded-md bg-emerald-100 text-emerald-700 flex items-center justify-center text-base shrink-0">
                    📺
                </div>
                <div>
                    <span class="font-semibold block">Sorteo Oficial en Pantalla Gigante</span>
                    <span class="text-[11px] text-emerald-800 leading-tight block mt-0.5">
                        El sorteo se realizará en vivo en la televisión del stand entre todos los visitantes registrados.
                    </span>
                </div>
            </div>

            <div class="pt-2">
                <a href="{{ route('expo.index') }}" 
                   class="w-full py-2.5 px-4 rounded-md bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs uppercase tracking-wider transition-all block text-center">
                    Conocer Más Stands de la Expo &rarr;
                </a>
            </div>

        </div>
    </div>
</x-guest-layout>

