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
                'Insta/Fb/Tiktok',
                'Island wide shipping',
            ]),
            'footer_tagline' => 'Premium custom abayas crafted with care. Every piece is made to order, tailored to your unique preferences.',
            'footer_info_links' => json_encode([
                ['label' => 'Our Story', 'url' => '#'],
                ['label' => 'Size Guide', 'url' => '#'],
            ]),
            'footer_customer_care_links' => json_encode([
                ['label' => 'Delivery', 'url' => '#'],
                ['label' => 'FAQs', 'url' => '#'],
            ]),
            'max_upload_size_mb' => '10',
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
