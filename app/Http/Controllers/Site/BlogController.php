<?php

namespace App\Http\Controllers\Site;

use App\Enums\PostType;
use App\Http\Controllers\Controller;
use App\Models\Post;

class BlogController extends Controller
{
    public function index()
    {
        $posts = Post::published()
            ->where('type', PostType::Article->value)
            ->paginate(9);

        return view('site.blog', compact('posts'));
    }
}
