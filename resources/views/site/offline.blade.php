@extends('layouts.public')

@section('title', 'Offline')
@section('meta_description', 'You are currently offline.')
@section('robots', 'noindex, nofollow')

@section('content')
    <div class="mx-auto max-w-2xl px-4 sm:px-6 lg:px-8 py-24 text-center">
        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl text-white" style="background-color: var(--color-brand)">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18M8.5 16.5a5 5 0 016.9-.02M5 12.5a10 10 0 0114-.02M2 9a15 15 0 0120 0M11 20h2" />
            </svg>
        </div>
        <h1 class="mt-6 text-2xl font-bold text-slate-900">You're offline</h1>
        <p class="mt-3 text-slate-600">We couldn't reach {{ $theme->site_name }} right now. Check your connection and try again — pages you've already visited are still available.</p>
        <button type="button" onclick="location.reload()" class="mt-8 rounded-full px-6 py-3 text-sm font-semibold text-white transition hover:opacity-90" style="background-color: var(--color-brand)">Retry</button>
    </div>
@endsection
