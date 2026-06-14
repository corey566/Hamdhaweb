@extends('layouts.app')

@section('title', $product->name . ' — Hamdha Clothing')
@section('meta_description', Str::limit(strip_tags($product->description), 160))

@section('content')
@section('json_ld')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Product",
    "name": "{{ $product->name }}",
    "image": "{{ $product->coverImageUrl() ?? '' }}",
    "description": "{{ Str::limit(strip_tags($product->description), 200) }}",
    "sku": "{{ $product->model_number }}",
    "offers": {
        "@type": "Offer",
        "price": "{{ $product->discount_price ?? $product->price }}",
        "priceCurrency": "LKR",
        "availability": "https://schema.org/InStock"
    }
}
</script>
@overwrite

@php
    $gallery = $product->galleryImages();
@endphp

<div class="max-w-7xl mx-auto px-4 lg:px-16 py-6">
  <nav class="mb-6">
    <ol class="flex items-center text-xs text-text-light uppercase tracking-wider flex-wrap">
      @foreach($breadcrumbs as $i => $crumb)
        @if($i > 0)
          <li class="mx-2">/</li>
        @endif
        @if($crumb['url'])
          <li><a href="{{ $crumb['url'] }}" class="hover:text-text-dark">{{ $crumb['label'] }}</a></li>
        @else
          <li class="text-text-dark font-medium">{{ $crumb['label'] }}</li>
        @endif
      @endforeach
    </ol>
  </nav>

  <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 lg:gap-10">
    <div class="lg:col-span-3" x-data="{
      activeImage: 0,
      images: @js(collect($gallery)->pluck('full')->values()->all()),
      touchStartX: 0,
      next() { if (this.images.length) this.activeImage = (this.activeImage + 1) % this.images.length; },
      prev() { if (this.images.length) this.activeImage = (this.activeImage - 1 + this.images.length) % this.images.length; },
      onTouchStart(e) { this.touchStartX = e.changedTouches[0].screenX; },
      onTouchEnd(e) {
        const diff = e.changedTouches[0].screenX - this.touchStartX;
        if (Math.abs(diff) > 50) { diff < 0 ? this.next() : this.prev(); }
      }
    }">
      @if(count($gallery))
      <div class="grid grid-cols-[70px_1fr] gap-3 lg:gap-4">
        <div class="hidden lg:flex flex-col gap-2">
          @foreach($gallery as $i => $item)
          <button
            type="button"
            @click="activeImage = {{ $i }}"
            class="flex-shrink-0 w-[70px] aspect-[4/5] overflow-hidden border-2 transition"
            :class="activeImage === {{ $i }} ? 'border-primary' : 'border-transparent'"
          >
            <img src="{{ $item['thumb'] }}" class="w-full h-full object-cover" loading="lazy" alt="{{ $product->name }} thumbnail">
          </button>
          @endforeach
        </div>

        <div class="relative aspect-[4/5] overflow-hidden bg-gray-100"
             @touchstart="onTouchStart($event)" @touchend="onTouchEnd($event)">
          <template x-for="(src, index) in images" :key="index">
            <img :src="src" class="absolute inset-0 w-full h-full object-cover transition-opacity duration-500"
                 :class="activeImage === index ? 'opacity-100 z-10' : 'opacity-0 z-0'"
                 alt="{{ $product->name }}">
          </template>

          @if(count($gallery) > 1)
          <div class="lg:hidden absolute bottom-3 left-0 right-0 flex justify-center gap-1.5 z-20">
            @foreach($gallery as $i => $item)
              <button type="button" @click="activeImage = {{ $i }}" class="w-1.5 h-1.5 rounded-full transition"
                      :class="activeImage === {{ $i }} ? 'bg-primary w-4' : 'bg-white/70'"></button>
            @endforeach
          </div>
          <button type="button" @click="prev()" class="lg:hidden absolute left-2 top-1/2 -translate-y-1/2 z-20 w-9 h-9 rounded-full bg-white/90 shadow flex items-center justify-center" aria-label="Previous image">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
          </button>
          <button type="button" @click="next()" class="lg:hidden absolute right-2 top-1/2 -translate-y-1/2 z-20 w-9 h-9 rounded-full bg-white/90 shadow flex items-center justify-center" aria-label="Next image">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
          </button>
          @endif
        </div>
      </div>
      @else
      <div class="aspect-[4/5] bg-gray-100 flex items-center justify-center text-text-light text-sm">No image</div>
      @endif
    </div>

    <div class="lg:col-span-2 lg:pl-4" x-data="{ showSizeGuide: false }">
      <div class="flex flex-wrap gap-1.5 mb-5">
        @foreach($product->categories as $cat)
          <span class="tag-pill-sm">{{ $cat->name }}</span>
        @endforeach
        <span class="tag-pill-outline">{{ $product->model_number }}</span>
      </div>

      <h1 class="text-lg lg:text-xl font-semibold text-text-dark leading-tight">
        {{ $product->name }}
      </h1>

      <div class="mt-3">
        @if($product->has_discount)
          <x-price :amount="$product->discount_price" class="text-xl font-bold text-sale" />
          <span class="text-sm text-text-light line-through ml-2">Rs. {{ number_format($product->price) }}</span>
        @else
          <x-price :amount="$product->price" class="text-xl font-bold text-text-dark" />
        @endif
      </div>

      <hr class="my-4 border-border">

      @if($product->description)
      <div class="text-sm text-text-medium leading-relaxed mb-6 prose prose-sm max-w-none">{!! $product->description !!}</div>
      @endif

      <div class="space-y-2">
        <div class="flex">
          <span class="text-xs font-semibold tracking-widest uppercase text-text-dark w-24">FABRIC</span>
          <span class="text-sm text-text-medium">{{ $product->fabricLabel() }}</span>
        </div>
        <div class="flex">
          <span class="text-xs font-semibold tracking-widest uppercase text-text-dark w-24">COLORS</span>
          <span class="text-sm text-text-medium">{{ $product->colors ?? 'N/A' }}</span>
        </div>
        <div class="flex">
          <span class="text-xs font-semibold tracking-widest uppercase text-text-dark w-24">SIZE</span>
          @if($product->sizeCharts->count())
            <button type="button" @click="showSizeGuide = true" class="text-sm text-primary underline font-medium hover:text-primary-hover">
              SIZE GUIDE
            </button>
          @else
            <span class="text-sm text-text-medium">Standard</span>
          @endif
        </div>
      </div>

      <div class="flex flex-col sm:flex-row gap-3 mt-8">
        <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener"
          class="flex-1 flex items-center justify-center gap-3 bg-primary text-white font-semibold text-sm tracking-wide py-4 rounded-sm hover:bg-primary-hover transition">
          <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.861-2.03-.961-.273-.096-.474-.048-.675.149-.2.2-.849.861-1.041 1.041-.189.2-.381.2-.678.069-.297-.149-1.255-.461-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
          PLACE ORDER VIA WHATSAPP
        </a>
        @if($siteSettings['wishlist_enabled'] ?? true)
        <button type="button" @click="$store.hamdha.toggleWishlist('{{ $product->slug }}')"
          class="flex items-center justify-center gap-2 px-6 py-4 border border-primary text-primary text-sm font-semibold tracking-wide rounded-sm hover:bg-primary hover:text-white transition"
          :class="$store.hamdha.isInWishlist('{{ $product->slug }}') ? 'bg-primary text-white' : ''">
          <svg class="w-5 h-5" :fill="$store.hamdha.isInWishlist('{{ $product->slug }}') ? 'currentColor' : 'none'" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
          </svg>
          <span x-text="$store.hamdha.isInWishlist('{{ $product->slug }}') ? 'Saved' : 'Wish list'"></span>
        </button>
        @endif
      </div>

      @if($product->sizeCharts->count())
      <div x-show="showSizeGuide" x-transition x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4"
           @keydown.escape.window="showSizeGuide = false">
        <div class="fixed inset-0 bg-black/50" @click="showSizeGuide = false"></div>
        <div class="relative bg-white rounded-lg max-w-lg w-full max-h-[90vh] overflow-y-auto p-6 z-10">
          <button type="button" @click="showSizeGuide = false" class="absolute top-3 right-3 text-text-light hover:text-text-dark" aria-label="Close">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
          </button>
          <h3 class="text-lg font-semibold text-text-dark mb-4">Size Guide</h3>
          <div class="space-y-4">
            @foreach($product->sizeCharts as $chart)
              <div>
                <p class="text-sm font-medium text-text-dark mb-2">{{ $chart->name }}</p>
                @if($chart->image_path)
                <img src="{{ asset('storage/' . $chart->image_path) }}" class="w-full rounded" alt="{{ $chart->name }}">
                @endif
              </div>
            @endforeach
          </div>
        </div>
      </div>
      @endif
    </div>
  </div>

  @if($relatedProducts->count())
  <section class="py-16 border-t border-border mt-12">
    <div class="text-center">
      <h2 class="font-heading text-2xl font-semibold tracking-[0.12em]">FEATURED DESIGNS</h2>
      <p class="text-xs tracking-[0.1em] text-text-light mt-2 uppercase">Latest Additions</p>
      <div class="grid grid-cols-2 items-stretch gap-3 lg:grid-cols-4 lg:gap-6 mt-10">
        @foreach($relatedProducts as $rp)
          <x-product-card :product="$rp" />
        @endforeach
      </div>
    </div>
  </section>
  @endif
</div>

@endsection
