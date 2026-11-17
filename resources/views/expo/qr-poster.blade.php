<x-guest-layout :stand="$stand">
    <x-slot:title>Cartel QR Imprimible A4 | {{ $stand->name }} - SotePass Expo</x-slot>
    <x-slot:robots>noindex, nofollow</x-slot>

    <!-- Librería QRCode.js para generación vectorial/canvas instantánea -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <style>
        @media print {
            @page {
                size: A4 portrait;
                margin: 10mm;
            }
            body {
                background: #ffffff !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .no-print {
                display: none !important;
            }
            .print-sheet {
                box-shadow: none !important;
                border: 2px solid #e2e8f0 !important;
                margin: 0 auto !important;
                width: 100% !important;
                max-width: 190mm !important;
            }
        }
    </style>

    <div class="min-h-screen bg-slate-100 py-6 px-4 flex flex-col items-center antialiased">

        <!-- Barra de Herramientas Superior (No sale en la impresión) -->
        <div class="no-print w-full max-w-[210mm] mb-5 bg-slate-900 text-white rounded-lg p-3.5 sm:px-5 flex flex-wrap items-center justify-between gap-3 shadow-md">
            <div class="flex items-center gap-2.5">
                <a href="{{ route('expo.panel', $stand->slug) }}"
                   class="px-3 py-1.5 rounded-md bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold border border-slate-700 transition">
                    &larr; Volver al Panel
                </a>
                <span class="text-xs text-slate-300 font-medium hidden sm:inline">
                    Lámina Oficial A4 para Acrílico o Mostrador de Stand
                </span>
            </div>

            <div class="flex items-center gap-2">
                <button type="button" onclick="downloadHighResQR()"
                        class="px-3.5 py-2 rounded-md bg-slate-800 hover:bg-slate-700 text-white font-semibold text-xs border border-slate-700 transition cursor-pointer flex items-center gap-1.5">
                    <span>⬇️ Descargar QR Solo (PNG)</span>
                </button>
                <button type="button" onclick="window.print()"
                        class="px-4 py-2 rounded-md bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs uppercase tracking-wider shadow-xs transition cursor-pointer flex items-center gap-1.5">
                    <span>🖨️ Imprimir Cartel A4</span>
                </button>
            </div>
        </div>

        <!-- LÁMINA A4 LISTA PARA IMPRIMIR -->
        <div class="print-sheet w-full max-w-[210mm] bg-white rounded-xl border border-slate-200 shadow-xl overflow-hidden flex flex-col justify-between"
             style="min-height: 270mm;">

            <!-- Franja Superior con Color Corporativo -->
            <div>
                <div class="h-4 w-full" style="background: linear-gradient(90deg, {{ $stand->primary_color }}, {{ $stand->secondary_color ?? '#0f172a' }});"></div>

                <!-- Cabecera de Marca del Expositor -->
                <div class="p-8 sm:p-10 pb-6 text-center border-b border-slate-100">
                    <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-md bg-slate-100 border border-slate-200 text-xs font-bold uppercase tracking-wider text-slate-700 mb-4">
                        <span>🎪 {{ $stand->event_name ?? 'Expo Paraguay' }}</span>
                        <span>&bull;</span>
                        <span>{{ $stand->stand_number }}</span>
                    </div>

                    @if($stand->logo_url)
                        <div class="h-20 flex items-center justify-center mb-3">
                            <img src="{{ asset($stand->logo_url) }}" alt="{{ $stand->name }}" class="max-h-20 max-w-[240px] object-contain">
                        </div>
                    @else
                        <div class="w-16 h-16 mx-auto rounded-lg flex items-center justify-center text-white font-extrabold text-2xl mb-3 shadow-xs"
                             style="background-color: {{ $stand->primary_color }};">
                            {{ strtoupper(substr($stand->slug, 0, 2)) }}
                        </div>
                    @endif

                    <h1 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">
                        {{ $stand->name }}
                    </h1>
                    @if($stand->tagline)
                        <p class="text-sm text-slate-500 mt-1.5 max-w-lg mx-auto font-medium">
                            {{ $stand->tagline }}
                        </p>
                    @endif
                </div>
            </div>

            <!-- Cuerpo Central con Llamado a la Acción y QR Gigante -->
            <div class="px-8 sm:px-12 py-6 text-center flex-1 flex flex-col items-center justify-center">
                <div class="inline-block px-4 py-1.5 rounded-md text-white font-bold text-xs uppercase tracking-widest mb-3 shadow-xs"
                     style="background-color: {{ $stand->primary_color }};">
                    {{ $stand->allow_spins ? '🎡 Ruleta Interactiva & Premios' : '🎟️ Sorteo Oficial en Vivo' }}
                </div>

                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight max-w-lg leading-tight">
                    ¡Escaneá el Código QR con tu Celular y Participá!
                </h2>
                <p class="text-sm text-slate-600 mt-2 max-w-md">
                    {{ $stand->allow_spins 
                        ? 'Registrate en segundos sin descargar aplicaciones y girá la ruleta digital para ganar premios instantáneos.' 
                        : 'Acreditá tu visita en segundos desde tu teléfono y obtené tu cupón digital para nuestro gran sorteo.' }}
                </p>

                <!-- Marco del Código QR -->
                <div class="my-6 p-6 bg-white rounded-xl border-4 shadow-sm inline-flex flex-col items-center justify-center relative"
                     style="border-color: {{ $stand->primary_color }};">
                    <div id="posterQrContainer" class="p-2 bg-white flex items-center justify-center"></div>
                    <span class="mt-3 text-[11px] font-mono font-bold text-slate-500 bg-slate-100 px-3 py-1 rounded-sm border border-slate-200">
                        {{ route('expo.stand', $stand->slug) }}
                    </span>
                </div>

                <!-- Pasos de Instrucción -->
                <div class="grid grid-cols-3 gap-4 w-full max-w-lg mt-2 text-left">
                    <div class="p-3.5 rounded-lg bg-slate-50 border border-slate-200">
                        <div class="text-xs font-extrabold uppercase tracking-wider mb-1" style="color: {{ $stand->primary_color }};">Paso 1</div>
                        <div class="text-xs font-bold text-slate-900">Escaneá el QR</div>
                        <div class="text-[11px] text-slate-500 mt-0.5 leading-snug">Abrí la cámara de tu celular y apuntá al código.</div>
                    </div>
                    <div class="p-3.5 rounded-lg bg-slate-50 border border-slate-200">
                        <div class="text-xs font-extrabold uppercase tracking-wider mb-1" style="color: {{ $stand->primary_color }};">Paso 2</div>
                        <div class="text-xs font-bold text-slate-900">Completá tus datos</div>
                        <div class="text-[11px] text-slate-500 mt-0.5 leading-snug">Ingresá tu nombre y WhatsApp en 15 segundos.</div>
                    </div>
                    <div class="p-3.5 rounded-lg bg-slate-50 border border-slate-200">
                        <div class="text-xs font-extrabold uppercase tracking-wider mb-1" style="color: {{ $stand->primary_color }};">Paso 3</div>
                        <div class="text-xs font-bold text-slate-900">{{ $stand->allow_spins ? '¡Girá y Ganá!' : '¡Ya Participás!' }}</div>
                        <div class="text-[11px] text-slate-500 mt-0.5 leading-snug">{{ $stand->allow_spins ? 'Probá tu suerte en la ruleta y canjeá en el stand.' : 'Recibí tu ticket digital verificado en pantalla.' }}</div>
                    </div>
                </div>
            </div>

            <!-- Pie de Página del Cartel -->
            <div class="px-8 py-4 bg-slate-50 border-t border-slate-200 flex items-center justify-between text-[11px] text-slate-500">
                <div class="font-semibold text-slate-700">
                    {{ $stand->name }} &bull; {{ $stand->sector }}
                </div>
                <div>
                    Tecnología de Acreditación Digital por <strong>SotePass&reg; Expo</strong>
                </div>
            </div>

        </div>

    </div>

    <script>
        const standPublicUrl = @json(route('expo.stand', $stand->slug));
        const standSlug = @json($stand->slug);

        document.addEventListener('DOMContentLoaded', function () {
            const container = document.getElementById('posterQrContainer');
            if (container && typeof QRCode !== 'undefined') {
                new QRCode(container, {
                    text: standPublicUrl,
                    width: 280,
                    height: 280,
                    colorDark: '#0f172a',
                    colorLight: '#ffffff',
                    correctLevel: QRCode.CorrectLevel.H
                });
            }
        });

        function downloadHighResQR() {
            const tempDiv = document.createElement('div');
            tempDiv.style.display = 'none';
            document.body.appendChild(tempDiv);

            new QRCode(tempDiv, {
                text: standPublicUrl,
                width: 900,
                height: 900,
                colorDark: '#0f172a',
                colorLight: '#ffffff',
                correctLevel: QRCode.CorrectLevel.H
            });

            setTimeout(() => {
                const sourceCanvas = tempDiv.querySelector('canvas');
                if (sourceCanvas) {
                    const finalCanvas = document.createElement('canvas');
                    finalCanvas.width = 1000;
                    finalCanvas.height = 1000;
                    const ctx = finalCanvas.getContext('2d');
                    ctx.fillStyle = '#ffffff';
                    ctx.fillRect(0, 0, 1000, 1000);
                    ctx.drawImage(sourceCanvas, 50, 50, 900, 900);

                    const link = document.createElement('a');
                    link.download = 'qr-stand-' + standSlug + '.png';
                    link.href = finalCanvas.toDataURL('image/png');
                    link.click();
                }
                document.body.removeChild(tempDiv);
            }, 150);
        }
    </script>
</x-guest-layout>
