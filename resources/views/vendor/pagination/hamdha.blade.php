@if ($paginator->hasPages())
<nav role="navigation" aria-label="Pagination" class="inline-flex items-center rounded-sm overflow-hidden border border-primary bg-primary shadow-sm">
    {{-- Previous --}}
    @if ($paginator->onFirstPage())
        <span class="px-3 py-2 text-white/40 cursor-not-allowed" aria-disabled="true">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="px-3 py-2 text-white/90 hover:bg-primary-hover transition" wire:navigate aria-label="Previous page">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
    @endif

    @foreach ($elements as $element)
        @if (is_string($element))
            <span class="px-3 py-2 text-sm text-white/50 border-l border-white/10">{{ $element }}</span>
        @endif

        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span class="px-3.5 py-2 text-sm font-semibold text-primary bg-white border-l border-white/10" aria-current="page">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="px-3.5 py-2 text-sm text-white/90 hover:bg-primary-hover border-l border-white/10 transition" wire:navigate>{{ $page }}</a>
                @endif
            @endforeach
        @endif
    @endforeach

    {{-- Next --}}
    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="px-3 py-2 text-white/90 hover:bg-primary-hover border-l border-white/10 transition" wire:navigate aria-label="Next page">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
    @else
        <span class="px-3 py-2 text-white/40 border-l border-white/10 cursor-not-allowed" aria-disabled="true">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </span>
    @endif
</nav>
@endif
