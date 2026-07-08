@extends('layouts.public')

@section('title', $post->title)
@section('meta_description', $post->meta_description ?: Str::limit(strip_tags($post->excerpt ?: $post->body), 155))

@section('og')
    <meta property="og:title" content="{{ $post->title }}">
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ route('news.show', $post) }}">
    @if ($post->featured_image_url)<meta property="og:image" content="{{ $post->featured_image_url }}">@endif
@endsection

@section('content')
    <article class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8 py-12">
        @include('site.partials.breadcrumbs', ['items' => [
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'News', 'url' => route('news.index')],
            ['label' => Str::limit($post->title, 60)],
        ]])

        <div class="mt-6 flex items-center gap-3 text-sm text-slate-500">
            <span class="rounded-full px-2 py-0.5 text-xs font-medium" style="background-color: color-mix(in srgb, var(--color-brand-accent) 20%, white); color: var(--color-brand-dark)">{{ $post->type->getLabel() }}</span>
            @if ($post->published_at)<time>{{ $post->published_at->format('F j, Y') }}</time>@endif
            @if ($post->author)<span>· by {{ $post->author->name }}</span>@endif
        </div>

        <h1 class="mt-4 text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">{{ $post->title }}</h1>

        @if ($post->tags->isNotEmpty())
            <div class="mt-3 flex flex-wrap gap-2">
                @foreach ($post->tags as $tag)
                    <span class="rounded-full bg-slate-100 px-2.5 py-0.5 text-xs text-slate-600">#{{ $tag->name }}</span>
                @endforeach
            </div>
        @endif

        @if ($post->featured_image_url)
            <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" class="mt-8 w-full rounded-2xl ring-1 ring-slate-200">
        @endif

        <div class="prose prose-slate mt-8 max-w-none">{!! $post->body !!}</div>

        {{-- Social sharing --}}
        @php
            $url = urlencode(route('news.show', $post));
            $text = urlencode($post->title);
        @endphp
        <div class="mt-10 flex flex-wrap items-center gap-3 border-t border-slate-200 pt-6">
            <span class="text-sm font-medium text-slate-500">Share:</span>
            <a href="https://www.facebook.com/sharer/sharer.php?u={{ $url }}" target="_blank" rel="noopener" class="rounded-full bg-slate-100 px-3 py-1 text-sm hover:bg-slate-200">Facebook</a>
            <a href="https://twitter.com/intent/tweet?url={{ $url }}&text={{ $text }}" target="_blank" rel="noopener" class="rounded-full bg-slate-100 px-3 py-1 text-sm hover:bg-slate-200">X</a>
            <a href="https://api.whatsapp.com/send?text={{ $text }}%20{{ $url }}" target="_blank" rel="noopener" class="rounded-full bg-slate-100 px-3 py-1 text-sm hover:bg-slate-200">WhatsApp</a>
            <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ $url }}" target="_blank" rel="noopener" class="rounded-full bg-slate-100 px-3 py-1 text-sm hover:bg-slate-200">LinkedIn</a>
        </div>

        {{-- Previous / Next article navigation --}}
        @if ($previous || $next)
            <nav aria-label="Post navigation" class="mt-10 grid gap-4 border-t border-slate-200 pt-6 sm:grid-cols-2">
                @if ($previous)
                    <a href="{{ route('news.show', $previous) }}" class="group rounded-xl ring-1 ring-slate-200 p-4 hover:ring-slate-300 hover:bg-slate-50 sm:text-left">
                        <span class="text-xs font-medium uppercase tracking-wide text-slate-400">&larr; Previous</span>
                        <span class="mt-1 block font-semibold text-slate-900 group-hover:underline">{{ $previous->title }}</span>
                    </a>
                @else
                    <span class="hidden sm:block"></span>
                @endif

                @if ($next)
                    <a href="{{ route('news.show', $next) }}" class="group rounded-xl ring-1 ring-slate-200 p-4 hover:ring-slate-300 hover:bg-slate-50 sm:text-right">
                        <span class="text-xs font-medium uppercase tracking-wide text-slate-400">Next &rarr;</span>
                        <span class="mt-1 block font-semibold text-slate-900 group-hover:underline">{{ $next->title }}</span>
                    </a>
                @endif
            </nav>
        @endif

        <div class="mt-8">
            <a href="{{ route('news.index') }}" class="text-sm font-semibold hover:underline" style="color: var(--color-brand)">&larr; Back to all news</a>
        </div>
    </article>

    @if ($related->isNotEmpty())
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 pb-16">
            <h2 class="text-xl font-bold text-slate-900">Related articles</h2>
            <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($related as $relatedPost)
                    @include('site.partials.post-card', ['post' => $relatedPost])
                @endforeach
            </div>
        </div>
    @endif
@endsection
