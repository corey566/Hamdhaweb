<?php

namespace Database\Seeders;

use App\Models\HomepageSection;
use Illuminate\Database\Seeder;

class HomepageSectionSeeder extends Seeder
{
    public function run(): void
    {
        HomepageSection::updateOrCreate(
            ['section_key' => 'hero'],
            [
                'title' => "CUSTOM ABAYAS\nMADE FOR YOU",
                'subtitle' => 'Custom-made modest fashion',
                'content' => 'Every piece is crafted to your exact preferences — from fabric and color to intricate handwork and embroidery.',
                'cta_text' => 'Order via Whatsapp',
                'cta_url' => '#whatsapp',
                'image_path' => null,
                'extra_data' => ['slides' => []],
                'sort_order' => 1,
                'is_visible' => true,
            ]
        );

        HomepageSection::clearSectionCache('hero');

        HomepageSection::updateOrCreate(
            ['section_key' => 'customization_steps'],
            [
                'title' => 'Have Your Own Design?',
                'subtitle' => 'Share your inspiration — a Pinterest board, an Instagram photo, or a sketch. We\'ll bring it to life with premium fabrics and expert craftsmanship.',
                'extra_data' => [
                    ['number' => '01', 'title' => 'Fully Personalized', 'description' => 'Choose from our portfolio or share your reference images.'],
                    ['number' => '02', 'title' => 'Connect via WhatsApp', 'description' => 'Discuss fabric, color, measurements and timeline.'],
                    ['number' => '03', 'title' => 'Receive Your Custom Piece', 'description' => 'Delivered within 7–14 days for local orders.'],
                ],
                'sort_order' => 2,
            ]
        );

        HomepageSection::updateOrCreate(
            ['section_key' => 'mission'],
            [
                'title' => 'Our Mission',
                'content' => '<p>Our mission is to become the leading brand for Muslim fashion by offering beautifully crafted garments at a competitive price and creating a space where customers can gain our insight into the modest fashion space. With this in mind, we aim to provide an enjoyable shopping experience every time through our personal approach to customer service.</p><p>Your opinion is very important to us. We appreciate your feedback and will use it to evaluate changes and make improvements to our site.</p>',
                'cta_text' => 'Contact us',
                'cta_url' => '#whatsapp',
                'image_path' => null,
                'sort_order' => 3,
            ]
        );

        HomepageSection::clearSectionCache('mission');
    }
}
