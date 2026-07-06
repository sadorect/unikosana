@extends('layouts.public')

@section('title', 'Search')

@section('content')
    @include('site.partials.page-header', ['title' => 'Search', 'subtitle' => $term ? "Results for “{$term}”" : 'Search members, events, news and resources.'])

    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 py-12">
        <form method="GET" action="{{ route('search') }}" class="flex gap-2">
            <input type="search" name="q" value="{{ $term }}" placeholder="Search…" autofocus
                   class="flex-1 rounded-full border border-slate-300 px-5 py-3 text-sm focus:border-[var(--color-brand)] focus:outline-none">
            <button type="submit" class="rounded-full px-6 py-3 text-sm font-semibold text-white" style="background-color: var(--color-brand)">Search</button>
        </form>

        @if ($term)
            <p class="mt-6 text-sm text-slate-500">{{ $total }} result(s) found.</p>

            @if ($events->isNotEmpty())
                <section class="mt-8">
                    <h2 class="text-lg font-bold text-slate-900">Events</h2>
                    <ul class="mt-3 space-y-2">
                        @foreach ($events as $event)
                            <li><a href="{{ route('events.show', $event) }}" class="block rounded-lg bg-white p-4 shadow-sm ring-1 ring-slate-200 hover:ring-[var(--color-brand)]">
                                <span class="font-medium text-slate-900">{{ $event->title }}</span>
                                <span class="ml-2 text-sm text-slate-400">{{ $event->date->format('M j, Y') }}</span>
                            </a></li>
                        @endforeach
                    </ul>
                </section>
            @endif

            @if ($posts->isNotEmpty())
                <section class="mt-8">
                    <h2 class="text-lg font-bold text-slate-900">News & Announcements</h2>
                    <ul class="mt-3 space-y-2">
                        @foreach ($posts as $post)
                            <li><a href="{{ route('news.show', $post) }}" class="block rounded-lg bg-white p-4 shadow-sm ring-1 ring-slate-200 hover:ring-[var(--color-brand)]">
                                <span class="font-medium text-slate-900">{{ $post->title }}</span>
                            </a></li>
                        @endforeach
                    </ul>
                </section>
            @endif

            @if ($members->isNotEmpty())
                <section class="mt-8">
                    <h2 class="text-lg font-bold text-slate-900">Members</h2>
                    <ul class="mt-3 space-y-2">
                        @foreach ($members as $member)
                            <li class="rounded-lg bg-white p-4 shadow-sm ring-1 ring-slate-200">
                                <span class="font-medium text-slate-900">{{ $member->full_name }}</span>
                                @if ($member->occupation)<span class="ml-2 text-sm text-slate-400">{{ $member->occupation }}</span>@endif
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endif

            @if ($resources->isNotEmpty())
                <section class="mt-8">
                    <h2 class="text-lg font-bold text-slate-900">Resources</h2>
                    <ul class="mt-3 space-y-2">
                        @foreach ($resources as $resource)
                            <li class="rounded-lg bg-white p-4 shadow-sm ring-1 ring-slate-200">
                                @if ($resource->file_url)
                                    <a href="{{ $resource->file_url }}" target="_blank" rel="noopener" class="font-medium hover:underline" style="color: var(--color-brand)">{{ $resource->title }}</a>
                                @else
                                    <span class="font-medium text-slate-900">{{ $resource->title }}</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endif

            @if ($total === 0)
                <p class="mt-10 text-center text-slate-500">No results found. Try a different search term.</p>
            @endif
        @endif
    </div>
@endsection
