@php
    $navLinks = [
        ['label' => 'Home', 'route' => 'home'],
        ['label' => 'About', 'route' => 'about'],
        ['label' => 'Leadership', 'route' => 'leadership'],
        ['label' => 'Members', 'route' => 'members'],
        ['label' => 'Events', 'route' => 'events.index'],
        ['label' => 'Gallery', 'route' => 'gallery.index'],
        ['label' => 'News', 'route' => 'news.index'],
        ['label' => 'Blog', 'route' => 'blog'],
        ['label' => 'Resources', 'route' => 'resources'],
        ['label' => 'FAQ', 'route' => 'faq'],
        ['label' => 'Donate', 'route' => 'donate'],
        ['label' => 'Contact', 'route' => 'contact'],
    ];
@endphp

<header data-nav class="sticky top-0 z-40 bg-white/95 backdrop-blur border-b border-slate-200 shadow-sm">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between gap-4">
            <a href="{{ route('home') }}" class="flex items-center gap-3 shrink-0">
                @if ($theme->logo_path)
                    <img src="{{ asset('storage/' . $theme->logo_path) }}" alt="{{ $theme->site_name }}" class="h-10 w-auto">
                @else
                    <span class="grid h-10 w-10 place-items-center rounded-lg text-white font-bold" style="background-color: var(--color-brand)">U</span>
                @endif
                <span class="font-semibold text-slate-900 leading-tight hidden sm:block">{{ $theme->site_name }}</span>
            </a>

            <nav class="hidden lg:flex items-center gap-1">
                @foreach ($navLinks as $link)
                    <a href="{{ route($link['route']) }}"
                       @class([
                           'px-3 py-2 rounded-md text-sm font-medium transition',
                           'text-white' => request()->routeIs($link['route']),
                           'text-slate-600 hover:text-slate-900 hover:bg-slate-100' => ! request()->routeIs($link['route']),
                       ])
                       @if (request()->routeIs($link['route'])) style="background-color: var(--color-brand)" @endif>
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </nav>

            <div class="flex items-center gap-2">
                <form action="{{ route('search') }}" method="GET" class="hidden md:block">
                    <input type="search" name="q" placeholder="Search…"
                           class="w-36 rounded-full border border-slate-300 px-4 py-1.5 text-sm focus:border-[var(--color-brand)] focus:outline-none focus:ring-1 focus:ring-[var(--color-brand)]">
                </form>
                @auth
                    <a href="{{ route('member.dashboard') }}" class="hidden sm:inline-flex rounded-full px-4 py-1.5 text-sm font-semibold text-white" style="background-color: var(--color-brand)">My Membership</a>
                @else
                    <a href="{{ route('member.login') }}" class="hidden sm:inline-flex rounded-full px-4 py-1.5 text-sm font-semibold text-white" style="background-color: var(--color-brand)">Member Login</a>
                @endauth
                <button data-nav-toggle type="button" class="lg:hidden inline-flex items-center justify-center rounded-md p-2 text-slate-600 hover:bg-slate-100" aria-label="Toggle menu">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>
        </div>
    </div>

    <div data-nav-menu class="hidden lg:hidden border-t border-slate-200 bg-white">
        <nav class="space-y-1 px-4 py-3">
            @foreach ($navLinks as $link)
                <a href="{{ route($link['route']) }}"
                   @class([
                       'block rounded-md px-3 py-2 text-base font-medium',
                       'bg-slate-100 text-slate-900' => request()->routeIs($link['route']),
                       'text-slate-600 hover:bg-slate-50' => ! request()->routeIs($link['route']),
                   ])>
                    {{ $link['label'] }}
                </a>
            @endforeach
            <form action="{{ route('search') }}" method="GET" class="pt-2">
                <input type="search" name="q" placeholder="Search…" class="w-full rounded-md border border-slate-300 px-4 py-2 text-sm">
            </form>
        </nav>
    </div>
</header>
