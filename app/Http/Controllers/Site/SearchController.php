<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Member;
use App\Models\Post;
use App\Models\Resource;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $term = $request->string('q')->trim()->toString();

        $members = collect();
        $events = collect();
        $posts = collect();
        $resources = collect();

        if ($term !== '') {
            $like = "%{$term}%";

            $members = Member::public()
                ->where(fn ($q) => $q->where('full_name', 'like', $like)
                    ->orWhere('occupation', 'like', $like)
                    ->orWhere('school', 'like', $like))
                ->limit(10)->get();

            $events = Event::where(fn ($q) => $q->where('title', 'like', $like)
                ->orWhere('description', 'like', $like)
                ->orWhere('venue', 'like', $like))
                ->limit(10)->get();

            $posts = Post::published()
                ->where(fn ($q) => $q->where('title', 'like', $like)
                    ->orWhere('body', 'like', $like)
                    ->orWhere('excerpt', 'like', $like))
                ->limit(10)->get();

            $resources = Resource::published()
                ->where(fn ($q) => $q->where('title', 'like', $like)
                    ->orWhere('description', 'like', $like))
                ->limit(10)->get();
        }

        $total = $members->count() + $events->count() + $posts->count() + $resources->count();

        return view('site.search', compact('term', 'members', 'events', 'posts', 'resources', 'total'));
    }
}
