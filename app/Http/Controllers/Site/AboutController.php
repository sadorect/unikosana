<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Settings\AboutSettings;

class AboutController extends Controller
{
    public function index(AboutSettings $about)
    {
        $objectives = collect(preg_split('/\r\n|\r|\n/', (string) $about->objectives))
            ->map(fn ($line) => trim($line))
            ->filter()
            ->values();

        return view('site.about', compact('about', 'objectives'));
    }
}
