@props(['amount'])

<span {{ $attributes->merge(['class' => 'price-display']) }} data-price-lkr="{{ $amount }}">
    Rs. {{ number_format((float) $amount, 0) }}
</span>
