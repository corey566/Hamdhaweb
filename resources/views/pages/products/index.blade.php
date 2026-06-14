@extends('layouts.app')

@section('title', ($pageTitle ?? 'Products') . ' — Hamdha Clothing')
@section('meta_description', 'Browse our ' . ($pageTitle ?? 'products') . ' collection of custom abayas.')

@section('content')
    <nav class="max-w-7xl mx-auto px-4 lg:px-16 pt-6 pb-2">
        <ol class="flex items-center flex-wrap text-xs text-text-light space-x-2">
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

    @unless($dynamicTitle ?? false)
    <div class="text-center py-6 px-4">
        <h1 class="font-heading text-2xl lg:text-[28px] font-semibold tracking-[0.12em] text-text-dark capitalize">{{ $pageTitle }}</h1>
        <p class="text-xs text-text-light tracking-[0.1em] mt-1">( {{ $productCount }} Products )</p>
        @if(!empty($siteSettings['plp_tagline']))
            <p class="plp-tagline">{{ $siteSettings['plp_tagline'] }}</p>
        @endif
    </div>
    @endunless

    @if(isset($filterTabs) && $filterTabs->count())
        @php
            $useColumns = ($siteSettings['collection_layout'] ?? 'tabs') === 'columns'
                && isset($topLevelCollections)
                && $topLevelCollections->count() >= 2
                && ! ($category ?? null)?->parent_id;
        @endphp

        @if($useColumns)
        <div class="max-w-7xl mx-auto px-4 lg:px-16 mb-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-12 border-b border-border pb-6">
                @foreach($topLevelCollections as $collection)
                <div>
                    <a href="{{ route('category.show', $collection->slug) }}" class="text-sm font-semibold tracking-widest uppercase text-text-dark hover:text-primary">
                        {{ $collection->name }}
                    </a>
                    <div class="flex flex-wrap gap-x-4 gap-y-1 mt-3">
                        @foreach($collection->children as $child)
                            <a href="{{ route('category.show', $child->slug) }}"
                               class="text-xs tracking-wide uppercase py-1 {{ ($category && $category->id === $child->id) ? 'text-primary font-medium' : 'text-text-medium hover:text-primary' }}">
                                {{ $child->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @else
        <div class="max-w-7xl mx-auto px-4 lg:px-16 border-b border-border overflow-x-auto mb-2">
            <div class="flex items-center gap-6 min-w-max py-1">
                @if(($category ?? null) && ($category->parent_id || $category->children->count()))
                    @php
                        $allSlug = $category->parent_id ? $category->parent->slug : $category->slug;
                        $onAll = $category->parent_id === null;
                    @endphp
                    <a href="{{ route('category.show', $allSlug) }}" class="filter-tab {{ $onAll ? 'filter-tab-active' : '' }}">All</a>
                @endif
                @foreach($filterTabs as $tab)
                    <a href="{{ route('category.show', $tab->slug) }}"
                       class="filter-tab {{ ($category && $category->id === $tab->id) ? 'filter-tab-active' : '' }}">
                        {{ $tab->name }}
                    </a>
                @endforeach
            </div>
        </div>
        @endif
    @endif

    @livewire(\App\Livewire\ProductFilter::class, [
        'categoryId' => $category?->id,
        'isNewArrivals' => $isNewArrivals ?? false,
        'fabrics' => $fabrics,
        'priceBuckets' => $priceBuckets,
        'categories' => $allCategories,
        'showFeaturedFilter' => $siteSettings['featured_filter_enabled'] ?? true,
        'filterMode' => $filterMode ?? 'multi',
        'pageTitle' => $pageTitle,
        'dynamicTitle' => $dynamicTitle ?? false,
    ])
@endsection
