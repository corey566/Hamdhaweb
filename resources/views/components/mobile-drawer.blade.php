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

<div x-show="drawerOpen" x-cloak class="fixed inset-0 z-50 lg:hidden">
    <div class="fixed inset-0 bg-black/50" @click="drawerOpen = false"
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
    </div>

    <div class="fixed inset-y-0 left-0 w-[300px] bg-white shadow-xl overflow-y-auto"
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full">

        <div class="flex justify-between items-center p-4 border-b border-gray-100">
            <img src="{{ asset('images/logo-dark.png') }}" alt="Hamdha" class="h-5 w-auto">
            <button type="button" @click="drawerOpen = false" class="text-text-dark" aria-label="Close menu">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        @if(($siteSettings['wishlist_enabled'] ?? true) || ($siteSettings['currency_enabled'] ?? true))
        <div class="px-4 py-3 flex items-center gap-3 border-b border-gray-100">
            @if($siteSettings['wishlist_enabled'] ?? true)
            <button type="button" @click="$store.hamdha.wishlistOpen = true; drawerOpen = false" class="text-xs font-medium tracking-widest uppercase text-text-dark">
                Wish List
            </button>
            @endif
            @if($siteSettings['currency_enabled'] ?? true)
            <button type="button" @click="$store.hamdha.toggleCurrency()" class="text-[11px] font-semibold tracking-widest border border-primary text-primary px-2 py-0.5 rounded-sm" x-text="$store.hamdha.currency"></button>
            @endif
        </div>
        @endif

        <nav class="px-6 pb-8 pt-2">
            <a href="{{ route('products.new-arrivals') }}"
               class="block py-3 text-sm font-medium tracking-widest uppercase text-text-dark border-b border-gray-100">
                New Arrivals
            </a>

            @if($navMode === 'flat')
                @foreach($flatItems as $item)
                    <a href="{{ route('category.show', $item->slug) }}"
                       class="block py-3 text-sm font-medium tracking-widest uppercase text-text-dark border-b border-gray-100">
                        {{ $item->name }}
                    </a>
                @endforeach
            @else
                @foreach($categories as $cat)
                    @if($cat->children->count())
                    <div x-data="{ open: false }" class="border-b border-gray-100">
                        <button type="button" @click="open = !open" class="w-full flex items-center justify-between py-3 text-sm font-medium tracking-widest uppercase text-text-dark">
                            <span>{{ $cat->name }}</span>
                            <svg class="w-4 h-4 transition-transform duration-300" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open" x-transition class="pb-2">
                            <a href="{{ route('category.show', $cat->slug) }}" class="block py-2 pl-2 text-xs font-semibold text-primary tracking-wide">All {{ $cat->name }}</a>
                            @foreach($cat->children as $child)
                                <a href="{{ route('category.show', $child->slug) }}"
                                   class="block py-2 pl-4 text-xs tracking-wide text-text-medium hover:text-primary transition">
                                    {{ $child->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                    @else
                    <a href="{{ route('category.show', $cat->slug) }}"
                       class="block py-3 text-sm font-medium tracking-widest uppercase text-text-dark border-b border-gray-100">
                        {{ $cat->name }}
                    </a>
                    @endif
                @endforeach
            @endif

            <a href="{{ route('products.index') }}"
               class="block py-3 text-sm font-medium tracking-widest uppercase text-text-dark border-b border-gray-100">
                All Products
            </a>
        </nav>
    </div>
</div>
