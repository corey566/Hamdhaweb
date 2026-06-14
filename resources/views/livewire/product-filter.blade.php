<div>
    @if($dynamicTitle)
    <div class="text-center py-6 px-4">
        <h1 class="font-heading text-2xl lg:text-[28px] font-semibold tracking-[0.12em] text-text-dark capitalize">{{ $displayTitle }}</h1>
        <p class="text-xs text-text-light tracking-[0.1em] mt-1 wire:loading.remove wire:target="selectedProductTypes,selectedCollections,selectedFabrics,selectedPriceBucket,featuredOnly">( {{ $products->total() }} Products )</p>
        @if(!empty($siteSettings['plp_tagline']))
            <p class="plp-tagline">{{ $siteSettings['plp_tagline'] }}</p>
        @endif
    </div>
    @if($filterMode === 'single' && count($productTypeCategories))
    <div class="max-w-7xl mx-auto px-4 lg:px-16 pb-4 overflow-x-auto">
        <div class="flex gap-6 border-b border-border min-w-max justify-center">
            @foreach($productTypeCategories as $cat)
                <button type="button" wire:click="selectProductType({{ $cat->id }})"
                    class="filter-tab {{ in_array($cat->id, $selectedProductTypes) ? 'filter-tab-active' : '' }}">
                    {{ $cat->name }}
                </button>
            @endforeach
        </div>
    </div>
    @endif
    @endif

    <div class="max-w-7xl mx-auto px-4 lg:px-16 pb-4">
        <div class="flex flex-wrap items-center gap-4 lg:gap-6 border-b border-border pb-4">
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" type="button" class="flex items-center text-xs text-text-dark font-medium tracking-widest uppercase">
                    Collection
                    <svg class="w-4 h-4 ml-1 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open" @click.away="open = false" x-cloak class="absolute top-full left-0 mt-2 bg-white shadow-lg rounded-md border border-border py-3 px-4 min-w-[200px] z-20">
                    @foreach($collectionCategories as $cat)
                    <label class="flex items-center space-x-2 py-1.5 cursor-pointer">
                        <input type="checkbox" wire:model.live="selectedCollections" value="{{ $cat->id }}" class="w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary">
                        <span class="text-sm text-text-medium">{{ $cat->name }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" type="button" class="flex items-center text-xs text-text-dark font-medium tracking-widest uppercase">
                    Product Type
                    <svg class="w-4 h-4 ml-1 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open" @click.away="open = false" x-cloak class="absolute top-full left-0 mt-2 bg-white shadow-lg rounded-md border border-border py-3 px-4 min-w-[220px] max-h-64 overflow-y-auto z-20">
                    @foreach($productTypeCategories as $cat)
                    <label class="flex items-center space-x-2 py-1.5 cursor-pointer">
                        @if($filterMode === 'single')
                            <input type="radio" wire:click="selectProductType({{ $cat->id }})" @checked(in_array($cat->id, $selectedProductTypes)) name="product_type_filter" class="w-4 h-4 border-gray-300 text-primary focus:ring-primary">
                        @else
                            <input type="checkbox" wire:model.live="selectedProductTypes" value="{{ $cat->id }}" class="w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary">
                        @endif
                        <span class="text-sm text-text-medium">{{ $cat->name }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" type="button" class="flex items-center text-xs text-text-dark font-medium tracking-widest uppercase">
                    Fabric
                    <svg class="w-4 h-4 ml-1 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open" @click.away="open = false" x-cloak class="absolute top-full left-0 mt-2 bg-white shadow-lg rounded-md border border-border py-3 px-4 min-w-[200px] z-20">
                    @foreach($fabrics as $fabric)
                    <label class="flex items-center space-x-2 py-1.5 cursor-pointer">
                        <input type="checkbox" wire:model.live="selectedFabrics" value="{{ $fabric->id }}" class="w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary">
                        <span class="text-sm text-text-medium">{{ $fabric->name }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" type="button" class="flex items-center text-xs text-text-dark font-medium tracking-widest uppercase">
                    Price
                    <svg class="w-4 h-4 ml-1 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open" @click.away="open = false" x-cloak class="absolute top-full left-0 mt-2 bg-white shadow-lg rounded-md border border-border py-3 px-4 min-w-[200px] z-20">
                    @foreach($priceBuckets as $bucket)
                    <label class="flex items-center space-x-2 py-1.5 cursor-pointer">
                        <input type="radio" wire:model.live="selectedPriceBucket" value="{{ $bucket->id }}" name="price_bucket" class="w-4 h-4 border-gray-300 text-primary focus:ring-primary">
                        <span class="text-sm text-text-medium">{{ $bucket->label }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            @if($showFeaturedFilter)
            <label class="flex items-center gap-2 cursor-pointer ml-auto lg:ml-0">
                <input type="checkbox" wire:model.live="featuredOnly" class="w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary">
                <span class="text-xs font-medium tracking-widest uppercase text-text-dark">Featured designs</span>
            </label>
            @endif

            @if(count($activeFilters) > 0)
            <button wire:click="clearFilters" type="button" class="text-xs text-primary hover:underline uppercase tracking-wide">
                Clear all
            </button>
            @endif
        </div>

        @if(count($activeFilters) > 0)
        <div class="flex flex-wrap gap-2 mt-4">
            @foreach($activeFilters as $filter)
                <span class="inline-flex items-center gap-1.5 bg-offwhite border border-border text-text-dark text-[10px] font-medium tracking-wide uppercase px-2.5 py-1 rounded-sm">
                    {{ $filter['label'] }}
                    @if($filter['type'] === 'collection')
                        <button type="button" wire:click="removeCollection({{ $filter['id'] }})" class="text-text-light hover:text-sale" aria-label="Remove filter">&times;</button>
                    @elseif($filter['type'] === 'product_type')
                        <button type="button" wire:click="removeProductType({{ $filter['id'] }})" class="text-text-light hover:text-sale" aria-label="Remove filter">&times;</button>
                    @elseif($filter['type'] === 'fabric')
                        <button type="button" wire:click="removeFabric({{ $filter['id'] }})" class="text-text-light hover:text-sale" aria-label="Remove filter">&times;</button>
                    @elseif($filter['type'] === 'price')
                        <button type="button" wire:click="clearPrice" class="text-text-light hover:text-sale" aria-label="Remove filter">&times;</button>
                    @elseif($filter['type'] === 'featured')
                        <button type="button" wire:click="$set('featuredOnly', false)" class="text-text-light hover:text-sale" aria-label="Remove filter">&times;</button>
                    @endif
                </span>
            @endforeach
        </div>
        @endif
    </div>

    <div class="max-w-7xl mx-auto px-4 lg:px-16">
        <div class="grid grid-cols-2 items-stretch gap-3 lg:grid-cols-4 lg:gap-6">
            @forelse($products as $product)
                <x-product-card :product="$product" />
            @empty
                <div class="col-span-full text-center py-12 text-text-light">
                    No products found.
                </div>
            @endforelse
        </div>

        @if($products->hasPages() && $products->lastPage() > 1)
        <div class="mt-10 flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between gap-4">
            <p class="text-sm text-text-light text-center sm:text-left shrink-0">
                Showing {{ $products->firstItem() }}–{{ $products->lastItem() }} of {{ $products->total() }} results
            </p>
            <div class="flex justify-center sm:justify-end">
                {{ $products->onEachSide(1)->links('vendor.pagination.hamdha') }}
            </div>
        </div>
        @endif
    </div>
</div>
