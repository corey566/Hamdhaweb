<?php

namespace App\Support;

use App\Models\Setting;

class StorefrontConfig
{
    public static function all(): array
    {
        return [
            'currency_enabled' => self::bool('storefront_currency_enabled', true),
            'currency_auto_detect' => self::bool('storefront_currency_auto_detect', true),
            'currency_default' => Setting::get('storefront_currency_default', 'LKR'),
            'currency_gbp_rate' => (float) Setting::get('currency_gbp_rate', '0.0021'),
            'wishlist_enabled' => self::bool('storefront_wishlist_enabled', true),
            'marquee_links_enabled' => self::bool('storefront_marquee_links_enabled', true),
            'featured_filter_enabled' => self::bool('storefront_featured_filter_enabled', true),
            'collection_layout' => Setting::get('storefront_collection_layout', 'tabs'),
            'nav_mode' => Setting::get('storefront_nav_mode', 'hierarchical'),
            'site_title' => Setting::get('site_title', 'Hamdha Clothing'),
            'shipping_text' => Setting::get('shipping_text', 'Island-wide shipping'),
            'orianwave_url' => Setting::get('orianwave_url', 'https://orianwave.com'),
        ];
    }

    public static function bool(string $key, bool $default = false): bool
    {
        $value = Setting::get($key, $default ? '1' : '0');

        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    public static function clearCache(): void
    {
        cache()->forget('site_settings_global');
    }
}
