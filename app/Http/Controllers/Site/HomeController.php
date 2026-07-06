<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Album;
use App\Models\Event;
use App\Models\Post;
use App\Models\Testimonial;
use App\Settings\HomeSettings;

class HomeController extends Controller
{
    public function index(HomeSettings $home)
    {
        $upcomingEvents = Event::upcoming()->limit(3)->get();
        $latestNews = Post::published()->limit(3)->get();
        $featuredPosts = Post::published()->where('is_featured', true)->limit(3)->get();
        $photoHighlights = Album::where('type', 'photo')
            ->latest()
            ->limit(6)
            ->get()
            ->filter(fn (Album $a) => $a->getFirstMedia('photos') !== null);
        $testimonials = Testimonial::published()
            ->where('is_featured', true)
            ->orderBy('sort_order')
            ->limit(6)
            ->get();

        return view('site.home', compact(
            'home',
            'upcomingEvents',
            'latestNews',
            'featuredPosts',
            'photoHighlights',
            'testimonials',
        ));
    }
}
