@extends('layouts.public')

@section('title', 'Leadership')
@section('meta_description', 'Meet the executive team leading the Unikosa North America Unit.')

@section('content')
    @include('site.partials.page-header', ['title' => 'Leadership', 'subtitle' => 'Meet the executives serving our community.'])

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-16">
        @if ($leaders->isEmpty())
            <p class="text-center text-slate-500">Leadership profiles will be published soon.</p>
        @else
            <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($leaders as $leader)
                    <div class="rounded-2xl bg-white p-6 text-center shadow-sm ring-1 ring-slate-200">
                        <div class="mx-auto h-28 w-28 overflow-hidden rounded-full bg-slate-100 ring-4" style="--tw-ring-color: color-mix(in srgb, var(--color-brand) 15%, white)">
                            @if ($leader->photo_url)
                                <img src="{{ $leader->photo_url }}" alt="{{ $leader->name }}" class="h-full w-full object-cover">
                            @else
                                <div class="flex h-full w-full items-center justify-center text-2xl font-bold text-slate-400">{{ Str::of($leader->name)->explode(' ')->map(fn($w) => Str::substr($w,0,1))->take(2)->implode('') }}</div>
                            @endif
                        </div>
                        <h3 class="mt-4 text-lg font-semibold text-slate-900">{{ $leader->name }}</h3>
                        <p class="text-sm font-medium" style="color: var(--color-brand)">{{ $leader->position }}</p>
                        @if ($leader->biography)
                            <p class="mt-3 text-sm text-slate-500">{{ Str::limit($leader->biography, 160) }}</p>
                        @endif
                        @if ($leader->email)
                            <a href="mailto:{{ $leader->email }}" class="mt-3 inline-block text-sm hover:underline" style="color: var(--color-brand)">{{ $leader->email }}</a>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
