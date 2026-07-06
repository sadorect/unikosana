<article class="group overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-slate-200 transition hover:shadow-md">
    <a href="{{ route('news.show', $post) }}" class="block">
        <div class="aspect-[16/9] w-full overflow-hidden bg-slate-100">
            @if ($post->featured_image_url)
                <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" class="h-full w-full object-cover transition group-hover:scale-105">
            @else
                <div class="flex h-full items-center justify-center" style="background-color: color-mix(in srgb, var(--color-brand) 8%, white)">
                    <span class="text-sm text-slate-400">{{ $post->type->getLabel() }}</span>
                </div>
            @endif
        </div>
    </a>
    <div class="p-5">
        <div class="flex items-center gap-2 text-xs text-slate-500">
            <span class="rounded-full px-2 py-0.5 font-medium" style="background-color: color-mix(in srgb, var(--color-brand-accent) 20%, white); color: var(--color-brand-dark)">{{ $post->type->getLabel() }}</span>
            @if ($post->published_at)<time>{{ $post->published_at->format('M j, Y') }}</time>@endif
        </div>
        <h3 class="mt-2 font-semibold text-slate-900">
            <a href="{{ route('news.show', $post) }}" class="hover:underline">{{ $post->title }}</a>
        </h3>
        @if ($post->excerpt)
            <p class="mt-1 line-clamp-2 text-sm text-slate-500">{{ $post->excerpt }}</p>
        @endif
    </div>
</article>
