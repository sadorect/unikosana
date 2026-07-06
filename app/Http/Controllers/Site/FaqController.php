<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Faq;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::published()
            ->orderBy('sort_order')
            ->get()
            ->groupBy(fn (Faq $faq) => $faq->category ?: 'General');

        return view('site.faq', compact('faqs'));
    }
}
