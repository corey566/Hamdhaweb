<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/search', [SearchController::class, 'index'])->name('search');
Route::get('/search/suggest', [SearchController::class, 'suggest'])->name('search.suggest');

Route::get('/new-arrivals', [ProductController::class, 'newArrivals'])->name('products.new-arrivals');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');

Route::get('/category/{slug}', [CategoryController::class, 'show'])->name('category.show');

Route::get('/product/{slug}', [ProductController::class, 'show'])->name('products.show');

Route::get('/page/{slug}', [PageController::class, 'show'])->name('pages.show');

Route::get('/wishlist/whatsapp', function (\Illuminate\Http\Request $request) {
    $slugs = array_filter(explode(',', $request->query('slugs', '')));

    return redirect(\App\Helpers\WhatsAppHelper::generateWishlistUrl($slugs));
})->name('wishlist.whatsapp');
