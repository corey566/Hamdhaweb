<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Hamdha Clothing — Custom Design Abayas')</title>
    <meta name="description" content="@yield('meta_description', 'Premium custom abayas crafted with care. Every piece is made to order, tailored to your unique preferences.')">

    @yield('json_ld')

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans text-text-dark bg-white antialiased" x-data="{ drawerOpen: false, searchOpen: false }">

    <x-announcement-bar :items="$siteSettings['announcement_bar_items'] ?? []" :phone="$siteSettings['contact_phone'] ?? ''" :socialInstagram="$siteSettings['social_instagram'] ?? ''" :socialFacebook="$siteSettings['social_facebook'] ?? ''" :socialTiktok="$siteSettings['social_tiktok'] ?? ''" />

    <x-navbar :categories="$navCategories" />

    <x-mobile-drawer :categories="$navCategories" />

    <div x-show="searchOpen" x-transition x-cloak
         class="fixed inset-x-0 top-0 bg-white shadow-lg z-40 p-4">
        <form action="{{ route('search') }}" method="GET" class="flex items-center gap-3">
            <input type="text" name="q" placeholder="Search products..."
                   class="flex-1 border border-gray-300 rounded-full py-2.5 px-5 text-sm focus:outline-none focus:border-primary"
                   autofocus>
            <button @click="searchOpen = false" type="button" class="text-text-dark">
                <x-heroicon-o-x-mark class="w-6 h-6" />
            </button>
        </form>
    </div>

    <main>
        @yield('content')
    </main>

    <x-footer :settings="$siteSettings" />

    @livewireScripts
</body>
</html>