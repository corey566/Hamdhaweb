<div
    x-show="$store.hamdha.wishlistOpen"
    x-cloak
    class="fixed inset-0 z-[60]"
    @keydown.escape.window="$store.hamdha.wishlistOpen = false"
>
    <div class="fixed inset-0 bg-black/50" @click="$store.hamdha.wishlistOpen = false"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"></div>

    <div class="fixed inset-y-0 right-0 w-full max-w-sm bg-white shadow-xl flex flex-col"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="translate-x-full">
        <div class="flex items-center justify-between p-4 border-b border-gray-200">
            <h2 class="text-sm font-semibold tracking-widest uppercase">Wish List</h2>
            <button type="button" @click="$store.hamdha.wishlistOpen = false" class="text-text-dark" aria-label="Close wish list">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto p-4">
            <template x-if="$store.hamdha.wishlistCount() === 0">
                <p class="text-sm text-text-medium text-center py-12">Save abayas you love with the heart icon, then send your list on WhatsApp.</p>
            </template>
            <template x-if="$store.hamdha.wishlistCount() > 0">
                <ul class="space-y-3 mb-6">
                    <template x-for="slug in $store.hamdha.wishlist" :key="slug">
                        <li class="flex items-start justify-between gap-2 border-b border-gray-100 pb-2">
                            <a :href="`/product/${slug}`" class="text-sm text-primary hover:underline capitalize flex-1" x-text="slug.replace(/-/g, ' ')"></a>
                            <button type="button" @click="$store.hamdha.toggleWishlist(slug)" class="text-text-light hover:text-sale text-lg leading-none" aria-label="Remove">&times;</button>
                        </li>
                    </template>
                </ul>
            </template>
        </div>

        <div class="p-4 border-t border-gray-200" x-show="$store.hamdha.wishlistCount() > 0">
            <a
                :href="`/wishlist/whatsapp?slugs=${$store.hamdha.wishlist.join(',')}`"
                target="_blank"
                rel="noopener"
                class="flex items-center justify-center gap-2 w-full bg-primary text-white text-sm font-semibold tracking-wide py-3 rounded-sm hover:bg-primary-hover transition"
            >
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.861-2.03-.961-.273-.096-.474-.048-.675.149-.2.2-.849.861-1.041 1.041-.189.2-.381.2-.678.069-.297-.149-1.255-.461-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                Send wish list via WhatsApp
            </a>
        </div>
    </div>
</div>
