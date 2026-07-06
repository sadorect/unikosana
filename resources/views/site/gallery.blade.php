@extends('layouts.public')

@section('title', 'Gallery')
@section('meta_description', 'Photo and video galleries from Unikosa North America events.')

@section('content')
    @include('site.partials.page-header', ['title' => 'Gallery', 'subtitle' => 'Photos and videos from our events, by album and year.'])

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12 space-y-16">
        <section>
            <h2 class="text-2xl font-bold text-slate-900">Photo Albums</h2>
            @if ($photoAlbums->isEmpty())
                <p class="mt-6 text-slate-500">No photo albums yet.</p>
            @else
                <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($photoAlbums as $album)
                        <a href="{{ route('gallery.show', $album) }}" class="group overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
                            <div class="aspect-square overflow-hidden bg-slate-100">
                                @if ($album->cover_url)
                                    <img src="{{ $album->cover_url }}" alt="{{ $album->title }}" class="h-full w-full object-cover transition group-hover:scale-105">
                                @endif
                            </div>
                            <div class="p-4">
                                <h3 class="font-semibold text-slate-900">{{ $album->title }}</h3>
                                <p class="text-sm text-slate-400">{{ $album->year }} · {{ $album->getMedia('photos')->count() }} photos</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </section>

        <section>
            <h2 class="text-2xl font-bold text-slate-900">Video Albums</h2>
            @if ($videoAlbums->isEmpty())
                <p class="mt-6 text-slate-500">No video albums yet.</p>
            @else
                <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($videoAlbums as $album)
                        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                            <h3 class="font-semibold text-slate-900">{{ $album->title }}</h3>
                            <p class="text-sm text-slate-400">{{ $album->year }}</p>
                            <ul class="mt-3 space-y-2 text-sm">
                                @foreach (($album->videos ?? []) as $video)
                                    <li><a href="{{ $video['url'] ?? '#' }}" target="_blank" rel="noopener" class="hover:underline" style="color: var(--color-brand)">&#9658; {{ $video['title'] ?? 'Watch video' }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>
    </div>
@endsection
