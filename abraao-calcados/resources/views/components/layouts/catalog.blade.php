@props([
    'title' => null,
    'description' => null,
    'keywords' => null,
    'canonical' => null,
    'ogTitle' => null,
    'ogDescription' => null,
    'ogType' => 'website',
    'ogImage' => null,
])

@php
    $storeName = config('store.name');
    $storeAddr = config('store.address');
    $storePhone = config('store.phone');
    $whatsapp = config('store.whatsapp_number');
    $phoneClean = preg_replace('/\D/', '', $storePhone);

    $defaultTitle = $storeName . ' | Calçados em Rio Branco - AC | Tênis, Sandálias, Sapatos';
    $defaultDesc = 'Loja de calçados em Rio Branco, Acre. Encontre tênis, sandálias, sapatos sociais, chinelos e botas com os melhores preços. Compre pelo WhatsApp! ' . $storeAddr;
    $defaultKeywords = 'calçados rio branco, loja de sapatos rio branco, tênis rio branco acre, sandálias rio branco, sapatos sociais acre, chinelos rio branco, botas rio branco, abraão calçados, comprar calçados acre, loja de tênis rio branco, promoção calçados rio branco';
    $defaultOgTitle = $storeName . ' - Calçados em Rio Branco, AC';
    $defaultOgDesc = 'As melhores promoções em calçados de Rio Branco! Tênis, sandálias, sapatos sociais, chinelos e botas. Compre pelo WhatsApp.';

    // JSON-LD gerado via PHP para evitar conflito com o Blade
    $localBusinessJsonLd = json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'ShoeStore',
        'name' => $storeName,
        'description' => 'Loja de calçados em Rio Branco, Acre. Tênis, sandálias, sapatos sociais, chinelos e botas com os melhores preços da região.',
        'url' => url('/'),
        'telephone' => '+55' . $phoneClean,
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => 'Estr. Juarez Távora, 206',
            'addressLocality' => 'Rio Branco',
            'addressRegion' => 'AC',
            'postalCode' => '69921-248',
            'addressCountry' => 'BR',
        ],
        'geo' => [
            '@type' => 'GeoCoordinates',
            'latitude' => -9.9753,
            'longitude' => -67.8248,
        ],
        'openingHoursSpecification' => [
            '@type' => 'OpeningHoursSpecification',
            'dayOfWeek' => ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'],
            'opens' => '08:00',
            'closes' => '18:00',
        ],
        'priceRange' => '$$',
        'areaServed' => [
            '@type' => 'City',
            'name' => 'Rio Branco',
        ],
        'sameAs' => ['https://wa.me/' . $whatsapp],
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
@endphp

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- SEO: Title & Meta --}}
    <title>{{ $title ?? $defaultTitle }}</title>
    <meta name="description" content="{{ $description ?? $defaultDesc }}">
    <meta name="keywords" content="{{ $keywords ?? $defaultKeywords }}">
    <meta name="author" content="{{ $storeName }}">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ $canonical ?? url()->current() }}">

    {{-- SEO: Geo Tags (Local) --}}
    <meta name="geo.region" content="BR-AC">
    <meta name="geo.placename" content="Rio Branco">
    <meta name="geo.position" content="-9.9753;-67.8248">
    <meta name="ICBM" content="-9.9753, -67.8248">

    {{-- SEO: Open Graph --}}
    <meta property="og:title" content="{{ $ogTitle ?? $defaultOgTitle }}" />
    <meta property="og:description" content="{{ $ogDescription ?? $defaultOgDesc }}" />
    <meta property="og:type" content="{{ $ogType }}" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:locale" content="pt_BR" />
    <meta property="og:site_name" content="{{ $storeName }}" />
    @if($ogImage)<meta property="og:image" content="{{ $ogImage }}" />@endif

    {{-- SEO: Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $ogTitle ?? $defaultOgTitle }}">
    <meta name="twitter:description" content="{{ $ogDescription ?? $defaultOgDesc }}">
    @if($ogImage)<meta name="twitter:image" content="{{ $ogImage }}">@endif

    {{-- SEO: JSON-LD (gerado via PHP, sem conflito Blade) --}}
    <script type="application/ld+json">{!! $localBusinessJsonLd !!}</script>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    {{-- Tailwind CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50:  '#FFFBEB',
                            100: '#FEF3C7',
                            200: '#FDE68A',
                            300: '#FCD34D',
                            400: '#FBBF24',
                            500: '#F59E0B',
                            600: '#D97706',
                            700: '#B45309',
                            800: '#92400E',
                            900: '#78350F',
                            950: '#451A03',
                        },
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-out',
                        'slide-up': 'slideUp 0.4s ease-out',
                        'pulse-soft': 'pulseSoft 2s ease-in-out infinite',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        slideUp: {
                            '0%': { opacity: '0', transform: 'translateY(20px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                        pulseSoft: {
                            '0%, 100%': { opacity: '1' },
                            '50%': { opacity: '0.85' },
                        },
                    },
                },
            },
        }
    </script>

    <style>
        body { font-family: 'Inter', system-ui, sans-serif; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        .glass {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        .img-load { transition: opacity 0.3s ease; }
    </style>

    @livewireStyles
</head>
<body class="antialiased bg-stone-50 text-gray-900">
    {{ $slot }}

    @livewireScripts
</body>
</html>
