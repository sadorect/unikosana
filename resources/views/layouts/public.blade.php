<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">

    @php
        $metaTitle = trim($__env->yieldContent('title', $theme->site_name));
        $pageTitle = $metaTitle === $theme->site_name
            ? $theme->site_name
            : $metaTitle . ' — ' . $theme->site_name;
        $metaDescription = trim($__env->yieldContent('meta_description', 'Official website of the North America Unit of Unikosa — events, members, news and resources.'));
        $canonical = url()->current();
        $ogImage = $__env->yieldContent('og_image', $theme->logoUrl() ?? asset('icons/icon-512.png'));
        $ogType = $__env->yieldContent('og_type', 'website');

        $twitterHandle = ($site->twitter_url ?? false)
            ? \Illuminate\Support\Str::afterLast(rtrim($site->twitter_url, '/'), '/')
            : null;

        $structuredData = json_encode(array_filter([
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => $theme->site_name,
            'url' => url('/'),
            'logo' => $theme->logoUrl() ?? asset('icons/icon-512.png'),
            'sameAs' => array_values(array_filter([
                $site->facebook_url ?? null,
                $site->instagram_url ?? null,
                $site->twitter_url ?? null,
                $site->youtube_url ?? null,
            ])),
            'contactPoint' => array_filter([
                '@type' => 'ContactPoint',
                'contactType' => 'customer support',
                'email' => $site->contact_email ?? null,
                'telephone' => $site->contact_phone ?? null,
            ]),
        ]), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    @endphp

    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $metaDescription }}">
    <meta name="robots" content="@yield('robots', 'index, follow, max-image-preview:large')">
    <link rel="canonical" href="{{ $canonical }}">
    <meta name="theme-color" content="{{ $theme->primary_color }}">
    <meta name="author" content="{{ $theme->site_name }}">

    {{-- Open Graph --}}
    <meta property="og:site_name" content="{{ $theme->site_name }}">
    <meta property="og:type" content="{{ $ogType }}">
    <meta property="og:title" content="{{ $metaTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:url" content="{{ $canonical }}">
    <meta property="og:locale" content="en_US">
    @if ($ogImage)
        <meta property="og:image" content="{{ $ogImage }}">
    @endif

    {{-- Twitter --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $metaTitle }}">
    <meta name="twitter:description" content="{{ $metaDescription }}">
    @if ($ogImage)
        <meta name="twitter:image" content="{{ $ogImage }}">
    @endif
    @if ($twitterHandle)
        <meta name="twitter:site" content="{{ '@'.$twitterHandle }}">
    @endif

    {{-- Per-page overrides (extra structured data, prev/next, etc.) --}}
    @hasSection('og')
        @yield('og')
    @endif

    {{-- Progressive Web App --}}
    <link rel="manifest" href="{{ asset('manifest.webmanifest') }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="{{ $theme->site_name }}">
    <link rel="apple-touch-icon" href="{{ asset('icons/icon-192.png') }}">

    <link rel="icon" href="{{ $theme->faviconUrl() ?? asset('favicon.ico') }}" sizes="any">
    <link rel="icon" type="image/png" sizes="512x512" href="{{ asset('icons/icon-512.png') }}">

    {{-- Structured data: Organization --}}
    <script type="application/ld+json">{!! $structuredData !!}</script>

    <style>
        :root {
            --color-brand: {{ $theme->primary_color }};
            --color-brand-dark: {{ $theme->secondary_color }};
            --color-brand-accent: {{ $theme->accent_color }};
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-800 antialiased flex flex-col">
    @include('site.partials.nav')

    <main class="flex-1">
        @yield('content')
    </main>

    @include('site.partials.footer')
    @include('site.partials.whatsapp')
</body>
</html>
