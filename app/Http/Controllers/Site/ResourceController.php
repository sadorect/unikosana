<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Resource;

class ResourceController extends Controller
{
    public function index()
    {
        $resources = Resource::published()
            ->orderBy('category')
            ->orderBy('sort_order')
            ->get()
            ->groupBy(fn (Resource $resource) => $resource->category->getLabel());

        return view('site.resources', compact('resources'));
    }
}
