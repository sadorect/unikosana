@extends('layouts.public')

@section('title', 'Contact Us')
@section('meta_description', 'Get in touch with the Unikosa North America Unit.')

@section('content')
    @include('site.partials.page-header', ['title' => 'Contact Us', 'subtitle' => 'We would love to hear from you.'])

    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid gap-10 lg:grid-cols-5">
            {{-- Info --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <h2 class="font-semibold text-slate-900">Reach us directly</h2>
                    <dl class="mt-4 space-y-3 text-sm">
                        @if ($site->contact_email)<div><dt class="text-slate-400">Email</dt><dd><a href="mailto:{{ $site->contact_email }}" class="hover:underline" style="color: var(--color-brand)">{{ $site->contact_email }}</a></dd></div>@endif
                        @if ($site->contact_phone)<div><dt class="text-slate-400">Phone</dt><dd class="text-slate-700">{{ $site->contact_phone }}</dd></div>@endif
                        @if ($site->address)<div><dt class="text-slate-400">Address</dt><dd class="text-slate-700">{{ $site->address }}</dd></div>@endif
                    </dl>
                </div>
                @if ($site->map_embed)
                    <div class="overflow-hidden rounded-2xl shadow-sm ring-1 ring-slate-200 [&_iframe]:w-full [&_iframe]:h-64 [&_iframe]:block">
                        {!! $site->map_embed !!}
                    </div>
                @endif
            </div>

            {{-- Form --}}
            <div class="lg:col-span-3">
                @if (session('success'))
                    <div class="mb-6 rounded-xl bg-green-50 p-4 text-sm text-green-800 ring-1 ring-green-200">{{ session('success') }}</div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 rounded-xl bg-red-50 p-4 text-sm text-red-800 ring-1 ring-red-200">
                        <ul class="list-inside list-disc space-y-1">
                            @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('contact.submit') }}" class="space-y-5 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    @csrf
                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Name</label>
                            <input type="text" name="name" value="{{ old('name') }}" required class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2 text-sm focus:border-[var(--color-brand)] focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" required class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2 text-sm focus:border-[var(--color-brand)] focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Phone <span class="text-slate-400">(optional)</span></label>
                            <input type="tel" name="phone" value="{{ old('phone') }}" class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2 text-sm focus:border-[var(--color-brand)] focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Subject</label>
                            <input type="text" name="subject" value="{{ old('subject') }}" required class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2 text-sm focus:border-[var(--color-brand)] focus:outline-none">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Message</label>
                        <textarea name="message" rows="5" required class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2 text-sm focus:border-[var(--color-brand)] focus:outline-none">{{ old('message') }}</textarea>
                    </div>

                    @if ($captchaEnabled)
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Enter the code shown</label>
                            <div class="mt-1 flex items-center gap-3">
                                <img src="{{ $captchaSrc }}" alt="Captcha" data-captcha class="h-14 rounded-lg ring-1 ring-slate-200">
                                <button type="button" data-captcha-reload class="rounded-lg bg-slate-100 px-3 py-2 text-xs font-medium text-slate-600 hover:bg-slate-200">&#8635; Refresh</button>
                            </div>
                            <input type="text" name="captcha" required autocomplete="off" class="mt-2 w-48 rounded-lg border border-slate-300 px-4 py-2 text-sm focus:border-[var(--color-brand)] focus:outline-none">
                        </div>
                    @endif

                    <button type="submit" class="rounded-full px-6 py-3 text-sm font-semibold text-white transition hover:opacity-90" style="background-color: var(--color-brand)">Send Message</button>
                </form>
            </div>
        </div>
    </div>
@endsection
