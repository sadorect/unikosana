@extends('layouts.public')

@section('title', 'Donate')
@section('meta_description', 'Support the Unikosa North America Unit with a donation.')

@section('content')
    @include('site.partials.page-header', ['title' => 'Support Our Community', 'subtitle' => 'Your donation powers our events, programs and member services.'])

    <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8 py-12 space-y-10">
        @if ($settings->intro)
            <p class="text-lg leading-relaxed text-slate-600">{{ $settings->intro }}</p>
        @endif

        @if ($paymentReady)
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <h2 class="text-xl font-bold text-slate-900">Give online</h2>
                @if ($errors->any())
                    <ul class="mt-3 list-inside list-disc rounded-lg bg-red-50 p-3 text-sm text-red-800 ring-1 ring-red-200">
                        @foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                    </ul>
                @endif
                <form method="POST" action="{{ route('donate.checkout') }}" class="mt-4 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Amount ({{ strtoupper($settings->currency) }})</label>
                        <div class="mt-2 flex flex-wrap gap-2">
                            @foreach ($amounts as $amount)
                                <button type="button" data-amount="{{ $amount }}" class="rounded-full border border-slate-300 px-4 py-2 text-sm text-slate-700 hover:border-slate-400">{{ strtoupper($settings->currency) }} {{ $amount }}</button>
                            @endforeach
                        </div>
                        <input type="number" name="amount" data-amount-input min="1" step="1" required value="{{ old('amount', $amounts->first()) }}" class="mt-3 w-full rounded-lg border border-slate-300 px-4 py-2 text-sm">
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Name (optional)" class="rounded-lg border border-slate-300 px-4 py-2 text-sm">
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="Email (optional)" class="rounded-lg border border-slate-300 px-4 py-2 text-sm">
                    </div>
                    <button type="submit" class="rounded-full px-6 py-3 text-sm font-semibold text-white" style="background-color: var(--color-brand-accent)">Donate securely</button>
                    <p class="text-xs text-slate-400">Payments are processed securely by Stripe.</p>
                </form>
            </div>
        @endif

        @if ($settings->bank_details)
            <div class="rounded-2xl p-6 ring-1 ring-slate-200" style="background-color: color-mix(in srgb, var(--color-brand) 5%, white)">
                <h2 class="text-xl font-bold text-slate-900">Give by bank transfer</h2>
                <pre class="mt-3 whitespace-pre-wrap font-sans text-sm text-slate-600">{{ $settings->bank_details }}</pre>
            </div>
        @endif
    </div>
@endsection
