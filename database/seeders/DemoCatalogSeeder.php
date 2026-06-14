<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Fabric;
use App\Models\PriceBucket;
use App\Models\Product;
use App\Models\SizeChart;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DemoCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedFabrics();
        $this->seedPriceBuckets();
        $abayaChart = $this->seedSizeChart('Abaya Size Guide');
        $hijabChart = $this->seedSizeChart('Hijab Size Guide');

        $samples = [
            'plain-abaya' => [
                ['name' => 'Classic Plain Abaya', 'price' => 12500],
                ['name' => 'Minimal Linen Abaya', 'price' => 14200],
            ],
            'embroidery-abaya' => [
                ['name' => 'Floral Embroidery Abaya', 'price' => 18500],
                ['name' => 'Pearl Detail Abaya', 'price' => 21000],
            ],
            'beads-abaya' => [
                ['name' => 'Crystal Beaded Abaya', 'price' => 22500],
            ],
            'wedding-abaya' => [
                ['name' => 'Luxury Starlit Bloom Embellished Open Abaya', 'price' => 12750, 'model_number' => 'HM-0008'],
                ['name' => 'Premium Textured Dewdrop Bloom Open Abaya', 'price' => 10250, 'model_number' => 'HM-0009'],
            ],
            'cotton-hijab' => [
                ['name' => 'Soft Cotton Hijab', 'price' => 3200],
            ],
            'georgette-hijab' => [
                ['name' => 'Flow Georgette Hijab', 'price' => 3800],
            ],
        ];

        $fabricIds = Fabric::pluck('id', 'slug');
        $variant = 0;

        foreach ($samples as $categorySlug => $products) {
            $category = Category::where('slug', $categorySlug)->first();
            if (! $category) {
                continue;
            }

            $isAbaya = str_contains($categorySlug, 'abaya');
            $chart = $isAbaya ? $abayaChart : $hijabChart;
            $fabricSlug = $isAbaya ? 'nida' : 'chiffon';

            foreach ($products as $data) {
                $slug = Str::slug($data['name']);
                $product = Product::firstOrNew(['slug' => $slug]);
                $product->fill([
                    'name' => $data['name'],
                    'price' => $data['price'],
                    'description' => '<p>Premium custom-made piece. Contact us on WhatsApp to confirm fabric, colour, and measurements.</p>',
                    'fabric_id' => $fabricIds[$fabricSlug] ?? $fabricIds->first(),
                    'colors' => 'Black, Navy, Brown',
                    'is_visible' => true,
                    'is_featured' => $variant % 3 === 0,
                    'cover_image_path' => null,
                    'cover_thumbnail_path' => null,
                ]);
                if (! $product->exists && ! empty($data['model_number'])) {
                    $product->model_number = $data['model_number'];
                }
                $product->save();

                $product->images()->delete();

                $product->categories()->sync([
                    $category->id => ['is_primary' => true],
                    $category->parent_id => ['is_primary' => false],
                ]);

                if ($chart) {
                    $product->sizeCharts()->sync([$chart->id => ['sort_order' => 0]]);
                }

                $variant++;
            }
        }

        cache()->forget('featured_products');
        cache()->forget('new_arrivals');
        cache()->forget('nav_categories');
        cache()->forget('all_categories_filter');
    }

    protected function seedFabrics(): void
    {
        $fabrics = [
            ['name' => 'Nida', 'slug' => 'nida', 'sort_order' => 1],
            ['name' => 'Crepe', 'slug' => 'crepe', 'sort_order' => 2],
            ['name' => 'Chiffon', 'slug' => 'chiffon', 'sort_order' => 3],
            ['name' => 'Jersey', 'slug' => 'jersey', 'sort_order' => 4],
        ];

        foreach ($fabrics as $fabric) {
            Fabric::updateOrCreate(['slug' => $fabric['slug']], $fabric);
        }
    }

    protected function seedPriceBuckets(): void
    {
        $buckets = [
            ['label' => 'Under Rs. 10,000', 'min_price' => 0, 'max_price' => 9999, 'sort_order' => 1],
            ['label' => 'Rs. 10,000 – 20,000', 'min_price' => 10000, 'max_price' => 20000, 'sort_order' => 2],
            ['label' => 'Above Rs. 20,000', 'min_price' => 20001, 'max_price' => null, 'sort_order' => 3],
        ];

        foreach ($buckets as $bucket) {
            PriceBucket::updateOrCreate(
                ['label' => $bucket['label']],
                $bucket
            );
        }
    }

    protected function seedSizeChart(string $name): ?SizeChart
    {
        return SizeChart::updateOrCreate(
            ['name' => $name],
            ['image_path' => null, 'sort_order' => 0]
        );
    }
}
