<x-guest-layout>
    <x-slot:title>¡Registro Confirmado! | {{ $stand->name }}</x-slot>

    <div class="min-h-screen bg-[#f8f7f5] flex items-center justify-center p-4 antialiased">
        <div class="max-w-md w-full bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xl text-center space-y-6">
            
            <!-- Logo Stand Oficial -->
            <div class="flex justify-center">
                @if($stand->logo_url)
                    <div class="w-20 h-20 rounded-3xl bg-white p-2 shadow-xl border-2 border-slate-100 flex items-center justify-center overflow-hidden">
                        <img src="{{ asset($stand->logo_url) }}" alt="{{ $stand->name }}" class="max-w-full max-h-full object-contain">
                    </div>
                @else
                    <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-white text-xl font-black shadow-md"
                         style="background-color: {{ $stand->primary_color }};">
                        {{ strtoupper(substr($stand->slug, 0, 2)) }}
                    </div>
                @endif
            </div>

            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    <span>Registro Exitoso</span>
                </span>
                <h1 class="text-2xl font-black text-slate-900 mt-2">
                    ¡Gracias por tu Visita!
                </h1>
                <p class="text-xs text-slate-500 mt-1">
                    Hola <strong>{{ $lead->full_name }}</strong>, tus datos han sido registrados en <strong>{{ $stand->name }}</strong>.
                </p>
            </div>

            <!-- TICKET DIGITAL DE PARTICIPACIÓN EN SORTEO -->
            <div class="bg-slate-50 rounded-2xl p-5 border-2 border-dashed border-slate-300 relative text-left space-y-3">
                <div class="flex items-center justify-between pb-3 border-b border-slate-200">
                    <span class="text-[10px] font-black uppercase tracking-wider text-slate-400">Cupón de Sorteo</span>
                    <span class="font-mono text-sm font-black text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-lg border border-emerald-200">
                        #TICKET-{{ str_pad($lead->id, 5, '0', STR_PAD_LEFT) }}
                    </span>
                </div>

                <div class="space-y-1.5 text-xs">
                    <div class="flex justify-between">
                        <span class="text-slate-400 font-medium">Participante:</span>
                        <span class="font-bold text-slate-800">{{ $lead->full_name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400 font-medium">WhatsApp:</span>
                        <span class="font-mono font-bold text-slate-800">{{ $lead->phone }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400 font-medium">Stand:</span>
                        <span class="font-bold text-slate-800">{{ $stand->stand_number }} ({{ $stand->sector }})</span>
                    </div>
                    @if($draw)
                        <div class="pt-2 border-t border-slate-200 flex justify-between items-center">
                            <span class="text-slate-400 font-medium">Premio en Juego:</span>
                            <span class="font-black text-gold-700 text-right text-[11px]">{{ $draw->prize_description }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Aviso del Sorteo en TV -->
            <div class="p-3.5 rounded-2xl bg-indigo-50 border border-indigo-100 text-indigo-900 text-xs flex items-center gap-3 text-left">
                <div class="w-9 h-9 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center text-lg shrink-0">
                    📺
                </div>
                <div>
                    <span class="font-bold block">Sorteo Oficial en Pantalla Gigante</span>
                    <span class="text-[11px] text-indigo-700 leading-tight block mt-0.5">
                        El sorteo se realizará en vivo en la televisión del stand entre todos los visitantes registrados.
                    </span>
                </div>
            </div>

            <div class="pt-2">
                <a href="{{ route('expo.index') }}" 
                   class="w-full py-3 px-4 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs uppercase tracking-wider transition-all block">
                    Conocer Más Stands de la Expo &rarr;
                </a>
            </div>

        </div>
    </div>
</x-guest-layout>

