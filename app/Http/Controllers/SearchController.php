<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->get('q', '');
        $products = collect();
        $count = 0;

        if (strlen($query) >= 2) {
            $products = Product::visible()
                ->search($query)
                ->with(['images' => fn ($q) => $q->orderBy('sort_order')->limit(2), 'categories', 'fabric'])
                ->latest()
                ->paginate(20);
            $count = $products->total();
        }

        $breadcrumbs = [
            ['label' => 'HOME', 'url' => route('home')],
            ['label' => 'SEARCH RESULTS', 'url' => null],
        ];

        return view('pages.search', compact('products', 'query', 'count', 'breadcrumbs'));
    }

    public function suggest(Request $request)
    {
        $query = trim($request->get('q', ''));

        if (strlen($query) < 2) {
            return response()->json(['results' => []]);
        }

        $results = Product::visible()
            ->search($query)
            ->with('fabric')
            ->latest()
            ->limit(8)
            ->get()
            ->map(fn (Product $product) => [
                'name' => $product->name,
                'slug' => $product->slug,
                'url' => route('products.show', $product->slug),
                'model_number' => $product->model_number,
                'price_lkr' => $product->has_discount ? $product->discount_price : $product->price,
                'thumbnail' => $product->coverThumbnailUrl(),
            ]);

        return response()->json(['results' => $results]);
    }
}
