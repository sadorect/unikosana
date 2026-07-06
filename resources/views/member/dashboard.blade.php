@extends('layouts.public')

@section('title', 'My Membership')

@section('content')
    @include('site.partials.page-header', ['title' => 'My Membership', 'subtitle' => 'Manage your directory profile.'])

    <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8 py-12">
        @if (session('success'))
            <p class="mb-6 rounded-lg bg-green-50 p-4 text-sm text-green-800 ring-1 ring-green-200">{{ session('success') }}</p>
        @endif

        {{-- Status banner --}}
        @php $status = $member->status; @endphp
        <div @class([
            'mb-8 flex items-center gap-3 rounded-xl p-4 text-sm ring-1',
            'bg-amber-50 text-amber-800 ring-amber-200' => $status === \App\Enums\MemberStatus::Pending,
            'bg-green-50 text-green-800 ring-green-200' => $status === \App\Enums\MemberStatus::Approved,
            'bg-red-50 text-red-800 ring-red-200' => $status === \App\Enums\MemberStatus::Rejected,
        ])>
            <span class="font-semibold">Status: {{ $status->getLabel() }}.</span>
            @if ($status === \App\Enums\MemberStatus::Pending)
                <span>Your membership is awaiting approval by an administrator.</span>
            @elseif ($status === \App\Enums\MemberStatus::Approved)
                <span>Your membership is active. Toggle visibility below to appear in the public directory.</span>
            @else
                <span>Please contact us for more information.</span>
            @endif
        </div>

        @if ($errors->any())
            <ul class="mb-6 list-inside list-disc rounded-lg bg-red-50 p-4 text-sm text-red-800 ring-1 ring-red-200">
                @foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        @endif

        <form method="POST" action="{{ route('member.profile.update') }}" enctype="multipart/form-data" class="space-y-5 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            @csrf
            @method('PUT')

            <div class="flex items-center gap-4">
                <div class="h-20 w-20 overflow-hidden rounded-full bg-slate-100">
                    @if ($member->photo_url)
                        <img src="{{ $member->photo_url }}" alt="{{ $member->full_name }}" class="h-full w-full object-cover">
                    @endif
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Profile photo</label>
                    <input type="file" name="photo" accept="image/*" class="mt-1 text-sm">
                </div>
            </div>

            <div class="grid gap-5 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-slate-700">Full name</label>
                    <input type="text" name="full_name" value="{{ old('full_name', $member->full_name) }}" required class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Country</label>
                    <input type="text" name="country" value="{{ old('country', $member->country) }}" required class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">State / Province</label>
                    <input type="text" name="state_province" value="{{ old('state_province', $member->state_province) }}" class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Occupation</label>
                    <input type="text" name="occupation" value="{{ old('occupation', $member->occupation) }}" class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">School</label>
                    <input type="text" name="school" value="{{ old('school', $member->school) }}" class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Graduation year</label>
                    <input type="number" name="graduation_year" value="{{ old('graduation_year', $member->graduation_year) }}" min="1900" max="2100" class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Phone</label>
                    <input type="tel" name="contact_phone" value="{{ old('contact_phone', $member->contact_phone) }}" class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2 text-sm">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-slate-700">Bio</label>
                    <textarea name="biography" rows="4" class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2 text-sm">{{ old('biography', $member->biography) }}</textarea>
                </div>
            </div>

            <label class="flex items-center gap-2 text-sm text-slate-700">
                <input type="checkbox" name="is_public" value="1" @checked(old('is_public', $member->is_public)) class="rounded border-slate-300">
                Show my profile in the public members directory
            </label>

            <button type="submit" class="rounded-full px-6 py-3 text-sm font-semibold text-white" style="background-color: var(--color-brand)">Save changes</button>
        </form>

        <form method="POST" action="{{ route('member.logout') }}" class="mt-6 text-right">
            @csrf
            <button type="submit" class="text-sm font-medium text-slate-500 hover:text-slate-800">Log out</button>
        </form>
    </div>
@endsection
