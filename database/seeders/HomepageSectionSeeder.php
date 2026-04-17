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
                'title' => "CUSTOME DESIGN\nABAYAS\nMADE FOR YOU",
                'subtitle' => 'Custom Made Modest Fashion',
                'content' => 'Every piece is crafted to your exact preferences — from rich fabrics and colors to intricate handwork and embroidery.',
                'cta_text' => 'Order via Whatsapp',
                'cta_url' => '#whatsapp',
                'sort_order' => 1,
            ]
        );

        HomepageSection::updateOrCreate(
            ['section_key' => 'customization_steps'],
            [
                'title' => 'HAVE YOUR OWN DESIGN?',
                'subtitle' => 'Share your idea through a Pinterest board, an Instagram photo, or a sketch. We\'ll bring it to life with premium quality.',
                'extra_data' => json_encode([
                    ['number' => '01', 'title' => 'Fully Personalized', 'description' => 'Choose any pattern, fabric, or color to make it yours.'],
                    ['number' => '02', 'title' => 'Connect Via WhatsApp', 'description' => 'Talk to us directly for a smooth experience.'],
                    ['number' => '03', 'title' => 'Receive Your Custom Piece', 'description' => 'Delivered to your doorstep, crafted just for you.'],
                ]),
                'sort_order' => 2,
            ]
        );

        HomepageSection::updateOrCreate(
            ['section_key' => 'mission'],
            [
                'title' => 'Our Mission',
                'content' => 'Committed to quality and tradition, Hamdha Clothing brings you handcrafted abayas that celebrate modesty with contemporary elegance. Featuring original handwork designs, each piece is carefully made just for you.',
                'cta_text' => 'CONTACT US',
                'cta_url' => '#whatsapp',
                'sort_order' => 3,
            ]
        );
    }
}
