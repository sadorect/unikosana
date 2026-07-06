@extends('layouts.public')

@section('title', 'FAQ')
@section('meta_description', 'Frequently asked questions about the Unikosa North America Unit.')

@section('content')
    @include('site.partials.page-header', ['title' => 'Frequently Asked Questions', 'subtitle' => 'Answers to the questions we hear most often.'])

    <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8 py-12 space-y-10">
        @forelse ($faqs as $category => $items)
            <section>
                @if ($faqs->count() > 1)
                    <h2 class="mb-4 text-xl font-bold text-slate-900">{{ $category }}</h2>
                @endif
                <div class="divide-y divide-slate-200 overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
                    @foreach ($items as $faq)
                        <details class="group p-5 [&_summary::-webkit-details-marker]:hidden">
                            <summary class="flex cursor-pointer items-center justify-between gap-4 font-medium text-slate-900">
                                {{ $faq->question }}
                                <span class="shrink-0 transition group-open:rotate-180" style="color: var(--color-brand)">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </span>
                            </summary>
                            <div class="mt-3 leading-relaxed text-slate-600">{!! nl2br(e($faq->answer)) !!}</div>
                        </details>
                    @endforeach
                </div>
            </section>
        @empty
            <p class="text-center text-slate-500">No FAQs published yet.</p>
        @endforelse
    </div>
@endsection
