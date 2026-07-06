<article class="group overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-slate-200 transition hover:shadow-md">
    <a href="{{ route('events.show', $event) }}" class="block">
        <div class="aspect-[3/2] w-full overflow-hidden bg-slate-100">
            @if ($event->flyer_url)
                <img src="{{ $event->flyer_url }}" alt="{{ $event->title }}" class="h-full w-full object-cover transition group-hover:scale-105">
            @else
                <div class="flex h-full items-center justify-center text-slate-400" style="background-color: color-mix(in srgb, var(--color-brand) 8%, white)">
                    <span class="text-sm">{{ $event->title }}</span>
                </div>
            @endif
        </div>
    </a>
    <div class="p-5">
        <div class="flex items-center gap-2 text-xs">
            <span class="rounded-full px-2 py-0.5 font-medium text-white" style="background-color: var(--color-brand)">{{ $event->status->getLabel() }}</span>
            <time class="text-slate-500">{{ $event->date->format('M j, Y') }}</time>
        </div>
        <h3 class="mt-2 font-semibold text-slate-900">
            <a href="{{ route('events.show', $event) }}" class="hover:underline">{{ $event->title }}</a>
        </h3>
        @if ($event->venue)
            <p class="mt-1 text-sm text-slate-500">{{ $event->venue }}</p>
        @endif
    </div>
</article>
