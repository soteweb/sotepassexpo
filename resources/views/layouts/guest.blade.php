@props([
    'stand' => null,
    'description' => null,
    'keywords' => null,
    'robots' => null,
    'image' => null,
    'type' => 'website',
])

@php
    $siteName = 'SotePass Expo';
    $defaultTitle = 'SotePass Expo | Captación Digital de Leads y Ruleta para Stands en Ferias y Eventos';
    $pageTitle = isset($title) && trim((string)$title) ? trim((string)$title) : $defaultTitle;

    // Si no contiene branding y no es una vista standalone, asegurar coherencia de marca
    if (!str_contains($pageTitle, 'SotePass') && !str_contains($pageTitle, 'Expo')) {
        $pageTitle .= ' | ' . $siteName;
    }

    $defaultDesc = 'Plataforma B2B para stands en ferias y exposiciones comerciales en Paraguay. Captá contactos calificados con WhatsApp verificado, ruleta digital interactiva y sorteos en pantalla TV.';
    $pageDesc = isset($description) && trim((string)$description) 
        ? trim((string)$description) 
        : ($stand ? ('Acreditación digital y ruleta interactiva para visitantes en el stand de ' . $stand->name . ' en ' . ($stand->event_name ?? 'la expo') . '. Participá por premios y cupones exclusivos.') : $defaultDesc);

    $defaultKeywords = 'captacion de leads, ruleta para stands, ruleta interactiva, exposiciones paraguay, ferias comerciales, sorteo tv, marketing ferial b2b, acreditacion digital qr, expo paraguay, agrodinamica, innovar, sotepass expo, soteweb';
    $pageKeywords = isset($keywords) && trim((string)$keywords) ? trim((string)$keywords) : $defaultKeywords;

    $pageRobots = isset($robots) && trim((string)$robots) 
        ? trim((string)$robots) 
        : 'index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1';

    $defaultImage = asset('images/hero-expo-paraguay.jpg');
    $pageImage = isset($image) && trim((string)$image) ? trim((string)$image) : $defaultImage;

    $canonicalUrl = url()->current();
    $themeColor = ($stand && $stand->primary_color) ? $stand->primary_color : '#059669';

    $schemaJson = [
        '@context' => 'https://schema.org',
        '@graph' => [
            [
                '@type' => 'Organization',
                '@id' => url('/') . '/#organization',
                'name' => 'Soteweb Tecnología',
                'url' => 'https://sotepass.com',
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => asset('favicon.svg'),
                ],
                'contactPoint' => [
                    '@type' => 'ContactPoint',
                    'telephone' => '+' . ltrim((string) config('expo.bank.whatsapp_support', '595981123456'), '+'),
                    'contactType' => 'customer service',
                    'areaServed' => 'PY',
                    'availableLanguage' => 'es',
                ],
            ],
            [
                '@type' => 'WebSite',
                '@id' => url('/') . '/#website',
                'url' => url('/'),
                'name' => 'SotePass Expo',
                'description' => 'Plataforma de Captación Digital de Leads y Ruleta para Stands en Ferias y Eventos Comerciales',
                'publisher' => [
                    '@id' => url('/') . '/#organization',
                ],
                'inLanguage' => 'es-PY',
            ],
            [
                '@type' => 'SoftwareApplication',
                'name' => 'SotePass Expo',
                'operatingSystem' => 'All',
                'applicationCategory' => 'BusinessApplication',
                'offers' => [
                    '@type' => 'AggregateOffer',
                    'priceCurrency' => 'PYG',
                    'lowPrice' => '490000',
                    'highPrice' => '1490000',
                ],
                'description' => 'Software B2B para stands en ferias y exposiciones con captación por QR, ruleta interactiva de premios y sorteos en pantalla TV.',
            ],
        ],
    ];
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Metas Principales para Motores de Búsqueda (Google, Bing) -->
    <title>{{ $pageTitle }}</title>
    <meta name="title" content="{{ $pageTitle }}">
    <meta name="description" content="{{ $pageDesc }}">
    <meta name="keywords" content="{{ $pageKeywords }}">
    <meta name="robots" content="{{ $pageRobots }}">
    <meta name="googlebot" content="{{ $pageRobots }}">
    <meta name="author" content="Soteweb Tecnología">
    <meta name="publisher" content="SotePass Expo">
    <meta name="theme-color" content="{{ $themeColor }}">
    <link rel="canonical" href="{{ $canonicalUrl }}">

    <!-- Open Graph / WhatsApp / Facebook / LinkedIn / Telegram -->
    <meta property="og:type" content="{{ $type ?? 'website' }}">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $pageDesc }}">
    <meta property="og:image" content="{{ $pageImage }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="{{ $pageTitle }}">
    <meta property="og:locale" content="es_PY">

    <!-- Twitter Cards -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ $canonicalUrl }}">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $pageDesc }}">
    <meta name="twitter:image" content="{{ $pageImage }}">

    <!-- Favicon Oficial SotePass Expo -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="alternate icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">

    <!-- Google Fonts: Plus Jakarta Sans & Share Tech Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&family=Share+Tech+Mono&display=swap" rel="stylesheet" />

    <!-- Datos Estructurados JSON-LD Schema.org para Google Rich Snippets -->
    <script type="application/ld+json">
    {!! json_encode($schemaJson, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) !!}
    </script>
    @if(isset($structuredData))
        {{ $structuredData }}
    @endif

    <!-- Tailwind CSS CDN con la paleta y fuentes de SotePass -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'Inter', 'system-ui', 'sans-serif'],
                        mono: ['"Share Tech Mono"', 'monospace'],
                    },
                    colors: {
                        gold: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            200: '#c7d2fe',
                            300: '#a5b4fc',
                            400: '#818cf8',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            800: '#3730a3',
                            900: '#312e81',
                            950: '#1e1b4b',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Canvas Confetti -->
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-digital { font-family: 'Share Tech Mono', monospace; }
        [x-cloak] { display: none !important; }
    </style>

    @livewireStyles
</head>
<body class="min-h-full font-sans antialiased text-slate-800 bg-[#f8f7f5] selection:bg-gold-500 selection:text-white">

    {{ $slot }}

    @livewireScripts
</body>
</html>

