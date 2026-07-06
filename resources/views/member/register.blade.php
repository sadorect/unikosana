@extends('layouts.public')

@section('title', 'Join Unikosa North America')

@section('content')
    @include('site.partials.page-header', ['title' => 'Membership Registration', 'subtitle' => 'Join the Unikosa North America community. Your registration will be reviewed by our team.'])

    <div class="mx-auto max-w-2xl px-4 sm:px-6 lg:px-8 py-12">
        @if ($errors->any())
            <ul class="mb-6 list-inside list-disc rounded-lg bg-red-50 p-4 text-sm text-red-800 ring-1 ring-red-200">
                @foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        @endif

        <form method="POST" action="{{ route('member.register.store') }}" class="space-y-5 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            @csrf
            <div class="grid gap-5 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-slate-700">Full name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Phone <span class="text-slate-400">(optional)</span></label>
                    <input type="tel" name="contact_phone" value="{{ old('contact_phone') }}" class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Password</label>
                    <input type="password" name="password" required class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Confirm password</label>
                    <input type="password" name="password_confirmation" required class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Country</label>
                    <input type="text" name="country" value="{{ old('country', 'United States') }}" required class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">State / Province</label>
                    <input type="text" name="state_province" value="{{ old('state_province') }}" class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Occupation</label>
                    <input type="text" name="occupation" value="{{ old('occupation') }}" class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">School</label>
                    <input type="text" name="school" value="{{ old('school') }}" class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Graduation year</label>
                    <input type="number" name="graduation_year" value="{{ old('graduation_year') }}" min="1900" max="2100" class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2 text-sm">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-slate-700">Short bio <span class="text-slate-400">(optional)</span></label>
                    <textarea name="biography" rows="3" class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2 text-sm">{{ old('biography') }}</textarea>
                </div>
            </div>
            <button type="submit" class="rounded-full px-6 py-3 text-sm font-semibold text-white" style="background-color: var(--color-brand)">Create my account</button>
        </form>

        <p class="mt-6 text-center text-sm text-slate-500">
            Already have an account?
            <a href="{{ route('member.login') }}" class="font-semibold hover:underline" style="color: var(--color-brand)">Log in</a>
        </p>
    </div>
@endsection
