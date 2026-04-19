@extends('layouts.app')

@section('title', 'Hamdha Clothing — Custom Design Abayas Made For You')
@section('meta_description', 'Premium custom abayas crafted with care. Every piece is made to order, tailored to your unique preferences.')

@section('content')

@if($hero && $hero->is_visible)
<section class="bg-offwhite">
    <div class="max-w-7xl mx-auto px-4 lg:px-16">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-0">
            <div class="lg:hidden relative aspect-[4/5]">
                @if($hero->image_path)
                    <img src="{{ asset('storage/' . $hero->image_path) }}" class="w-full h-full object-cover" alt="">
                @endif
            </div>

            <div class="flex flex-col justify-center py-10 lg:py-20 lg:pr-12">
                <p class="text-xs tracking-[0.2em] uppercase text-text-light mb-3">{{ $hero->subtitle }}</p>
                <h1 class="font-heading text-2xl lg:text-4xl font-bold text-text-dark leading-tight tracking-wide whitespace-pre-line">
                    {{ $hero->title }}
                </h1>
                <p class="text-sm text-text-medium mt-4 leading-relaxed max-w-md">{{ $hero->content }}</p>
                <div class="flex flex-wrap gap-3 mt-6">
                    <a href="{{ route('products.index') }}" class="btn-outline">Explore Design</a>
                    <a href="https://wa.me/{{ $siteSettings['whatsapp_number'] ?? '94777626013' }}" target="_blank" class="btn-primary">
                        {{ $hero->cta_text ?? 'Order via Whatsapp' }}
                    </a>
                </div>
            </div>

            <div class="hidden lg:block relative h-[600px]">
                @if($hero->image_path)
                    <img src="{{ asset('storage/' . $hero->image_path) }}" class="w-full h-full object-cover" alt="">
                @endif
            </div>
        </div>

        <div class="marquee-container py-6 border-t border-gray-200">
            <div class="marquee-track">
                @foreach($categories as $cat)
                    <a href="{{ route('category.show', $cat->slug) }}" class="tag-pill hover:bg-primary-hover transition whitespace-nowrap">
                        {{ $cat->name }}
                    </a>
                @endforeach
                @foreach($categories as $cat)
                    <a href="{{ route('category.show', $cat->slug) }}" class="tag-pill hover:bg-primary-hover transition whitespace-nowrap">
                        {{ $cat->name }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif

<section class="section-spacing">
    <div class="container-page text-center">
        <h2 class="section-title">ABAYAS COLLECTIONS</h2>
        <p class="section-subtitle">Featuring Stunning Garments For Every Occasion</p>
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
                        <span class="text-white text-sm font-semibold tracking-widest uppercase">{{ $cat->name }}</span>
                    </div>
                </a>
                @endif
            @endforeach
        </div>
    </div>
</section>

<section class="section-spacing">
    <div class="container-page text-center">
        <h2 class="section-title">FEATURED DESIGNS</h2>
        <p class="section-subtitle">Latest Additions</p>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-6 mt-10">
            @foreach($featuredProducts as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>
        <a href="{{ route('products.index') }}" class="inline-block btn-outline mt-10">VIEW ALL DESIGNS</a>
    </div>
</section>

@if($steps && $steps->is_visible)
<section class="section-spacing bg-offwhite">
    <div class="container-page">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <div>
                <h2 class="section-title">{{ $steps->title }}</h2>
                <p class="text-sm text-text-medium mt-3 leading-relaxed">{{ $steps->subtitle }}</p>
                <div class="mt-8 space-y-6">
                    @foreach($steps->extra_data ?? [] as $step)
                        <div class="flex gap-4">
                            <span class="text-3xl font-bold text-primary leading-none">{{ $step['number'] }}</span>
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
                    <p class="text-sm text-text-medium mt-6 leading-relaxed">{{ $mission->content }}</p>
                    <a href="https://wa.me/{{ $siteSettings['whatsapp_number'] ?? '94777626013' }}" target="_blank" class="inline-block btn-primary mt-6">
                        {{ $mission->cta_text ?? 'CONTACT US' }}
                    </a>
                @endif
            </div>
        </div>
    </div>
</section>
@endif

@endsection