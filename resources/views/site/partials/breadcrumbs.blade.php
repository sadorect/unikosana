@php($items ??= [])

@if (! empty($items))
    <nav aria-label="Breadcrumb" class="text-sm">
        <ol class="flex flex-wrap items-center gap-x-2 gap-y-1 text-slate-500">
            @foreach ($items as $item)
                <li class="flex items-center gap-x-2">
                    @unless ($loop->first)
                        <span class="text-slate-300" aria-hidden="true">/</span>
                    @endunless

                    @if (! $loop->last && ! empty($item['url']))
                        <a href="{{ $item['url'] }}" class="hover:text-slate-700 hover:underline">{{ $item['label'] }}</a>
                    @else
                        <span @if ($loop->last) aria-current="page" @endif class="font-medium text-slate-700">{{ $item['label'] }}</span>
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>
@endif
