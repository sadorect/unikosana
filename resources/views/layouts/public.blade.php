<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', $theme->site_name) — {{ $theme->site_name }}</title>
    <meta name="description" content="@yield('meta_description', 'Official website of the North America Unit of Unikosa — events, members, news and resources.')">

    @hasSection('og')
        @yield('og')
    @endif

    @if ($theme->faviconUrl())
        <link rel="icon" href="{{ $theme->faviconUrl() }}">
    @endif

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
