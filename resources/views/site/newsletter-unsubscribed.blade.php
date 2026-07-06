@extends('layouts.public')

@section('title', 'Unsubscribed')

@section('content')
    <div class="mx-auto max-w-2xl px-4 sm:px-6 lg:px-8 py-24 text-center">
        <h1 class="text-2xl font-bold text-slate-900">You have been unsubscribed</h1>
        <p class="mt-4 text-slate-600">
            @if ($email)
                <strong>{{ $email }}</strong> will no longer receive our newsletter.
            @else
                This unsubscribe link is invalid or has already been used.
            @endif
        </p>
        <a href="{{ route('home') }}" class="mt-8 inline-block rounded-full px-6 py-3 text-sm font-semibold text-white" style="background-color: var(--color-brand)">Back to home</a>
    </div>
@endsection
