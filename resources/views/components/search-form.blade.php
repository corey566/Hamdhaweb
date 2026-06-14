@props([
    'value' => '',
    'showSubmitButton' => true,
    'inputId' => 'search-input',
    'placeholder' => 'Search...',
])

@php
    $inputPadding = $showSubmitButton ? 'pl-5 pr-12' : 'pl-5 pr-5';
@endphp

<div
    x-data="hamdhaSearch({
        initial: @js($value),
        suggestUrl: @js(route('search.suggest')),
        searchUrl: @js(route('search')),
    })"
    @click.away="close()"
    class="relative w-full"
>
    <form action="{{ route('search') }}" method="GET" role="search" @submit="close()">
        <label for="{{ $inputId }}" class="sr-only">Search products</label>
        <input
            id="{{ $inputId }}"
            type="search"
            name="q"
            x-model="query"
            autocomplete="off"
            autocapitalize="off"
            spellcheck="false"
            placeholder="{{ $placeholder }}"
            class="w-full border border-gray-300 rounded-full py-2.5 text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/20 transition {{ $inputPadding }}"
            @input.debounce.300ms="fetchSuggestions()"
            @focus="hasQuery && suggestions.length && (open = true)"
            @keydown="onKeydown($event)"
            role="combobox"
            aria-autocomplete="list"
            :aria-expanded="showDropdown"
            aria-controls="{{ $inputId }}-suggestions"
        >
        @if($showSubmitButton)
        <button type="submit" class="absolute right-1 top-1/2 -translate-y-1/2 bg-primary text-white rounded-full w-9 h-9 flex items-center justify-center hover:bg-primary-hover transition" aria-label="Search">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </button>
        @endif
    </form>

    <div
        x-show="showDropdown"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 translate-y-1"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-1"
        x-cloak
        id="{{ $inputId }}-suggestions"
        class="search-suggest-dropdown absolute left-0 right-0 top-[calc(100%+0.5rem)] z-50"
        role="listbox"
    >
        <div class="bg-white border border-border rounded-lg shadow-lg overflow-hidden">
            <template x-if="loading">
                <p class="px-4 py-3 text-sm text-text-light">Searching…</p>
            </template>

            <template x-if="!loading && suggestions.length === 0">
                <p class="px-4 py-3 text-sm text-text-light">No matching products</p>
            </template>

            <ul x-show="!loading && suggestions.length > 0" class="max-h-80 overflow-y-auto divide-y divide-gray-100">
                <template x-for="(item, index) in suggestions" :key="item.slug">
                    <li>
                        <a
                            :href="item.url"
                            class="search-suggest-item flex items-center gap-3 px-3 py-2.5 hover:bg-offwhite transition"
                            :class="{ 'bg-offwhite': isActive(index) }"
                            role="option"
                            :aria-selected="isActive(index)"
                            @mouseenter="activeIndex = index"
                        >
                            <span class="shrink-0 w-10 h-12 rounded-sm overflow-hidden bg-gray-100">
                                <img
                                    x-show="item.thumbnail"
                                    :src="item.thumbnail"
                                    :alt="item.name"
                                    class="w-full h-full object-cover"
                                    loading="lazy"
                                >
                                <span x-show="!item.thumbnail" class="flex w-full h-full items-center justify-center text-[10px] text-text-light">—</span>
                            </span>
                            <span class="min-w-0 flex-1 text-left">
                                <span class="block text-sm font-medium text-text-dark truncate" x-text="item.name"></span>
                                <span class="block text-xs text-text-light mt-0.5" x-text="item.model_number"></span>
                            </span>
                            <span class="shrink-0 text-sm font-semibold text-text-dark" x-text="formatPrice(item.price_lkr)"></span>
                        </a>
                    </li>
                </template>
            </ul>

            <a
                x-show="!loading && suggestions.length > 0"
                :href="allResultsUrl"
                class="block px-4 py-3 text-xs font-semibold tracking-widest uppercase text-center text-primary border-t border-gray-100 hover:bg-offwhite transition"
            >
                View all results
            </a>
        </div>
    </div>
</div>
