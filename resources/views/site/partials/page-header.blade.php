@php $subtitle ??= null; @endphp

<section class="text-white" style="background-color: var(--color-brand-dark)">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-14 sm:py-20">
        <h1 class="text-3xl font-extrabold tracking-tight sm:text-4xl">{{ $title }}</h1>
        @if ($subtitle)
            <p class="mt-3 max-w-2xl text-slate-200">{{ $subtitle }}</p>
        @endif
    </div>
</section>
