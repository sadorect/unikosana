@php
    $difficulty = $difficulty ?? 'default';
    $base = url('captcha/' . $difficulty);
@endphp

<div
    x-data="{ src: @js(captcha_src($difficulty)), refresh() { this.src = @js($base) + '?' + Date.now(); } }"
    class="space-y-2"
>
    <div class="flex items-center gap-3">
        <img :src="src" alt="Security code" class="h-12 rounded-md ring-1 ring-gray-950/10 dark:ring-white/20">
        <button
            type="button"
            x-on:click="refresh()"
            class="text-sm font-medium text-primary-600 hover:text-primary-500 dark:text-primary-400"
        >
            &#8635; Refresh
        </button>
    </div>
</div>
