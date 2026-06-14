<?php

namespace App\Http\Controllers;

use App\Helpers\WhatsAppHelper;
use App\Models\Category;
use App\Models\Fabric;
use App\Models\PriceBucket;
use App\Models\Product;
use App\Models\Setting;

class ProductController extends Controller
{
    public function index()
    {
        $productCount = Product::visible()->count();

        $fabrics = cache()->remember('fabrics_list', 3600, fn () => Fabric::ordered()->get());
        $priceBuckets = cache()->remember('price_buckets_list', 3600, fn () => PriceBucket::ordered()->get());
        $allCategories = cache()->remember('all_categories_filter', 3600, fn () => Category::visible()->ordered()->get());

        $breadcrumbs = [
            ['label' => 'HOME', 'url' => route('home')],
            ['label' => 'ALL PRODUCTS', 'url' => null],
        ];

        $category = null;
        $pageTitle = 'All Products';
        $filterTabs = Category::topLevel()->visible()->ordered()->get();
        $topLevelCollections = Category::topLevel()->visible()->ordered()->with('children')->get();
        $filterMode = 'multi';
        $dynamicTitle = false;

        return view('pages.products.index', compact(
            'category', 'productCount', 'fabrics', 'priceBuckets',
            'allCategories', 'breadcrumbs', 'pageTitle', 'filterTabs', 'topLevelCollections',
            'filterMode', 'dynamicTitle'
        ));
    }

    public function newArrivals()
    {
        $count = (int) Setting::get('new_arrivals_count', 8);
        $productCount = min(Product::visible()->count(), $count);

        $fabrics = cache()->remember('fabrics_list', 3600, fn () => Fabric::ordered()->get());
        $priceBuckets = cache()->remember('price_buckets_list', 3600, fn () => PriceBucket::ordered()->get());
        $allCategories = cache()->remember('all_categories_filter', 3600, fn () => Category::visible()->ordered()->get());

        $breadcrumbs = [
            ['label' => 'HOME', 'url' => route('home')],
            ['label' => 'NEW ARRIVALS', 'url' => null],
        ];

        $category = null;
        $pageTitle = 'New Arrivals';
        $isNewArrivals = true;
        $filterTabs = Category::topLevel()->visible()->ordered()->get();
        $topLevelCollections = Category::topLevel()->visible()->ordered()->with('children')->get();
        $filterMode = 'multi';
        $dynamicTitle = false;

        return view('pages.products.index', compact(
            'category', 'productCount', 'fabrics', 'priceBuckets',
            'allCategories', 'breadcrumbs', 'pageTitle', 'isNewArrivals', 'filterTabs', 'topLevelCollections',
            'filterMode', 'dynamicTitle'
        ));
    }

    public function show(string $slug)
    {
        $product = Product::where('slug', $slug)
            ->visible()
            ->with(['images', 'categories', 'sizeCharts', 'fabric'])
            ->firstOrFail();

        $whatsappUrl = WhatsAppHelper::generateOrderUrl($product);

        $primaryCategory = $product->primaryCategory();
        $relatedProducts = collect();
        if ($primaryCategory) {
            $relatedProducts = Product::visible()
                ->whereHas('categories', fn ($q) => $q->where('categories.id', $primaryCategory->id))
                ->where('id', '!=', $product->id)
                ->with(['images' => fn ($q) => $q->orderBy('sort_order')->limit(1), 'categories', 'fabric'])
                ->latest()
                ->limit(4)
                ->get();
        }

        if ($relatedProducts->count() < 4) {
            $remaining = 4 - $relatedProducts->count();
            $excludeIds = $relatedProducts->pluck('id')->push($product->id);
            $more = Product::visible()
                ->whereNotIn('id', $excludeIds)
                ->with(['images' => fn ($q) => $q->orderBy('sort_order')->limit(1), 'categories', 'fabric'])
                ->latest()
                ->limit($remaining)
                ->get();
            $relatedProducts = $relatedProducts->merge($more);
        }

        $breadcrumbs = [
            ['label' => 'HOME', 'url' => route('home')],
        ];
        if ($primaryCategory) {
            $breadcrumbs[] = ['label' => strtoupper($primaryCategory->name), 'url' => route('category.show', $primaryCategory->slug)];
        }
        $breadcrumbs[] = ['label' => strtoupper($product->name), 'url' => null];

        return view('pages.products.show', compact(
            'product', 'whatsappUrl', 'relatedProducts', 'breadcrumbs'
        ));
    }
}
