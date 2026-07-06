@php
    $subtitle ??= null;
    $link ??= null;
    $linkLabel ??= 'View all';
@endphp

<div class="flex items-end justify-between gap-4">
    <div>
        <h2 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">{{ $title }}</h2>
        @if ($subtitle)
            <p class="mt-1 text-slate-500">{{ $subtitle }}</p>
        @endif
    </div>
    @if ($link)
        <a href="{{ $link }}" class="shrink-0 text-sm font-semibold hover:underline" style="color: var(--color-brand)">{{ $linkLabel }} &rarr;</a>
    @endif
</div>
