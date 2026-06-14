@extends('layouts.app')

@section('title', $page->title . ' — Hamdha Clothing')

@section('content')
<nav class="max-w-7xl mx-auto px-4 lg:px-16 pt-6 pb-2">
    <ol class="flex items-center text-xs text-text-light space-x-2">
        @foreach($breadcrumbs as $crumb)
            @if($crumb['url'])
                <li><a href="{{ $crumb['url'] }}" class="hover:text-text-dark">{{ $crumb['label'] }}</a></li>
                <li>/</li>
            @else
                <li class="text-text-dark font-medium">{{ $crumb['label'] }}</li>
            @endif
        @endforeach
    </ol>
</nav>

@if($page->banner_image)
<section class="w-full aspect-[21/9] lg:aspect-[3/1] overflow-hidden bg-offwhite">
    <img src="{{ asset('storage/' . $page->banner_image) }}" alt="{{ $page->title }}" class="w-full h-full object-cover">
</section>
@endif

<article class="max-w-3xl mx-auto px-4 lg:px-16 py-12 lg:py-16">
    <h1 class="font-heading text-3xl font-semibold tracking-wide text-text-dark mb-8">{{ $page->title }}</h1>
    <div class="prose prose-sm max-w-none text-text-medium leading-relaxed">
        {!! $page->content !!}
    </div>

    @if(!empty($page->sections))
        @foreach($page->sections as $section)
        <section class="mt-12 pt-8 border-t border-border">
            @if(!empty($section['heading']))
                <h2 class="text-xl font-semibold text-text-dark mb-4">{{ $section['heading'] }}</h2>
            @endif
            @if(!empty($section['image']))
                <img src="{{ asset('storage/' . $section['image']) }}" alt="" class="w-full rounded-lg mb-6 aspect-[4/5] object-cover max-h-[480px]">
            @endif
            @if(!empty($section['body']))
                <div class="prose prose-sm max-w-none text-text-medium">{!! $section['body'] !!}</div>
            @endif
        </section>
        @endforeach
    @endif
</article>
@endsection
