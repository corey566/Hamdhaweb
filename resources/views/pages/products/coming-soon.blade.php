@extends('layouts.app')

@section('title', $pageTitle . ' — Hamdha Clothing')

@section('content')
<nav class="max-w-7xl mx-auto px-4 lg:px-16 pt-6 pb-2">
    <ol class="flex items-center flex-wrap text-xs text-text-light space-x-2">
        @foreach($breadcrumbs as $crumb)
            @if(!is_null($crumb['url']))
                <li><a href="{{ $crumb['url'] }}" class="hover:text-text-dark uppercase tracking-wide">{{ $crumb['label'] }}</a></li>
                <li class="text-text-light">/</li>
            @else
                <li class="text-text-dark font-medium uppercase tracking-wide">{{ $crumb['label'] }}</li>
            @endif
        @endforeach
    </ol>
</nav>

<section class="max-w-7xl mx-auto px-4 lg:px-16 py-24 lg:py-32 text-center">
    <p class="text-xs tracking-[0.3em] uppercase text-text-light mb-4">Hamdha Clothing</p>
    <h1 class="font-heading text-3xl lg:text-4xl font-semibold text-text-dark capitalize">{{ $pageTitle }}</h1>
    <p class="text-lg text-text-medium mt-6 max-w-md mx-auto">Coming Soon</p>
    <p class="text-sm text-text-light mt-3 max-w-lg mx-auto">We are preparing beautiful pieces for this collection. Check back soon or contact us on WhatsApp for custom orders.</p>
    <a href="https://wa.me/{{ $siteSettings['whatsapp_number'] ?? '94777626013' }}" target="_blank" rel="noopener" class="inline-block btn-primary mt-10">Contact via WhatsApp</a>
    <a href="{{ route('home') }}" class="block mt-4 text-sm text-primary hover:underline">← Back to home</a>
</section>
@endsection
