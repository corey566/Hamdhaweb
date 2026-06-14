@extends('layouts.app')

@section('title', 'Search Results for "' . $query . '" — Hamdha Clothing')
@section('meta_description', 'Search results for "' . $query . '" at Hamdha Clothing.')

@section('content')
<!-- Breadcrumbs -->
<nav class="max-w-7xl mx-auto px-4 lg:px-16 pt-6 pb-2">
  <ol class="flex items-center text-xs text-text-light uppercase tracking-wide space-x-2">
    @foreach($breadcrumbs as $breadcrumb)
      @if($breadcrumb['url'])
        <li><a href="{{ $breadcrumb['url'] }}" class="hover:text-text-dark">{{ $breadcrumb['label'] }}</a></li>
        <li class="text-text-light">/</li>
      @else
        <li class="text-text-dark font-medium">{{ $breadcrumb['label'] }}</li>
      @endif
    @endforeach
  </ol>
</nav>

<!-- Page Header -->
<div class="text-center py-6">
  <h1 class="font-heading text-2xl lg:text-[28px] font-semibold tracking-[0.15em] text-text-dark uppercase">
    SEARCH RESULTS
  </h1>
  <p class="text-xs text-text-light tracking-[0.1em] mt-1">
    FOR "{{ $query }}" ( {{ $count }} PRODUCTS )
  </p>
</div>

<!-- Results -->
<div class="max-w-7xl mx-auto px-4 lg:px-16 pb-16">
  @if($products->count() > 0)
    <div class="grid grid-cols-2 items-stretch gap-3 lg:grid-cols-4 lg:gap-6">
      @foreach($products as $product)
        <x-product-card :product="$product" />
      @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-10">{!! $products->links() !!}</div>
  @else
    <div class="text-center py-16">
      <p class="text-lg text-text-medium">No products found for your search. Try different keywords.</p>
      <a href="{{ route('products.index') }}" class="inline-block mt-6 px-6 py-3 bg-primary text-white text-sm font-medium tracking-wide rounded-sm hover:bg-primary-hover transition">
        VIEW ALL PRODUCTS
      </a>
    </div>
  @endif
</div>
@endsection