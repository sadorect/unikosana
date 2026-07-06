@php
    // $url is provided by the includer.
    $embed = null;
    if (!empty($url)) {
        if (preg_match('~(?:youtube\.com/watch\?v=|youtu\.be/|youtube\.com/live/)([\w-]+)~', $url, $m)) {
            $embed = 'https://www.youtube.com/embed/' . $m[1];
        } elseif (preg_match('~vimeo\.com/(\d+)~', $url, $m)) {
            $embed = 'https://player.vimeo.com/video/' . $m[1];
        } elseif (str_contains($url, '/embed') || str_contains($url, 'player.')) {
            $embed = $url;
        }
    }
@endphp

@if ($embed)
    <div class="aspect-video w-full overflow-hidden rounded-xl bg-black">
        <iframe src="{{ $embed }}" class="h-full w-full" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
    </div>
@elseif (!empty($url))
    <a href="{{ $url }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 rounded-full px-5 py-2.5 text-sm font-semibold text-white" style="background-color: var(--color-brand)">Watch the stream &rarr;</a>
@endif
