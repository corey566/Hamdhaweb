@extends('layouts.app')

@section('title', $pageTitle ?? 'Products — Hamdha Clothing')

@section('content')
    <nav class="max-w-7xl mx-auto px-4 lg:px-16 pt-6 pb-2">
        <ol class="flex items-center text-xs text-text-light space-x-2">
            @foreach($breadcrumbs as $i => $crumb)
                @if(!is_null($crumb['url']))
                    <li><a href="{{ $crumb['url'] }}" class="hover:text-text-dark uppercase tracking-wide">{{ $crumb['label'] }}</a></li>
                @else
                    <li class="text-text-dark font-medium uppercase tracking-wide">{{ $crumb['label'] }}</li>
                @endif
                @if(!$loop->last)
                    <li class="text-text-light">/</li>
                @endif
            @endforeach
        </ol>
    </nav>

    <div class="text-center py-6">
        <h1 class="font-heading text-2xl lg:text-[28px] font-semibold tracking-[0.15em] text-text-dark uppercase">
            {{ $pageTitle }}
        </h1>
        <p class="text-xs text-text-light tracking-[0.1em] mt-1">( {{ $productCount }} PRODUCTS )</p>
    </div>

    @livewire(\App\Livewire\ProductFilter::class, [
        'categoryId' => $category?->id,
        'isNewArrivals' => $isNewArrivals ?? false,
        'fabrics' => $fabrics,
        'priceBuckets' => $priceBuckets,
        'categories' => $allCategories,
    ])
@endsection