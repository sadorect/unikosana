@extends('layouts.public')

@section('title', 'News & Announcements')
@section('meta_description', 'Latest news, articles, announcements and press releases from Unikosa North America.')

@section('content')
    @include('site.partials.page-header', ['title' => 'News & Announcements', 'subtitle' => 'Stay up to date with our community.'])

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 pt-6">
        @include('site.partials.breadcrumbs', ['items' => [
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'News'],
        ]])
    </div>

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12">
        {{-- Type filter --}}
        <div class="mb-8 flex flex-wrap gap-2">
            @php $types = ['' => 'All', 'news' => 'News', 'article' => 'Articles', 'announcement' => 'Announcements', 'press_release' => 'Press Releases']; @endphp
            @foreach ($types as $value => $label)
                <a href="{{ route('news.index', array_filter(['type' => $value])) }}"
                   @class([
                       'rounded-full px-4 py-1.5 text-sm font-medium ring-1',
                       'text-white ring-transparent' => request('type', '') === $value,
                       'bg-white text-slate-600 ring-slate-200 hover:bg-slate-50' => request('type', '') !== $value,
                   ])
                   @if (request('type', '') === $value) style="background-color: var(--color-brand)" @endif>{{ $label }}</a>
            @endforeach
        </div>

        @if ($posts->isEmpty())
            <p class="text-center text-slate-500">No posts published yet.</p>
        @else
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($posts as $post)
                    @include('site.partials.post-card', ['post' => $post])
                @endforeach
            </div>
            <div class="mt-8">{{ $posts->links() }}</div>
        @endif
    </div>
@endsection
