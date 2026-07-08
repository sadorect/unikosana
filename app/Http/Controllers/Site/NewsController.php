<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::published();

        if ($type = $request->string('type')->toString()) {
            $query->where('type', $type);
        }

        $posts = $query->paginate(9)->withQueryString();

        return view('site.news.index', compact('posts'));
    }

    public function show(Post $post)
    {
        abort_if($post->published_at === null || $post->published_at->isFuture(), 404);

        $related = Post::published()
            ->where('id', '!=', $post->id)
            ->where('type', $post->type)
            ->limit(3)
            ->get();

        // Chronological navigation across all published posts.
        $previous = Post::published()
            ->where('published_at', '<', $post->published_at)
            ->reorder('published_at', 'desc')
            ->first();

        $next = Post::published()
            ->where('published_at', '>', $post->published_at)
            ->reorder('published_at', 'asc')
            ->first();

        return view('site.news.show', compact('post', 'related', 'previous', 'next'));
    }
}
