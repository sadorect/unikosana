<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Album;

class GalleryController extends Controller
{
    public function index()
    {
        $photoAlbums = Album::where('type', 'photo')
            ->orderByDesc('year')
            ->orderByDesc('created_at')
            ->get();

        $videoAlbums = Album::where('type', 'video')
            ->orderByDesc('year')
            ->orderByDesc('created_at')
            ->get();

        return view('site.gallery', compact('photoAlbums', 'videoAlbums'));
    }

    public function show(Album $album)
    {
        return view('site.album', compact('album'));
    }
}
