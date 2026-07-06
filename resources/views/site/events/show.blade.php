@extends('layouts.public')

@section('title', $event->title)
@section('meta_description', Str::limit(strip_tags($event->description), 155))

@section('content')
    @include('site.partials.page-header', ['title' => $event->title, 'subtitle' => $event->date->format('l, F j, Y') . ($event->venue ? ' · ' . $event->venue : '')])

    <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid gap-10 lg:grid-cols-3">
            <div class="lg:col-span-2 space-y-8">
                @if ($event->is_live && $event->live_stream_url)
                    <div class="space-y-3">
                        <div class="inline-flex items-center gap-2 rounded-full bg-red-600 px-3 py-1 text-sm font-semibold text-white">
                            <span class="h-2 w-2 animate-pulse rounded-full bg-white"></span> LIVE NOW
                        </div>
                        @include('site.partials.video-embed', ['url' => $event->live_stream_url])
                    </div>
                @elseif ($event->flyer_url)
                    <img src="{{ $event->flyer_url }}" alt="{{ $event->title }}" class="w-full rounded-2xl ring-1 ring-slate-200">
                @endif

                @if ($event->description)
                    <div class="prose max-w-none text-slate-700">{!! $event->description !!}</div>
                @endif

                {{-- Gallery --}}
                @php $gallery = $event->getMedia('gallery'); @endphp
                @if ($gallery->isNotEmpty())
                    <section>
                        <h2 class="text-xl font-bold text-slate-900">Gallery</h2>
                        <div class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-3">
                            @foreach ($gallery as $photo)
                                <a href="{{ $photo->getUrl() }}" target="_blank" rel="noopener" class="aspect-square overflow-hidden rounded-lg bg-slate-100">
                                    <img src="{{ $photo->hasGeneratedConversion('thumb') ? $photo->getUrl('thumb') : $photo->getUrl() }}" alt="" class="h-full w-full object-cover transition hover:scale-105">
                                </a>
                            @endforeach
                        </div>
                    </section>
                @endif

                {{-- Videos --}}
                @if (! empty($event->videos))
                    <section>
                        <h2 class="text-xl font-bold text-slate-900">Videos</h2>
                        <ul class="mt-4 space-y-2">
                            @foreach ($event->videos as $video)
                                <li>
                                    <a href="{{ $video['url'] ?? '#' }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 text-sm font-medium hover:underline" style="color: var(--color-brand)">
                                        &#9658; {{ $video['title'] ?? $video['url'] ?? 'Watch video' }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </section>
                @endif
            </div>

            <aside class="space-y-6">
                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <dl class="space-y-3 text-sm">
                        <div>
                            <dt class="font-medium text-slate-400">Status</dt>
                            <dd><span class="rounded-full px-2 py-0.5 text-xs font-medium text-white" style="background-color: var(--color-brand)">{{ $event->status->getLabel() }}</span></dd>
                        </div>
                        <div><dt class="font-medium text-slate-400">Date</dt><dd class="text-slate-700">{{ $event->date->format('F j, Y') }}@if($event->time) at {{ \Illuminate\Support\Carbon::parse($event->time)->format('g:i A') }}@endif</dd></div>
                        @if ($event->venue)<div><dt class="font-medium text-slate-400">Venue</dt><dd class="text-slate-700">{{ $event->venue }}</dd></div>@endif
                    </dl>
                    @if ($event->registration_link)
                        <a href="{{ $event->registration_link }}" target="_blank" rel="noopener" class="mt-5 block rounded-full px-5 py-2.5 text-center text-sm font-semibold text-white" style="background-color: var(--color-brand-accent)">Register / RSVP</a>
                    @endif
                    <a href="{{ route('events.ics', $event) }}" class="mt-3 block rounded-full px-5 py-2.5 text-center text-sm font-semibold ring-1 ring-slate-300 text-slate-700 hover:bg-slate-50">Add to calendar</a>
                </div>

                @if ($event->registration_enabled)
                    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                        <h3 class="font-semibold text-slate-900">Register for this event</h3>

                        @if (session('reg_success'))
                            <p class="mt-3 rounded-lg bg-green-50 p-3 text-sm text-green-800 ring-1 ring-green-200">{{ session('reg_success') }}</p>
                        @elseif ($event->isFull())
                            <p class="mt-3 rounded-lg bg-amber-50 p-3 text-sm text-amber-800 ring-1 ring-amber-200">This event has reached capacity.</p>
                        @else
                            @if (session('reg_error'))
                                <p class="mt-3 rounded-lg bg-red-50 p-3 text-sm text-red-800 ring-1 ring-red-200">{{ session('reg_error') }}</p>
                            @endif
                            @if ($errors->any())
                                <ul class="mt-3 list-inside list-disc rounded-lg bg-red-50 p-3 text-sm text-red-800 ring-1 ring-red-200">
                                    @foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                                </ul>
                            @endif
                            <form method="POST" action="{{ route('events.register', $event) }}" class="mt-4 space-y-3">
                                @csrf
                                <input type="text" name="name" value="{{ old('name') }}" required placeholder="Full name" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                <input type="email" name="email" value="{{ old('email') }}" required placeholder="Email" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="Phone (optional)" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                <input type="number" name="guests" value="{{ old('guests', 0) }}" min="0" max="20" placeholder="Additional guests" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                <button type="submit" class="w-full rounded-full px-5 py-2.5 text-sm font-semibold text-white" style="background-color: var(--color-brand-accent)">Register</button>
                            </form>
                        @endif
                    </div>
                @endif

                @if (! empty($event->speakers))
                    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                        <h3 class="font-semibold text-slate-900">Speakers</h3>
                        <ul class="mt-3 space-y-2 text-sm">
                            @foreach ($event->speakers as $speaker)
                                <li class="text-slate-700">
                                    <span class="font-medium">{{ $speaker['name'] ?? '' }}</span>
                                    @if (! empty($speaker['title']))<span class="text-slate-400"> — {{ $speaker['title'] }}</span>@endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @php $documents = $event->getMedia('documents'); @endphp
                @if ($documents->isNotEmpty())
                    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                        <h3 class="font-semibold text-slate-900">Documents</h3>
                        <ul class="mt-3 space-y-2 text-sm">
                            @foreach ($documents as $doc)
                                <li><a href="{{ $doc->getUrl() }}" target="_blank" rel="noopener" class="hover:underline" style="color: var(--color-brand)">{{ $doc->file_name }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </aside>
        </div>

        <div class="mt-10">
            <a href="{{ route('events.index') }}" class="text-sm font-semibold text-slate-500 hover:text-slate-800">&larr; Back to events</a>
        </div>
    </div>
@endsection
