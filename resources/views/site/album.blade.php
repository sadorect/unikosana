@extends('layouts.public')

@section('title', $album->title)

@section('content')
    @include('site.partials.page-header', ['title' => $album->title, 'subtitle' => collect([$album->year, optional($album->event)->title])->filter()->implode(' · ')])

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12">
        @if ($album->description)
            <p class="mb-8 max-w-2xl text-slate-600">{{ $album->description }}</p>
        @endif

        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4">
            @foreach ($album->getMedia('photos') as $photo)
                <a href="{{ $photo->getUrl() }}" target="_blank" rel="noopener" class="aspect-square overflow-hidden rounded-lg bg-slate-100">
                    <img src="{{ $photo->hasGeneratedConversion('thumb') ? $photo->getUrl('thumb') : $photo->getUrl() }}" alt="" class="h-full w-full object-cover transition hover:scale-105">
                </a>
            @endforeach
        </div>

        <div class="mt-10">
            <a href="{{ route('gallery.index') }}" class="text-sm font-semibold text-slate-500 hover:text-slate-800">&larr; Back to gallery</a>
        </div>
    </div>
@endsection
