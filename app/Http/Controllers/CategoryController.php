<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Fabric;
use App\Models\PriceBucket;

class CategoryController extends Controller
{
    public function show(string $slug)
    {
        $category = Category::where('slug', $slug)->visible()->firstOrFail();

        $productCount = $category->products()->visible()->count();

        $fabrics = cache()->remember('fabrics_list', 3600, function () {
            return Fabric::ordered()->get();
        });

        $priceBuckets = cache()->remember('price_buckets_list', 3600, function () {
            return PriceBucket::ordered()->get();
        });

        $allCategories = cache()->remember('all_categories_filter', 3600, function () {
            return Category::visible()->ordered()->get();
        });

        $breadcrumbs = [
            ['label' => 'HOME', 'url' => route('home')],
            ['label' => strtoupper($category->name), 'url' => null],
        ];
        $pageTitle = strtoupper($category->name);
        if ($category->parent) {
            array_splice($breadcrumbs, 1, 0, [[
                'label' => strtoupper($category->parent->name),
                'url' => route('category.show', $category->parent->slug),
            ]]);
        }

        return view('pages.products.index', compact(
            'category', 'productCount', 'fabrics', 'priceBuckets',
            'allCategories', 'breadcrumbs', 'pageTitle'
        ));
    }
}
