@props(['categories'])

@php
    $navMode = $siteSettings['nav_mode'] ?? 'hierarchical';
    $flatItems = collect();
    if ($navMode === 'flat') {
        foreach ($categories as $parent) {
            if ($parent->children->count()) {
                foreach ($parent->children as $child) {
                    $flatItems->push($child);
                }
            } else {
                $flatItems->push($parent);
            }
        }
    }
@endphp

<header class="bg-white border-b border-border sticky top-0 z-30">
    {{-- Mobile --}}
    <div class="lg:hidden max-w-7xl mx-auto px-4 py-3 flex items-center justify-between gap-3">
        <button @click="drawerOpen = true" class="text-text-dark shrink-0 p-1 hover:text-primary transition" aria-label="Open menu">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>

        <a href="{{ route('home') }}" class="flex items-center justify-center flex-1 min-w-0">
            <img src="{{ asset('images/logo-dark.png') }}" alt="Hamdha" class="h-5 w-auto">
        </a>

        <div class="flex items-center gap-2 shrink-0">
            <button @click="searchOpen = true" class="text-text-dark p-1" aria-label="Search">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </button>
            @if($siteSettings['wishlist_enabled'] ?? true)
            <button type="button" @click="$store.hamdha.wishlistOpen = true" class="relative text-text-dark p-1 hover:text-primary transition" aria-label="Wish list">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                <span x-show="$store.hamdha.wishlistCount() > 0" x-text="$store.hamdha.wishlistCount()" class="absolute -top-0.5 -right-0.5 bg-primary text-white text-[9px] font-bold w-4 h-4 rounded-full flex items-center justify-center"></span>
            </button>
            @endif
        </div>
    </div>

    {{-- Desktop: balanced columns + shared center stack for search & nav --}}
    <div class="hidden lg:block max-w-7xl mx-auto px-4 lg:px-16">
        <div class="grid grid-cols-[1fr_auto_1fr] items-center gap-4 xl:gap-8 py-3">
            <a href="{{ route('home') }}" class="flex items-center justify-self-start shrink-0" aria-label="{{ $siteSettings['site_title'] ?? 'Hamdha Clothing' }}">
                <img src="{{ asset('images/logo-dark.png') }}" alt="{{ $siteSettings['site_title'] ?? 'Hamdha Clothing' }}" class="h-6 w-auto">
            </a>

            <div class="flex flex-col items-center justify-self-center w-full min-w-[min(100%,28rem)] max-w-3xl overflow-visible px-2">
                <div class="relative z-20 w-full max-w-md">
                    <x-search-form :value="request('q')" input-id="desktop-search" />
                </div>

                <nav class="w-full border-t border-gray-100 mt-3 pt-3">
                    <div class="flex flex-wrap items-center justify-center gap-x-6 gap-y-2 xl:gap-x-8">
                        <a href="{{ route('products.new-arrivals') }}" class="nav-link {{ request()->routeIs('products.new-arrivals') ? 'text-primary' : '' }}">New Arrivals</a>

                        @if($navMode === 'flat')
                            @foreach($flatItems as $item)
                                <a href="{{ route('category.show', $item->slug) }}" class="nav-link {{ request()->is('category/'.$item->slug) ? 'text-primary' : '' }}">{{ $item->name }}</a>
                            @endforeach
                        @else
                            @foreach($categories as $cat)
                                <div class="relative group">
                                    <a href="{{ route('category.show', $cat->slug) }}" class="nav-link inline-flex items-center gap-1 {{ request()->is('category/'.$cat->slug.'*') ? 'text-primary' : '' }}">
                                        {{ $cat->name }}
                                        @if($cat->children->count())
                                            <svg class="w-3 h-3 transition-transform duration-300 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                        @endif
                                    </a>
                                    @if($cat->children->count())
                                        <div class="absolute top-full left-1/2 -translate-x-1/2 pt-2 opacity-0 invisible translate-y-1 group-hover:opacity-100 group-hover:visible group-hover:translate-y-0 transition-all duration-300 z-20">
                                            <div class="bg-white shadow-lg rounded-md border border-border py-2 px-3 min-w-[200px]">
                                                <a href="{{ route('category.show', $cat->slug) }}" class="block py-2 px-2 text-xs font-semibold tracking-widest uppercase text-primary border-b border-gray-100 mb-1">All {{ $cat->name }}</a>
                                                @foreach($cat->children as $child)
                                                    <a href="{{ route('category.show', $child->slug) }}" class="block py-2 px-2 text-sm text-text-medium hover:text-primary transition">{{ $child->name }}</a>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        @endif

                        <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.index') ? 'text-primary' : '' }}">All Products</a>
                    </div>
                </nav>
            </div>

            <div class="flex items-center justify-self-end gap-3">
                @if($siteSettings['wishlist_enabled'] ?? true)
                <button type="button" @click="$store.hamdha.wishlistOpen = true" class="relative text-text-dark p-1.5 hover:text-primary transition" aria-label="Wish list">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    <span x-show="$store.hamdha.wishlistCount() > 0" x-text="$store.hamdha.wishlistCount()" class="absolute -top-0.5 -right-0.5 bg-primary text-white text-[9px] font-bold w-4 h-4 rounded-full flex items-center justify-center"></span>
                </button>
                @endif
            </div>
        </div>
    </div>
</header>
