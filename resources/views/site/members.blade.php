@extends('layouts.public')

@section('title', 'Members Directory')
@section('meta_description', 'Search and browse the Unikosa North America members directory.')

@section('content')
    @include('site.partials.page-header', ['title' => 'Members Directory', 'subtitle' => 'Connect with members across North America.'])

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12">
        {{-- Filters --}}
        <form method="GET" class="grid gap-3 rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200 sm:grid-cols-2 lg:grid-cols-5">
            <input type="search" name="q" value="{{ request('q') }}" placeholder="Name, occupation, school…"
                   class="rounded-lg border border-slate-300 px-4 py-2 text-sm focus:border-[var(--color-brand)] focus:outline-none lg:col-span-2">
            <select name="country" class="rounded-lg border border-slate-300 px-4 py-2 text-sm">
                <option value="">All countries</option>
                @foreach ($countries as $c)
                    <option value="{{ $c }}" @selected(request('country') === $c)>{{ $c }}</option>
                @endforeach
            </select>
            <select name="state" class="rounded-lg border border-slate-300 px-4 py-2 text-sm">
                <option value="">All states/provinces</option>
                @foreach ($states as $s)
                    <option value="{{ $s }}" @selected(request('state') === $s)>{{ $s }}</option>
                @endforeach
            </select>
            <div class="flex gap-2">
                <select name="year" class="w-full rounded-lg border border-slate-300 px-4 py-2 text-sm">
                    <option value="">Grad year</option>
                    @foreach ($years as $y)
                        <option value="{{ $y }}" @selected((string) request('year') === (string) $y)>{{ $y }}</option>
                    @endforeach
                </select>
                <button type="submit" class="rounded-lg px-4 py-2 text-sm font-semibold text-white" style="background-color: var(--color-brand)">Filter</button>
            </div>
        </form>

        <p class="mt-6 text-sm text-slate-500">{{ $members->total() }} member(s) found.</p>

        <div class="mt-4 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($members as $member)
                <div class="flex gap-4 rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
                    <div class="h-16 w-16 shrink-0 overflow-hidden rounded-full bg-slate-100">
                        @if ($member->photo_url)
                            <img src="{{ $member->photo_url }}" alt="{{ $member->full_name }}" class="h-full w-full object-cover">
                        @else
                            <div class="flex h-full w-full items-center justify-center font-bold text-slate-400">{{ Str::substr($member->full_name, 0, 1) }}</div>
                        @endif
                    </div>
                    <div class="min-w-0">
                        <h3 class="truncate font-semibold text-slate-900">{{ $member->full_name }}</h3>
                        @if ($member->occupation)<p class="truncate text-sm text-slate-500">{{ $member->occupation }}</p>@endif
                        <p class="mt-1 text-xs text-slate-400">
                            {{ collect([$member->state_province, $member->country])->filter()->implode(', ') }}
                            @if ($member->graduation_year) &middot; Class of {{ $member->graduation_year }} @endif
                        </p>
                    </div>
                </div>
            @empty
                <p class="col-span-full text-center text-slate-500">No members match your search.</p>
            @endforelse
        </div>

        <div class="mt-8">{{ $members->links() }}</div>
    </div>
@endsection
