@extends('layouts.public')

@section('title', 'Donation Cancelled')

@section('content')
    <div class="mx-auto max-w-2xl px-4 sm:px-6 lg:px-8 py-24 text-center">
        <h1 class="text-2xl font-bold text-slate-900">Your donation was cancelled</h1>
        <p class="mt-4 text-slate-600">No charge was made. You are welcome to try again whenever you are ready.</p>
        <a href="{{ route('donate') }}" class="mt-8 inline-block rounded-full px-6 py-3 text-sm font-semibold text-white" style="background-color: var(--color-brand)">Back to donate</a>
    </div>
@endsection
