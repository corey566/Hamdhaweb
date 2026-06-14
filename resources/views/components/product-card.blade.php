@props(['product', 'currentCategory' => null])

@php
    $displayPrice = $product->has_discount ? $product->discount_price : $product->price;
    $tagCategories = $product->categories->when($currentCategory, fn ($c) => $c->where('id', '!=', $currentCategory->id));
    $coverUrl = $product->coverThumbnailUrl();
    $hoverUrl = $product->hoverImageUrl();
@endphp

<article class="group relative product-card flex h-full flex-col">
    <a href="{{ route('products.show', $product->slug) }}" class="flex h-full flex-col text-left">
        <div class="relative aspect-[4/5] shrink-0 overflow-hidden bg-gray-100 product-card-image-stack">
            @if($siteSettings['wishlist_enabled'] ?? true)
            <button type="button" @click.stop.prevent="$store.hamdha.toggleWishlist('{{ $product->slug }}')"
                class="absolute top-2 left-2 z-10 w-8 h-8 rounded-full bg-white/90 flex items-center justify-center shadow-sm hover:bg-white transition"
                :class="$store.hamdha.isInWishlist('{{ $product->slug }}') ? 'text-sale' : 'text-text-light'" aria-label="Add to wish list">
                <svg class="w-4 h-4" :fill="$store.hamdha.isInWishlist('{{ $product->slug }}') ? 'currentColor' : 'none'" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
            </button>
            @endif
            @if($coverUrl)
                <img src="{{ $coverUrl }}" alt="{{ $product->name }}" class="product-card-cover" loading="lazy">
            @endif
            @if($hoverUrl)
                <img src="{{ $hoverUrl }}" alt="" class="product-card-hover" loading="lazy">
            @endif
            @if(!$coverUrl && !$hoverUrl)
                <div class="absolute inset-0 flex items-center justify-center text-text-light text-sm">No Image</div>
            @endif
            @if($product->has_discount)
                <span class="absolute top-2 right-2 bg-sale text-white text-[9px] font-semibold px-1.5 py-0.5 rounded z-10">SALE</span>
            @endif
        </div>

        <div class="mt-3 flex min-h-[7.5rem] flex-1 flex-col product-card-info">
            <h3 class="text-[13px] font-medium text-text-dark leading-snug line-clamp-2 min-h-[2.75rem]">
                {{ $product->name }}
            </h3>
            <div class="mt-2 flex min-h-[1.375rem] flex-wrap items-center gap-x-1 gap-y-0.5">
                @if($product->has_discount)
                    <x-price :amount="$product->discount_price" class="text-sm font-semibold text-sale" />
                    <span class="text-xs text-text-light line-through">Rs. {{ number_format($product->price) }}</span>
                @else
                    <x-price :amount="$displayPrice" class="text-sm font-semibold text-text-dark" />
                @endif
            </div>
            <div class="mt-auto flex min-h-[1.625rem] flex-wrap items-end justify-start gap-1 pt-3">
                @foreach($tagCategories as $cat)
                    <span class="tag-pill-sm">{{ $cat->name }}</span>
                @endforeach
            </div>
        </div>
    </a>
</article>
