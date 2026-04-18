@extends('layouts.app')

@section('title', $product->name . ' — Hamdha Clothing')

@section('content')

<div class="max-w-7xl mx-auto px-4 lg:px-16 py-6">
  <nav class="mb-6">
    <ol class="flex items-center text-xs text-text-light uppercase tracking-wider">
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
      images: {{ $product->images->map(fn($i) => asset('storage/' . $i->image_path))->toJson() }}
    }">
      <div class="grid grid-cols-[70px_1fr] gap-3 lg:gap-4">
        <div class="flex lg:flex-col gap-2 overflow-x-auto lg:overflow-visible">
          @foreach($product->images as $i => $image)
          <button
            @click="activeImage = {{ $i }}"
            class="flex-shrink-0 w-[70px] aspect-[4/5] overflow-hidden border-2 transition"
            :class="activeImage === {{ $i }} ? 'border-primary' : 'border-transparent'"
          >
            <img src="{{ asset('storage/' . $image->thumbnail_path) }}" class="w-full h-full object-cover" alt="">
          </button>
          @endforeach
        </div>
        <div class="aspect-[4/5] overflow-hidden bg-gray-100">
          <img :src="images[activeImage]" class="w-full h-full object-cover" alt="{{ $product->name }}">
        </div>
      </div>
    </div>

    <div class="lg:col-span-2 lg:pl-4" x-data="{ showSizeGuide: false }">
      <div class="flex flex-wrap gap-2 mb-4">
        @foreach($product->categories as $cat)
          <span class="inline-block bg-primary text-white text-[11px] font-medium tracking-wide px-3 py-1.5 rounded-sm">
            {{ strtoupper($cat->name) }}
          </span>
        @endforeach
        <span class="inline-block border border-primary text-primary text-[11px] font-medium tracking-wide px-3 py-1.5 rounded-sm">
          {{ $product->model_number }}
        </span>
      </div>

      <h1 class="text-lg lg:text-xl font-semibold text-text-dark leading-tight">
        {{ $product->name }}
      </h1>

      <div class="mt-3">
        @if($product->has_discount)
          <span class="text-xl font-bold text-sale">Rs. {{ number_format($product->discount_price) }}</span>
          <span class="text-sm text-text-light line-through ml-2">Rs. {{ number_format($product->price) }}</span>
        @else
          <span class="text-xl font-bold text-text-dark">Rs. {{ number_format($product->price) }}</span>
        @endif
      </div>

      <hr class="my-4 border-border">

      @if($product->description)
      <p class="text-sm text-text-medium leading-relaxed mb-6">{!! $product->description !!}</p>
      @endif

      <div class="space-y-2">
        <div class="flex">
          <span class="text-xs font-semibold tracking-widest uppercase text-text-dark w-24">FABRIC</span>
          <span class="text-sm text-text-medium">{{ $product->fabric->name }}</span>
        </div>
        <div class="flex">
          <span class="text-xs font-semibold tracking-widest uppercase text-text-dark w-24">COLORS</span>
          <span class="text-sm text-text-medium">{{ $product->colors ?? 'N/A' }}</span>
        </div>
        <div class="flex">
          <span class="text-xs font-semibold tracking-widest uppercase text-text-dark w-24">SIZE</span>
          @if($product->sizeCharts->count())
            <button @click="showSizeGuide = true" class="text-sm text-primary underline font-medium hover:text-primary-hover">
              SIZE GUIDE
            </button>
          @else
            <span class="text-sm text-text-medium">Standard</span>
          @endif
        </div>
      </div>

      <a href="{{ $whatsappUrl }}" target="_blank"
        class="flex items-center justify-center gap-3 w-full bg-primary text-white font-semibold text-sm tracking-wide py-4 rounded-sm mt-8 hover:bg-primary-hover transition">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12c0 1.33.26 2.61.76 3.78l-1.04 4.73c-.14.63.36 1.22 1.02 1.22h.17c.62 0 1.17-.35 1.45-.88l1.39-2.63c.16-.3.48-.5.84-.5h2.17c.56 0 .84.68.46 1.1l-2.66 2.97c-.44.5-.68 1.15-.68 1.83 0 3.87 3.13 7 7 7s7-3.13 7-7c0-3.12-2.05-5.79-4.88-6.77l-2.5-.9c-.95-.34-1.94-.52-2.95-.52zm4.5 10.5c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5z"/></svg>
        PLACE ORDER VIA WHATSAPP
      </a>

      @if($product->sizeCharts->count())
      <div x-show="showSizeGuide" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4"
           @click.self="showSizeGuide = false">
        <div class="fixed inset-0 bg-black/50"></div>
        <div class="relative bg-white rounded-lg max-w-lg w-full max-h-[90vh] overflow-y-auto p-6 z-10">
          <button @click="showSizeGuide = false" class="absolute top-3 right-3 text-text-light hover:text-text-dark">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
          </button>
          <h3 class="text-lg font-semibold text-text-dark mb-4">Size Guide</h3>
          <div class="space-y-4">
            @foreach($product->sizeCharts as $chart)
              <div>
                <p class="text-sm font-medium text-text-dark mb-2">{{ $chart->name }}</p>
                <img src="{{ asset('storage/' . $chart->image_path) }}" class="w-full rounded" alt="{{ $chart->name }}">
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
    <div class="max-w-7xl mx-auto px-4 lg:px-16 text-center">
      <h2 class="font-heading text-2xl font-semibold tracking-[0.12em]">FEATURED DESIGNS</h2>
      <p class="text-xs tracking-[0.1em] text-text-light mt-2 uppercase">Latest Additions</p>
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-6 mt-10">
        @foreach($relatedProducts as $rp)
          <x-product-card :product="$rp" />
        @endforeach
      </div>
    </div>
  </section>
  @endif
</div>

@endsection