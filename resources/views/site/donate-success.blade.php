@extends('layouts.public')

@section('title', 'Thank You')

@section('content')
    <div class="mx-auto max-w-2xl px-4 sm:px-6 lg:px-8 py-24 text-center">
        <div class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-full" style="background-color: color-mix(in srgb, var(--color-brand) 12%, white)">
            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--color-brand)"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        </div>
        <h1 class="text-2xl font-bold text-slate-900">Thank you for your generosity!</h1>
        <p class="mt-4 text-slate-600">Your donation has been received and directly supports our community. A receipt has been sent to your email.</p>
        <a href="{{ route('home') }}" class="mt-8 inline-block rounded-full px-6 py-3 text-sm font-semibold text-white" style="background-color: var(--color-brand)">Back to home</a>
    </div>
@endsection
