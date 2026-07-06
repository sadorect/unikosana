@extends('layouts.public')

@section('title', 'Events')
@section('meta_description', 'Upcoming and recent events of the Unikosa North America Unit.')

@section('content')
    @include('site.partials.page-header', ['title' => 'Events', 'subtitle' => 'Our gatherings, programs and activities.'])

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12 space-y-16">
        <section>
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-bold text-slate-900">Upcoming & Ongoing</h2>
                <a href="{{ route('events.archive') }}" class="text-sm font-semibold hover:underline" style="color: var(--color-brand)">Event archive &rarr;</a>
            </div>
            @if ($upcoming->isEmpty())
                <p class="mt-6 rounded-xl bg-white p-8 text-center text-slate-500 ring-1 ring-slate-200">No upcoming events scheduled. Check back soon.</p>
            @else
                <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($upcoming as $event)
                        @include('site.partials.event-card', ['event' => $event])
                    @endforeach
                </div>
            @endif
        </section>

        @if ($recent->isNotEmpty())
            <section>
                <h2 class="text-2xl font-bold text-slate-900">Recently Completed</h2>
                <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($recent as $event)
                        @include('site.partials.event-card', ['event' => $event])
                    @endforeach
                </div>
            </section>
        @endif
    </div>
@endsection
