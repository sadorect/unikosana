@extends('layouts.public')

@section('title', 'About Us')
@section('meta_description', 'History, mission, vision and objectives of the Unikosa North America Unit.')

@section('content')
    @include('site.partials.page-header', ['title' => 'About Us', 'subtitle' => 'Our story, mission, and the principles that guide us.'])

    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 py-16 space-y-12">
        @if ($about->history)
            <section>
                <h2 class="text-2xl font-bold text-slate-900">Our History</h2>
                <div class="mt-4 space-y-4 leading-relaxed text-slate-600">{!! nl2br(e($about->history)) !!}</div>
            </section>
        @endif

        <div class="grid gap-6 md:grid-cols-2">
            @if ($about->mission)
                <div class="rounded-2xl bg-white p-8 shadow-sm ring-1 ring-slate-200">
                    <h2 class="text-lg font-semibold" style="color: var(--color-brand)">Mission</h2>
                    <p class="mt-3 text-slate-600">{{ $about->mission }}</p>
                </div>
            @endif
            @if ($about->vision)
                <div class="rounded-2xl bg-white p-8 shadow-sm ring-1 ring-slate-200">
                    <h2 class="text-lg font-semibold" style="color: var(--color-brand)">Vision</h2>
                    <p class="mt-3 text-slate-600">{{ $about->vision }}</p>
                </div>
            @endif
        </div>

        @if ($objectives->isNotEmpty())
            <section>
                <h2 class="text-2xl font-bold text-slate-900">Our Objectives</h2>
                <ul class="mt-4 space-y-3">
                    @foreach ($objectives as $objective)
                        <li class="flex gap-3 text-slate-600">
                            <span class="mt-1 h-2 w-2 shrink-0 rounded-full" style="background-color: var(--color-brand-accent)"></span>
                            <span>{{ $objective }}</span>
                        </li>
                    @endforeach
                </ul>
            </section>
        @endif

        @if ($about->org_structure || $about->org_structure_image_path)
            <section>
                <h2 class="text-2xl font-bold text-slate-900">Organizational Structure</h2>
                @if ($about->org_structure)
                    <div class="mt-4 space-y-4 leading-relaxed text-slate-600">{!! nl2br(e($about->org_structure)) !!}</div>
                @endif
                @if ($about->org_structure_image_path)
                    <img src="{{ asset('storage/' . $about->org_structure_image_path) }}" alt="Organizational structure" class="mt-6 w-full rounded-xl ring-1 ring-slate-200">
                @endif
            </section>
        @endif

        @if ($about->constitution_pdf_path)
            <section class="rounded-2xl p-8" style="background-color: color-mix(in srgb, var(--color-brand) 6%, white)">
                <h2 class="text-xl font-bold text-slate-900">Governing Documents</h2>
                <p class="mt-2 text-slate-600">Read our constitution and governing documents.</p>
                <a href="{{ asset('storage/' . $about->constitution_pdf_path) }}" target="_blank" rel="noopener"
                   class="mt-4 inline-flex items-center gap-2 rounded-full px-5 py-2.5 text-sm font-semibold text-white" style="background-color: var(--color-brand)">
                    Download Constitution (PDF)
                </a>
            </section>
        @endif
    </div>
@endsection
