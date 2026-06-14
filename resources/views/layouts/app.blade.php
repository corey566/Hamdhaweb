<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Hamdha Clothing — Custom Design Abayas')</title>
    <meta name="description" content="@yield('meta_description', 'Premium custom abayas crafted with care. Every piece is made to order, tailored to your unique preferences.')">

    @yield('json_ld')

    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script>
        window.hamdhaConfig = {
            gbpRate: {{ (float) ($siteSettings['currency_gbp_rate'] ?? 0.0021) }},
            currencyEnabled: @json($siteSettings['currency_enabled'] ?? true),
            currencyAutoDetect: @json($siteSettings['currency_auto_detect'] ?? true),
            currencyDefault: @json($siteSettings['currency_default'] ?? 'LKR'),
            wishlistEnabled: @json($siteSettings['wishlist_enabled'] ?? true),
        };
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans text-text-dark bg-white antialiased" x-data="{ drawerOpen: false, searchOpen: false }">

    <x-top-header :settings="$siteSettings" />

    <x-navbar :categories="$navCategories" />

    <x-mobile-drawer :categories="$navCategories" />

    @if($siteSettings['wishlist_enabled'] ?? true)
        <x-wishlist-drawer />
    @endif

    <div x-show="searchOpen" x-transition x-cloak
         x-effect="searchOpen && $nextTick(() => document.getElementById('mobile-search')?.focus())"
         class="fixed inset-x-0 top-0 bg-white shadow-lg z-40 p-4">
        <div class="flex items-center gap-3 max-w-7xl mx-auto">
            <div class="flex-1 min-w-0">
                <x-search-form
                    :value="request('q')"
                    input-id="mobile-search"
                    placeholder="Search products..."
                    :show-submit-button="false"
                />
            </div>
            <button @click="searchOpen = false" type="button" class="text-text-dark shrink-0" aria-label="Close search">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    </div>

    <main>
        @yield('content')
    </main>

    <x-footer :settings="$siteSettings" />

    @livewireScripts
</body>
</html>
