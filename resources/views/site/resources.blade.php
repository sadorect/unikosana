@extends('layouts.public')

@section('title', 'Resources')
@section('meta_description', 'Download the constitution, reports, meeting minutes, newsletters and membership forms.')

@section('content')
    @include('site.partials.page-header', ['title' => 'Resources', 'subtitle' => 'Downloadable documents and forms.'])

    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 py-12 space-y-10">
        @forelse ($resources as $category => $items)
            <section>
                <h2 class="text-xl font-bold text-slate-900">{{ $category }}</h2>
                <ul class="mt-4 divide-y divide-slate-100 overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
                    @foreach ($items as $resource)
                        <li class="flex items-center justify-between gap-4 p-4">
                            <div class="min-w-0">
                                <p class="font-medium text-slate-900">{{ $resource->title }}</p>
                                @if ($resource->description)<p class="truncate text-sm text-slate-500">{{ $resource->description }}</p>@endif
                            </div>
                            @if ($resource->file_url)
                                <a href="{{ $resource->file_url }}" target="_blank" rel="noopener"
                                   class="shrink-0 rounded-full px-4 py-2 text-sm font-semibold text-white" style="background-color: var(--color-brand)">
                                    Download @if ($resource->file_size)<span class="opacity-80">({{ $resource->file_size }})</span>@endif
                                </a>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </section>
        @empty
            <p class="text-center text-slate-500">No resources available yet.</p>
        @endforelse
    </div>
@endsection
