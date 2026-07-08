@extends('layouts.public')

@section('title', 'Member Login')

@section('content')
    <div class="mx-auto max-w-md px-4 sm:px-6 lg:px-8 py-16">
        <div class="rounded-2xl bg-white p-8 shadow-sm ring-1 ring-slate-200">
            <h1 class="text-2xl font-bold text-slate-900">Member Login</h1>
            <p class="mt-1 text-sm text-slate-500">Access your membership profile.</p>

            @if (session('success'))
                <p class="mt-4 rounded-lg bg-green-50 p-3 text-sm text-green-800 ring-1 ring-green-200">{{ session('success') }}</p>
            @endif
            @if ($errors->any())
                <ul class="mt-4 list-inside list-disc rounded-lg bg-red-50 p-3 text-sm text-red-800 ring-1 ring-red-200">
                    @foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                </ul>
            @endif

            @if (filled(config('services.national.client_id')))
                <a href="{{ route('national.redirect') }}" class="mt-6 flex w-full items-center justify-center gap-2 rounded-full px-5 py-3 text-sm font-semibold text-white" style="background-color: var(--color-brand-dark)">
                    Continue with your Unikosa account
                </a>

                <p class="mt-6 text-center text-sm text-slate-500">
                    Not a member yet?
                    <a href="{{ rtrim(config('services.national.base_url'), '/') . '/register' }}" class="font-semibold hover:underline" style="color: var(--color-brand)">Create your Unikosa account</a>
                </p>
                <p class="mt-4 text-center text-xs text-slate-400">
                    Chapter administrators sign in via the
                    <a href="{{ url('/admin/login') }}" class="font-medium hover:underline">admin panel</a>.
                </p>
            @else
                {{-- Fallback: local password login when national SSO is not configured. --}}
                <form method="POST" action="{{ route('member.login.store') }}" class="mt-6 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Password</label>
                        <input type="password" name="password" required class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2 text-sm">
                    </div>
                    <label class="flex items-center gap-2 text-sm text-slate-600">
                        <input type="checkbox" name="remember" class="rounded border-slate-300"> Remember me
                    </label>
                    <button type="submit" class="w-full rounded-full px-5 py-3 text-sm font-semibold text-white" style="background-color: var(--color-brand)">Sign in</button>
                </form>

                <p class="mt-6 text-center text-sm text-slate-500">
                    Not a member yet?
                    <a href="{{ route('member.register') }}" class="font-semibold hover:underline" style="color: var(--color-brand)">Join now</a>
                </p>
            @endif
        </div>
    </div>
@endsection
