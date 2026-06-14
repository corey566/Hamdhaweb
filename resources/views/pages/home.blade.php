@extends('layouts.app')

@section('title', 'Hamdha Clothing — Custom Design Abayas Made For You')
@section('meta_description', 'Premium custom abayas crafted with care. Every piece is made to order, tailored to your unique preferences.')

@section('content')

@if($hero && $hero->is_visible)
@php
    $slides = collect($hero->extra_data['slides'] ?? [])->filter();
    if ($slides->isEmpty() && $hero->image_path) {
        $slides = collect([$hero->image_path]);
    }
@endphp
<section class="bg-offwhite overflow-hidden">
    <div class="lg:hidden hero-mobile-bleed relative aspect-[4/5] overflow-hidden" x-data="{
        current: 0,
        slides: {{ $slides->count() }},
        init() {
            if (this.slides > 1) {
                setInterval(() => { this.current = (this.current + 1) % this.slides; }, 5000);
            }
        }
    }">
        @foreach($slides as $i => $slide)
            <img src="{{ asset('storage/' . $slide) }}"
                 alt="{{ $hero->title }}"
                 class="absolute inset-0 w-full h-full object-cover transition-opacity duration-700"
                 :class="current === {{ $i }} ? 'opacity-100' : 'opacity-0'">
        @endforeach
    </div>

    <div class="hero-desktop-bleed lg:grid lg:grid-cols-2 lg:min-h-[600px]">
        <div class="flex flex-col justify-center px-4 pt-5 pb-8 lg:px-10 lg:py-16 xl:px-14 2xl:px-20 lg:pr-10 xl:pr-14">
                <p class="text-xs tracking-[0.2em] uppercase text-text-light mb-1 lg:mb-1.5">{{ $hero->subtitle }}</p>
                <h1 class="font-heading text-2xl lg:text-4xl xl:text-[2.75rem] font-bold text-text-dark leading-[1.1] tracking-wide whitespace-pre-line hero-title-animate lg:animate-none">
                    {{ $hero->title }}
                </h1>
                <p class="text-sm text-text-medium mt-2 lg:mt-3 leading-relaxed max-w-md lg:max-w-lg">{{ $hero->content }}</p>
                <div class="flex flex-wrap gap-3 mt-4 lg:mt-6">
                    <a href="{{ route('products.index') }}" class="btn-outline">Explore Design</a>
                    <a href="https://wa.me/{{ $siteSettings['whatsapp_number'] ?? '94777626013' }}" target="_blank" rel="noopener" class="btn-primary">
                        {{ $hero->cta_text ?? 'Order via Whatsapp' }}
                    </a>
                </div>
            </div>

        <div class="hidden lg:block relative min-h-[600px] overflow-hidden bg-gray-200" x-data="{
            current: 0,
            slides: {{ $slides->count() }},
            init() {
                if (this.slides > 1) {
                    setInterval(() => { this.current = (this.current + 1) % this.slides; }, 5000);
                }
            }
        }">
            @forelse($slides as $i => $slide)
                <img src="{{ asset('storage/' . $slide) }}"
                     alt="{{ $hero->title }}"
                     class="absolute inset-0 w-full h-full object-cover transition-opacity duration-700"
                     :class="current === {{ $i }} ? 'opacity-100' : 'opacity-0'">
            @empty
                <div class="absolute inset-0 flex items-center justify-center text-sm text-text-light px-6 text-center">
                    Add hero images in Admin → Homepage → Hero Section
                </div>
            @endforelse
        </div>
    </div>

    @php
        $marqueeCategories = collect();
        foreach ($categories as $parent) {
            foreach ($parent->children as $child) {
                $suffix = str_contains(strtolower($parent->slug), 'hijab') ? ' Hijab' : ' Abaya';
                $marqueeCategories->push([
                    'model' => $child,
                    'label' => strtoupper($child->name.$suffix),
                ]);
            }
        }
    @endphp
    @if($marqueeCategories->isNotEmpty())
    <nav class="category-strip-fullbleed bg-primary text-white overflow-hidden" aria-label="Shop by category">
        <div class="category-strip-marquee">
            @foreach([0, 1] as $copy)
                @foreach($marqueeCategories as $item)
                    @if($siteSettings['marquee_links_enabled'] ?? true)
                        <a href="{{ route('category.show', $item['model']->slug) }}"
                           class="category-strip-link"
                           @if($copy === 1) aria-hidden="true" tabindex="-1" @endif>
                            {{ $item['label'] }}
                        </a>
                    @else
                        <span class="category-strip-link" @if($copy === 1) aria-hidden="true" @endif>{{ $item['label'] }}</span>
                    @endif
                @endforeach
            @endforeach
        </div>
    </nav>
    @endif
</section>
@endif

<section class="section-spacing">
    <div class="container-page text-center">
        <h2 class="section-title">Abayas Collections</h2>
        <p class="section-subtitle">Featuring stunning garments for every occasion.</p>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-3 lg:gap-4 mt-10">
            @foreach($categories as $cat)
                @if($cat->cover_image)
                <a href="{{ route('category.show', $cat->slug) }}" class="group relative overflow-hidden">
                    <div class="aspect-[4/5] overflow-hidden">
                        <img src="{{ asset('storage/' . $cat->cover_image) }}"
                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                             alt="{{ $cat->name }}">
                    </div>
                    <div class="absolute bottom-0 inset-x-0 bg-gradient-to-t from-black/50 to-transparent py-4 px-3">
                        <span class="text-white text-sm font-semibold tracking-widest uppercase">{{ $cat->name }} Abaya</span>
                    </div>
                </a>
                @endif
            @endforeach
        </div>
    </div>
</section>

@php
    $weddingCategory = $categories->first(fn ($c) => str_contains(strtolower($c->name), 'wedding'));
@endphp
@if($weddingCategory && $weddingCategory->cover_image)
<section class="section-spacing bg-offwhite">
    <div class="container-page">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
            <a href="{{ route('category.show', $weddingCategory->slug) }}" class="block aspect-[4/5] overflow-hidden">
                <img src="{{ asset('storage/' . $weddingCategory->cover_image) }}" alt="{{ $weddingCategory->name }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
            </a>
            <div class="text-center lg:text-left">
                <h2 class="section-title">{{ $weddingCategory->name }} Abaya</h2>
                <p class="text-sm text-text-medium mt-4 leading-relaxed max-w-md">Discover elegant wedding abayas with exquisite embroidery and premium finishes.</p>
                <a href="{{ route('category.show', $weddingCategory->slug) }}" class="inline-block btn-outline mt-6">Shop {{ $weddingCategory->name }}</a>
            </div>
        </div>
    </div>
</section>
@endif

<section class="section-spacing">
    <div class="container-page text-center">
        <h2 class="section-title">Featured designs</h2>
        <p class="section-subtitle">Latest Additions</p>
        <div class="grid grid-cols-2 items-stretch gap-3 lg:grid-cols-4 lg:gap-6 mt-10">
            @foreach($featuredProducts as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>
        <a href="{{ route('products.index') }}" class="inline-block btn-outline mt-10 text-xs tracking-widest uppercase">view all designs</a>
    </div>
</section>

@if($steps && $steps->is_visible)
<section class="section-spacing bg-offwhite">
    <div class="container-page">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <div>
                <p class="text-xs tracking-[0.2em] uppercase text-text-light mb-2">Fully Personalized</p>
                <h2 class="section-title">{{ $steps->title }}</h2>
                <p class="text-sm text-text-medium mt-3 leading-relaxed">{{ $steps->subtitle }}</p>
                <div class="mt-8 space-y-6">
                    @foreach($steps->extra_data ?? [] as $step)
                        <div class="flex gap-4">
                            <span class="text-3xl font-bold text-primary leading-none">{{ str_pad($step['number'] ?? '', 2, '0', STR_PAD_LEFT) }}</span>
                            <div>
                                <h3 class="text-sm font-semibold text-text-dark">{{ $step['title'] }}</h3>
                                <p class="text-xs text-text-medium mt-1">{{ $step['description'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div>
                @if($mission && $mission->image_path)
                    <img src="{{ asset('storage/' . $mission->image_path) }}" class="w-full rounded-lg object-cover" alt="">
                @endif
                @if($mission)
                    <div class="text-sm text-text-medium mt-6 leading-relaxed space-y-4">
                        {!! $mission->content !!}
                    </div>
                    <a href="https://wa.me/{{ $siteSettings['whatsapp_number'] ?? '94777626013' }}" target="_blank" rel="noopener" class="inline-block btn-primary mt-6">
                        {{ $mission->cta_text ?? 'Contact us' }}
                    </a>
                @endif
            </div>
        </div>
    </div>
</section>
@endif

@endsection
