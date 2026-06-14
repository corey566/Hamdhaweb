<?php

namespace Database\Seeders;

use App\Models\CmsPage;
use Illuminate\Database\Seeder;

class CmsPageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'slug' => 'our-story',
                'title' => 'Our Story',
                'content' => <<<'HTML'
<p class="text-lg font-medium text-text-dark mb-6">Where Modesty Meets Elegance</p>
<p>Hamdha Clothing is a Sri Lanka based modest fashion brand, launched in 2022 with a vision to blend tradition, culture, and modern elegance. We design premium abayas that reflect confidence, comfort, and timeless style for every woman.</p>
<p>We specialize in custom-made abayas tailored to each customer’s preference, from fabric selection to final detailing. Every garment is crafted with care and attention to quality, offering a personal and meaningful experience with every order.</p>
<h2 class="text-xl font-semibold text-text-dark mt-10 mb-4">Our Mission</h2>
<p>Our mission is to become the leading brand for Muslim fashion by offering beautifully crafted garments at a competitive price and creating a space where customers can gain our insight into the modest fashion space. With this in mind, we aim to provide an enjoyable shopping experience every time through our personal approach to customer service.</p>
<p>Your opinion is very important to us. We appreciate your feedback and will use it to evaluate changes and make improvements to our site.</p>
HTML,
                'sort_order' => 1,
            ],
            [
                'slug' => 'size-guide',
                'title' => 'Size Guide',
                'content' => <<<'HTML'
<p class="text-lg font-medium text-text-dark mb-4">Find Your Perfect Fit with Confidence</p>
<p>The Common Size Chart includes lengths such as 52", 54", 56", 58", and 60", while the UK Size Chart includes UK 8, UK 10, UK 12, UK 14, and UK 16. All other measurements including bust, shoulder, sleeve, and hip remain standard, ensuring consistency in fit across all sizes.</p>
<p>For the best fit, you may choose a slightly longer size if you prefer wearing heels, or a shorter size for a more relaxed, casual look. Additionally, most abayas can be easily tailored as they are finished with a simple hem for easy adjustments.</p>
<p>Every Hamdha piece is made to order. Use the size guide on each product page or contact us on WhatsApp for personalized measurements.</p>
HTML,
                'sort_order' => 2,
            ],
            [
                'slug' => 'delivery',
                'title' => 'Delivery and Shipping',
                'content' => <<<'HTML'
<p class="text-lg font-medium text-text-dark mb-4">Fast, Reliable &amp; Made for You</p>
<p>We ensure every Hamdha order is carefully packed and delivered with attention to quality and timing, so you receive your abaya in perfect condition.</p>
<h2 class="text-lg font-semibold text-text-dark mt-8 mb-3">Delivery Within Sri Lanka</h2>
<p>For local orders within Sri Lanka, delivery typically takes 4 to 5 working days after order confirmation. We work with reliable local delivery partners to ensure safe and timely arrival.</p>
<h2 class="text-lg font-semibold text-text-dark mt-8 mb-3">International Shipping</h2>
<p>For international orders, delivery time depends on the destination country and shipping logistics. We coordinate with trusted shipping partners to ensure your order reaches you as smoothly as possible.</p>
<h2 class="text-lg font-semibold text-text-dark mt-8 mb-3">Tracking &amp; Support</h2>
<p>Once your order is shipped, tracking details will be shared so you can monitor your delivery status.</p>
<p>Local custom orders are typically produced within 7–14 days before dispatch. Contact us on WhatsApp for international inquiries.</p>
HTML,
                'sort_order' => 3,
            ],
            [
                'slug' => 'faqs',
                'title' => 'FAQs',
                'content' => <<<'HTML'
<p class="text-lg font-medium text-text-dark mb-4">Everything You Need to Know</p>
<p>Find answers to the most commonly asked questions about our abayas, customization process, and delivery services. We aim to keep everything clear, simple, and transparent so you can shop with confidence.</p>
<div class="space-y-8 mt-8">
<p><strong>1. How can I place an order?</strong><br>
You can place your order easily through WhatsApp. Simply select a design from our collection or send us a reference design you like. Our team will guide you through the next steps, including style selection and measurement details.</p>
<p><strong>2. Do you offer custom abaya designs?</strong><br>
Yes, we specialize in custom abayas. You can share any design from anywhere, and we will review it and adjust it according to your style preference, ensuring it matches your desired look and comfort.</p>
<p><strong>3. How does the ordering process work?</strong><br>
Once the design and measurements are confirmed, an advance payment is required to start production. After confirmation, we begin the stitching process with careful attention to detail and quality.</p>
<p><strong>4. How long does production take?</strong><br>
The stitching process usually takes around 1 to 1.5 weeks, depending on the design and customization details. We ensure each piece is completed with proper finishing and quality control.</p>
<p><strong>5. How long does delivery take?</strong><br>
After production, your order is handed over to our logistics partner. Delivery within Sri Lanka takes approximately 4 to 5 working days, while international delivery time depends on the shipping destination and courier service.</p>
<p><strong>6. Do you offer Cash on Delivery (COD)?</strong><br>
Yes, Cash on Delivery is available within Sri Lanka. A small advance is required to confirm the order, and the remaining balance can be paid to the delivery person upon receiving your parcel.</p>
<p><strong>7. Can I change my design after placing the order?</strong><br>
Design changes are possible only before the production stage begins. Once stitching starts, modifications are limited, so we always confirm all details clearly before proceeding with the order.</p>
</div>
HTML,
                'sort_order' => 4,
            ],
        ];

        foreach ($pages as $page) {
            CmsPage::updateOrCreate(['slug' => $page['slug']], $page);
        }
    }
}
