const WISHLIST_KEY = 'hamdha_wishlist';
const CURRENCY_KEY = 'hamdha_currency';

function loadWishlist() {
    try {
        return JSON.parse(localStorage.getItem(WISHLIST_KEY) || '[]');
    } catch {
        return [];
    }
}

function saveWishlist(items) {
    localStorage.setItem(WISHLIST_KEY, JSON.stringify(items));
    window.dispatchEvent(new CustomEvent('hamdha-wishlist-updated', { detail: items }));
}

function formatLkr(amount) {
    return `Rs. ${Number(amount).toLocaleString('en-LK', { maximumFractionDigits: 0 })}`;
}

function formatGbp(amountLkr, rate) {
    const gbp = Number(amountLkr) * rate;
    return `£${gbp.toLocaleString('en-GB', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
}

function detectCurrency(config) {
    if (!config.currencyAutoDetect || !config.currencyEnabled) {
        return config.currencyDefault === 'GBP' ? 'GBP' : 'LKR';
    }

    const stored = localStorage.getItem(CURRENCY_KEY);
    if (stored === 'LKR' || stored === 'GBP') {
        return stored;
    }

    const locale = navigator.language || '';
    if (locale.includes('GB') || locale === 'en-GB') {
        return 'GBP';
    }

    return config.currencyDefault === 'GBP' ? 'GBP' : 'LKR';
}

async function detectCurrencyFromGeo(store, config) {
    if (!config.currencyAutoDetect || !config.currencyEnabled) {
        return;
    }
    if (localStorage.getItem(CURRENCY_KEY)) {
        return;
    }

    try {
        const res = await fetch('https://ipapi.co/json/', { signal: AbortSignal.timeout(3000) });
        const data = await res.json();
        if (data.country_code === 'GB') {
            store.setCurrency('GBP');
        } else if (data.country_code === 'LK') {
            store.setCurrency('LKR');
        }
    } catch {
        // keep locale-based default
    }
}

function registerHamdhaSearch() {
    Alpine.data('hamdhaSearch', (config = {}) => ({
        query: config.initial || '',
        suggestions: [],
        open: false,
        loading: false,
        activeIndex: -1,
        suggestUrl: config.suggestUrl || '/search/suggest',
        searchUrl: config.searchUrl || '/search',

        get hasQuery() {
            return this.query.trim().length >= 2;
        },

        get showDropdown() {
            return this.open && this.hasQuery && (this.loading || this.suggestions.length > 0);
        },

        get allResultsUrl() {
            return `${this.searchUrl}?q=${encodeURIComponent(this.query.trim())}`;
        },

        async fetchSuggestions() {
            const term = this.query.trim();
            this.activeIndex = -1;

            if (term.length < 2) {
                this.suggestions = [];
                this.open = false;
                this.loading = false;
                return;
            }

            this.loading = true;
            this.open = true;

            try {
                const response = await fetch(
                    `${this.suggestUrl}?q=${encodeURIComponent(term)}`,
                    { headers: { Accept: 'application/json' } },
                );

                if (!response.ok) {
                    throw new Error('Search suggest failed');
                }

                const data = await response.json();
                this.suggestions = data.results || [];
            } catch {
                this.suggestions = [];
            } finally {
                this.loading = false;
            }
        },

        close() {
            this.open = false;
            this.activeIndex = -1;
        },

        formatPrice(amountLkr) {
            const store = window.Alpine?.store('hamdha');
            if (store?.formatPrice) {
                return store.formatPrice(amountLkr);
            }
            return formatLkr(amountLkr);
        },

        onKeydown(event) {
            if (!this.showDropdown && event.key !== 'Escape') {
                return;
            }

            if (event.key === 'Escape') {
                this.close();
                return;
            }

            if (event.key === 'ArrowDown') {
                event.preventDefault();
                const max = this.suggestions.length - 1;
                this.activeIndex = this.activeIndex < max ? this.activeIndex + 1 : 0;
                return;
            }

            if (event.key === 'ArrowUp') {
                event.preventDefault();
                const max = this.suggestions.length - 1;
                this.activeIndex = this.activeIndex > 0 ? this.activeIndex - 1 : max;
                return;
            }

            if (event.key === 'Enter' && this.activeIndex >= 0 && this.suggestions[this.activeIndex]) {
                event.preventDefault();
                window.location.href = this.suggestions[this.activeIndex].url;
            }
        },

        isActive(index) {
            return this.activeIndex === index;
        },
    }));
}

export function initHamdhaStore() {
    document.addEventListener('alpine:init', () => {
        registerHamdhaSearch();
        const config = window.hamdhaConfig || {};
        const gbpRate = parseFloat(config.gbpRate) || 0.0021;
        const currencyEnabled = config.currencyEnabled !== false;
        const wishlistEnabled = config.wishlistEnabled !== false;

        Alpine.store('hamdha', {
            currencyEnabled,
            wishlistEnabled,
            currency: detectCurrency(config),
            gbpRate,
            wishlist: wishlistEnabled ? loadWishlist() : [],
            wishlistOpen: false,

            setCurrency(code) {
                if (!this.currencyEnabled) {
                    this.currency = 'LKR';
                    this.refreshPrices();
                    return;
                }
                this.currency = code;
                localStorage.setItem(CURRENCY_KEY, code);
                this.refreshPrices();
            },

            toggleCurrency() {
                if (!this.currencyEnabled) {
                    return;
                }
                this.setCurrency(this.currency === 'LKR' ? 'GBP' : 'LKR');
            },

            formatPrice(amountLkr) {
                if (this.currencyEnabled && this.currency === 'GBP') {
                    return formatGbp(amountLkr, this.gbpRate);
                }
                return formatLkr(amountLkr);
            },

            refreshPrices() {
                document.querySelectorAll('[data-price-lkr]').forEach((el) => {
                    const lkr = parseFloat(el.dataset.priceLkr);
                    if (!Number.isNaN(lkr)) {
                        el.textContent = this.formatPrice(lkr);
                    }
                });
            },

            toggleWishlist(slug) {
                if (!this.wishlistEnabled) {
                    return;
                }
                const idx = this.wishlist.indexOf(slug);
                if (idx === -1) {
                    this.wishlist.push(slug);
                } else {
                    this.wishlist.splice(idx, 1);
                }
                saveWishlist([...this.wishlist]);
            },

            isInWishlist(slug) {
                return this.wishlistEnabled && this.wishlist.includes(slug);
            },

            wishlistCount() {
                return this.wishlistEnabled ? this.wishlist.length : 0;
            },

            wishlistWhatsAppUrl() {
                const slugs = this.wishlist.join(',');
                return `/wishlist/whatsapp?slugs=${encodeURIComponent(slugs)}`;
            },
        });

        Alpine.nextTick(() => {
            const store = Alpine.store('hamdha');
            store.refreshPrices();
            detectCurrencyFromGeo(store, config);
        });
    });

    document.addEventListener('livewire:navigated', () => {
        if (window.Alpine?.store('hamdha')) {
            Alpine.store('hamdha').refreshPrices();
        }
    });
}
