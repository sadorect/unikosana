@extends('layouts.public')

@section('title', 'Home')

@section('content')
    {{-- Hero --}}
    <section class="relative overflow-hidden" style="background-color: var(--color-brand-dark)">
        @if ($home->hero_image_path)
            <img src="{{ asset('storage/' . $home->hero_image_path) }}" alt="" class="absolute inset-0 h-full w-full object-cover opacity-25">
        @endif
        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-24 sm:py-32 text-center">
            <h1 class="mx-auto max-w-3xl text-4xl font-extrabold tracking-tight text-white sm:text-5xl">
                {{ $home->hero_heading }}
            </h1>
            @if ($home->hero_subheading)
                <p class="mx-auto mt-5 max-w-2xl text-lg text-slate-200">{{ $home->hero_subheading }}</p>
            @endif
            <div class="mt-8 flex flex-wrap justify-center gap-3">
                <a href="{{ route('about') }}" class="rounded-full px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:opacity-90" style="background-color: var(--color-brand)">Learn about us</a>
                <a href="{{ route('events.index') }}" class="rounded-full bg-white/10 px-6 py-3 text-sm font-semibold text-white ring-1 ring-white/30 transition hover:bg-white/20">Upcoming events</a>
            </div>
        </div>
    </section>

    {{-- Intro / Mission / Vision --}}
    <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-16">
        @if ($home->intro_text)
            <p class="mx-auto max-w-3xl text-center text-lg leading-relaxed text-slate-600">{{ $home->intro_text }}</p>
        @endif
        <div class="mt-12 grid gap-6 md:grid-cols-2">
            <div class="rounded-2xl bg-white p-8 shadow-sm ring-1 ring-slate-200">
                <h2 class="text-lg font-semibold" style="color: var(--color-brand)">Our Mission</h2>
                <p class="mt-3 text-slate-600">{{ $home->mission }}</p>
            </div>
            <div class="rounded-2xl bg-white p-8 shadow-sm ring-1 ring-slate-200">
                <h2 class="text-lg font-semibold" style="color: var(--color-brand)">Our Vision</h2>
                <p class="mt-3 text-slate-600">{{ $home->vision }}</p>
            </div>
        </div>
    </section>

    {{-- Featured announcements --}}
    @if ($featuredPosts->isNotEmpty())
        <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
            @include('site.partials.section-heading', ['title' => 'Featured', 'link' => route('news.index')])
            <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($featuredPosts as $post)
                    @include('site.partials.post-card', ['post' => $post])
                @endforeach
            </div>
        </section>
    @endif

    {{-- Upcoming events --}}
    @if ($upcomingEvents->isNotEmpty())
        <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
            @include('site.partials.section-heading', ['title' => 'Upcoming Events', 'subtitle' => 'Join us at our next gathering', 'link' => route('events.index')])
            <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($upcomingEvents as $event)
                    @include('site.partials.event-card', ['event' => $event])
                @endforeach
            </div>
        </section>
    @endif

    {{-- Latest news --}}
    @if ($latestNews->isNotEmpty())
        <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
            @include('site.partials.section-heading', ['title' => 'Latest News', 'link' => route('news.index')])
            <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($latestNews as $post)
                    @include('site.partials.post-card', ['post' => $post])
                @endforeach
            </div>
        </section>
    @endif

    {{-- Photo highlights --}}
    @if ($photoHighlights->isNotEmpty())
        <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
            @include('site.partials.section-heading', ['title' => 'Photo Highlights', 'link' => route('gallery.index')])
            <div class="mt-6 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-6">
                @foreach ($photoHighlights as $album)
                    <a href="{{ route('gallery.show', $album) }}" class="group relative aspect-square overflow-hidden rounded-lg bg-slate-100">
                        <img src="{{ $album->cover_url }}" alt="{{ $album->title }}" class="h-full w-full object-cover transition group-hover:scale-110">
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    {{-- Testimonials --}}
    @if ($testimonials->isNotEmpty())
        <section class="bg-white py-16">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                @include('site.partials.section-heading', ['title' => 'What Our Members Say'])
                <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($testimonials as $testimonial)
                        <figure class="rounded-2xl p-6 ring-1 ring-slate-200" style="background-color: color-mix(in srgb, var(--color-brand) 5%, white)">
                            <blockquote class="text-slate-700">&ldquo;{{ $testimonial->quote }}&rdquo;</blockquote>
                            <figcaption class="mt-4 flex items-center gap-3">
                                <div class="h-10 w-10 overflow-hidden rounded-full bg-slate-200">
                                    @if ($testimonial->photo_url)
                                        <img src="{{ $testimonial->photo_url }}" alt="{{ $testimonial->name }}" class="h-full w-full object-cover">
                                    @endif
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-slate-900">{{ $testimonial->name }}</div>
                                    @if ($testimonial->title)<div class="text-xs text-slate-500">{{ $testimonial->title }}</div>@endif
                                </div>
                            </figcaption>
                        </figure>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Quick links + Contact CTA --}}
    <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-16">
        <div class="rounded-2xl px-8 py-12 text-center text-white" style="background-color: var(--color-brand)">
            <h2 class="text-2xl font-bold sm:text-3xl">Become part of our community</h2>
            <p class="mx-auto mt-3 max-w-2xl text-white/90">Explore our members directory, browse resources, or reach out to the North America team.</p>
            <div class="mt-6 flex flex-wrap justify-center gap-3">
                <a href="{{ route('members') }}" class="rounded-full bg-white px-6 py-3 text-sm font-semibold" style="color: var(--color-brand)">Members Directory</a>
                <a href="{{ route('resources') }}" class="rounded-full bg-white/10 px-6 py-3 text-sm font-semibold ring-1 ring-white/40 hover:bg-white/20">Resources</a>
                <a href="{{ route('contact') }}" class="rounded-full bg-white/10 px-6 py-3 text-sm font-semibold ring-1 ring-white/40 hover:bg-white/20">Contact Us</a>
            </div>
        </div>
    </section>
@endsection
