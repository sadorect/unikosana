@extends('layouts.public')

@section('title', 'Blog')
@section('meta_description', 'Articles and stories from the Unikosa North America community.')

@section('content')
    @include('site.partials.page-header', ['title' => 'Blog', 'subtitle' => 'Articles and stories from our community.'])

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 pt-6">
        @include('site.partials.breadcrumbs', ['items' => [
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Blog'],
        ]])
    </div>

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12">
        @if ($posts->isEmpty())
            <p class="text-center text-slate-500">No articles published yet.</p>
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
