<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'whatsapp_number' => '94777626013',
            'whatsapp_message_template' => "Hello Hamdha,\n\nI'm interested in this product:\n• Model: {model}\n• Name: {name}\n• Price: Rs. {price}\n• Fabric: {fabric}\n• Link: {url}\n\nPlease confirm availability.",
            'model_number_prefix' => 'HM',
            'model_number_next' => '1',
            'new_arrivals_count' => '8',
            'social_instagram' => 'https://instagram.com/hamdhaclothing',
            'social_facebook' => 'https://facebook.com/hamdhaclothing',
            'social_tiktok' => 'https://tiktok.com/@hamdhaclothing',
            'contact_phone' => '077-762-6013',
            'announcement_bar_items' => json_encode([
                'Island wide shipping',
            ]),
            'footer_tagline' => 'Premium custom abayas crafted with care. Every piece is made to order, tailored to your unique preferences.',
            'footer_info_links' => json_encode([
                ['label' => 'Our Story', 'url' => '/page/our-story'],
                ['label' => 'Size Guide', 'url' => '/page/size-guide'],
            ]),
            'footer_customer_care_links' => json_encode([
                ['label' => 'Delivery', 'url' => '/page/delivery'],
                ['label' => 'FAQs', 'url' => '/page/faqs'],
                ['label' => 'Contact us', 'url' => 'https://wa.me/94777626013'],
            ]),
            'site_title' => 'Hamdha Clothing',
            'shipping_text' => 'Island-wide shipping',
            'orianwave_url' => 'https://orianwave.com',
            'storefront_nav_mode' => 'hierarchical',
            'storefront_currency_auto_detect' => '1',
            'currency_gbp_rate' => '0.0021',
            'plp_tagline' => 'Premium custom abayas crafted with care. Every piece is made to order, tailored to your unique preferences.',
            'storefront_currency_enabled' => '1',
            'storefront_currency_default' => 'LKR',
            'storefront_wishlist_enabled' => '1',
            'storefront_marquee_links_enabled' => '1',
            'storefront_featured_filter_enabled' => '1',
            'storefront_collection_layout' => 'columns',
            'max_upload_size_mb' => '10',
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
