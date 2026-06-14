<?php

namespace App\Http\Controllers;

use App\Models\CmsPage;

class PageController extends Controller
{
    public function show(string $slug)
    {
        $page = CmsPage::where('slug', $slug)->visible()->firstOrFail();

        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('home')],
            ['label' => $page->title, 'url' => null],
        ];

        return view('pages.cms.show', compact('page', 'breadcrumbs'));
    }
}
