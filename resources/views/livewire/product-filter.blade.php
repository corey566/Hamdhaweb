<div>
    <div class="max-w-7xl mx-auto px-4 lg:px-16 pb-8">
        <div class="flex flex-wrap items-center gap-4 lg:gap-8 border-b border-border pb-4">

            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center text-sm text-text-dark font-medium tracking-wide">
                    COLLECTION
                    <svg class="w-4 h-4 ml-1 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div x-show="open" @click.away="open = false" style="display: none;" class="absolute top-full left-0 mt-2 bg-white shadow-lg rounded-md border border-border py-3 px-4 min-w-[200px] z-20">
                    @foreach($categories as $cat)
                    <label class="flex items-center space-x-2 py-1.5 cursor-pointer">
                        <input type="checkbox" wire:model.live="selectedCategories" value="{{ $cat->id }}" class="w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary">
                        <span class="text-sm text-text-medium">{{ $cat->name }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center text-sm text-text-dark font-medium tracking-wide">
                    FABRIC
                    <svg class="w-4 h-4 ml-1 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div x-show="open" @click.away="open = false" style="display: none;" class="absolute top-full left-0 mt-2 bg-white shadow-lg rounded-md border border-border py-3 px-4 min-w-[200px] z-20">
                    @foreach($fabrics as $fabric)
                    <label class="flex items-center space-x-2 py-1.5 cursor-pointer">
                        <input type="checkbox" wire:model.live="selectedFabrics" value="{{ $fabric->id }}" class="w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary">
                        <span class="text-sm text-text-medium">{{ $fabric->name }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center text-sm text-text-dark font-medium tracking-wide">
                    PRICE
                    <svg class="w-4 h-4 ml-1 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div x-show="open" @click.away="open = false" style="display: none;" class="absolute top-full left-0 mt-2 bg-white shadow-lg rounded-md border border-border py-3 px-4 min-w-[200px] z-20">
                    @foreach($priceBuckets as $bucket)
                    <label class="flex items-center space-x-2 py-1.5 cursor-pointer">
                        <input type="radio" wire:model.live="selectedPriceBucket" value="{{ $bucket->id }}" name="price_bucket" class="w-4 h-4 border-gray-300 text-primary focus:ring-primary">
                        <span class="text-sm text-text-medium">{{ $bucket->label }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            @if(count($selectedCategories) > 0 || count($selectedFabrics) > 0 || $selectedPriceBucket !== null)
            <button wire:click="clearFilters" class="text-xs text-primary hover:underline ml-auto">
                CLEAR ALL
            </button>
            @endif
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 lg:px-16">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-6">
            @forelse($products as $product)
                <x-product-card :product="$product" />
            @empty
                <div class="col-span-full text-center py-12 text-text-light">
                    No products found.
                </div>
            @endforelse
        </div>

        @if($products->hasPages())
        <div class="mt-10">
            {!! $products->links() !!}
        </div>
        @endif
    </div>
</div>