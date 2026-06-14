<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\HomepageSection;
use App\Models\Product;
use App\Models\Setting;

class HomeController extends Controller
{
    public function index()
    {
        $categories = cache()->remember('nav_categories', 3600, function () {
            return Category::topLevel()
                ->visible()
                ->ordered()
                ->get();
        });

        $featuredProducts = cache()->remember('featured_products', 1800, function () {
            return Product::visible()
                ->featured()
                ->withPrimaryCategory()
                ->with(['images' => fn ($q) => $q->orderBy('sort_order')->limit(2), 'fabric'])
                ->latest()
                ->limit(8)
                ->get();
        });

        if ($featuredProducts->isEmpty()) {
            $featuredProducts = cache()->remember('latest_products_home', 1800, function () {
                $count = (int) Setting::get('new_arrivals_count', 8);

                return Product::visible()
                    ->withPrimaryCategory()
                    ->with(['images' => fn ($q) => $q->orderBy('sort_order')->limit(2), 'fabric'])
                    ->latest()
                    ->limit($count)
                    ->get();
            });
        }

        $hero = HomepageSection::getSection('hero');
        $steps = HomepageSection::getSection('customization_steps');
        $mission = HomepageSection::getSection('mission');

        return view('pages.home', compact(
            'categories', 'featuredProducts', 'hero', 'steps', 'mission'
        ));
    }
}
