<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Leadership;

class LeadershipController extends Controller
{
    public function index()
    {
        $leaders = Leadership::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('site.leadership', compact('leaders'));
    }
}
