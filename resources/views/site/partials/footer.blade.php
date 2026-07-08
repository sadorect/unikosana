@php
    $socials = array_filter([
        'Facebook' => $site->facebook_url,
        'Instagram' => $site->instagram_url,
        'X' => $site->twitter_url,
        'YouTube' => $site->youtube_url,
        'WhatsApp' => $site->whatsapp_url,
    ]);
@endphp

<footer class="mt-auto text-slate-300" style="background-color: var(--color-brand-dark)">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12 grid gap-8 md:grid-cols-5">
        <div class="md:col-span-2">
            <div class="flex items-center gap-3">
                @if ($theme->logoUrl())
                    <img src="{{ $theme->logoUrl() }}" alt="{{ $theme->site_name }}" class="h-10 w-auto bg-white/90 rounded p-1">
                @endif
                <span class="text-lg font-semibold text-white">{{ $theme->site_name }}</span>
            </div>
            <p class="mt-4 max-w-md text-sm text-slate-300/90">
                The official platform of the North America Unit of Unikosa — documenting our activities, celebrating our members, and keeping our community connected.
            </p>
        </div>

        <div>
            <h3 class="text-sm font-semibold uppercase tracking-wider text-white">Quick Links</h3>
            <ul class="mt-4 space-y-2 text-sm">
                <li><a href="{{ route('about') }}" class="hover:text-white">About Us</a></li>
                <li><a href="{{ route('events.index') }}" class="hover:text-white">Events</a></li>
                <li><a href="{{ route('members') }}" class="hover:text-white">Members</a></li>
                <li><a href="{{ route('resources') }}" class="hover:text-white">Resources</a></li>
                <li><a href="{{ route('contact') }}" class="hover:text-white">Contact</a></li>
            </ul>
        </div>

        <div>
            <h3 class="text-sm font-semibold uppercase tracking-wider text-white">Newsletter</h3>
            @if (session('newsletter_success'))
                <p class="mt-4 rounded-lg bg-white/10 p-3 text-sm text-white">{{ session('newsletter_success') }}</p>
            @else
                <p class="mt-4 text-sm text-slate-300/90">Get news and event updates in your inbox.</p>
                <form action="{{ route('newsletter.subscribe') }}" method="POST" class="mt-3 space-y-2">
                    @csrf
                    <input type="email" name="email" required placeholder="you@example.com"
                           class="w-full rounded-lg border-0 px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2" style="--tw-ring-color: var(--color-brand-accent)">
                    <button type="submit" class="w-full rounded-lg px-3 py-2 text-sm font-semibold text-white" style="background-color: var(--color-brand-accent)">Subscribe</button>
                </form>
                @error('email')<p class="mt-1 text-xs text-red-300">{{ $message }}</p>@enderror
            @endif
        </div>

        <div>
            <h3 class="text-sm font-semibold uppercase tracking-wider text-white">Get in Touch</h3>
            <ul class="mt-4 space-y-2 text-sm">
                @if ($site->contact_email)
                    <li><a href="mailto:{{ $site->contact_email }}" class="hover:text-white">{{ $site->contact_email }}</a></li>
                @endif
                @if ($site->contact_phone)
                    <li><a href="tel:{{ $site->contact_phone }}" class="hover:text-white">{{ $site->contact_phone }}</a></li>
                @endif
                @if ($site->address)
                    <li class="text-slate-300/90">{{ $site->address }}</li>
                @endif
            </ul>
            @if (count($socials))
                <div class="mt-4 flex flex-wrap gap-3 text-sm">
                    @foreach ($socials as $label => $url)
                        <a href="{{ $url }}" target="_blank" rel="noopener" class="rounded-full bg-white/10 px-3 py-1 hover:bg-white/20">{{ $label }}</a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <div class="border-t border-white/10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-4 text-center text-xs text-slate-400">
            &copy; {{ date('Y') }} {{ $theme->site_name }}. All rights reserved.
        </div>
    </div>
</footer>
