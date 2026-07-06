@extends('layouts.public')

@section('title', 'Event Archive')
@section('meta_description', 'Archive of completed Unikosa North America events, organized by year.')

@section('content')
    @include('site.partials.page-header', ['title' => 'Event Archive', 'subtitle' => 'A record of our past events, by year.'])

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12 space-y-12">
        @forelse ($eventsByYear as $year => $events)
            <section>
                <h2 class="mb-6 flex items-center gap-3 text-2xl font-bold text-slate-900">
                    <span>{{ $year }}</span>
                    <span class="h-px flex-1 bg-slate-200"></span>
                    <span class="text-sm font-normal text-slate-400">{{ $events->count() }} event(s)</span>
                </h2>
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($events as $event)
                        @include('site.partials.event-card', ['event' => $event])
                    @endforeach
                </div>
            </section>
        @empty
            <p class="text-center text-slate-500">No completed events yet.</p>
        @endforelse
    </div>
@endsection
