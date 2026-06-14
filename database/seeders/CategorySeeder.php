<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $tree = [
            [
                'name' => 'Abayas',
                'slug' => 'abayas',
                'sort_order' => 1,
                'children' => [
                    ['name' => 'Plain', 'slug' => 'plain-abaya'],
                    ['name' => 'Embroidery', 'slug' => 'embroidery-abaya'],
                    ['name' => 'Beads', 'slug' => 'beads-abaya'],
                    ['name' => 'Cutwork', 'slug' => 'cutwork-abaya'],
                    ['name' => 'Stonework', 'slug' => 'stonework-abaya'],
                    ['name' => 'Haj', 'slug' => 'haj-abaya'],
                    ['name' => 'Wedding', 'slug' => 'wedding-abaya'],
                ],
            ],
            [
                'name' => 'Hijab',
                'slug' => 'hijab',
                'sort_order' => 2,
                'children' => [
                    ['name' => 'Cotton', 'slug' => 'cotton-hijab'],
                    ['name' => 'Georgette', 'slug' => 'georgette-hijab'],
                    ['name' => 'Printed', 'slug' => 'printed-hijab'],
                    ['name' => 'Viscose', 'slug' => 'viscose-hijab'],
                    ['name' => 'Bubble', 'slug' => 'bubble-hijab'],
                ],
            ],
        ];

        foreach ($tree as $parentData) {
            $children = $parentData['children'];
            unset($parentData['children']);

            $parent = Category::updateOrCreate(
                ['slug' => $parentData['slug']],
                [
                    'name' => $parentData['name'],
                    'sort_order' => $parentData['sort_order'],
                    'is_visible' => true,
                    'parent_id' => null,
                ]
            );

            foreach ($children as $i => $childData) {
                Category::updateOrCreate(
                    ['slug' => $childData['slug']],
                    [
                        'name' => $childData['name'],
                        'parent_id' => $parent->id,
                        'sort_order' => $i + 1,
                        'is_visible' => true,
                    ]
                );
            }
        }

        $legacyHajj = Category::where('slug', 'hajj-abaya')->first();
        if ($legacyHajj) {
            $haj = Category::where('slug', 'haj-abaya')->first();
            if ($haj && $haj->id !== $legacyHajj->id) {
                foreach ($legacyHajj->products as $product) {
                    $product->categories()->detach($legacyHajj->id);
                    $product->categories()->syncWithoutDetaching([$haj->id => ['is_primary' => false]]);
                }
                $legacyHajj->delete();
            } else {
                $legacyHajj->update(['name' => 'Haj', 'slug' => 'haj-abaya']);
            }
        }
    }
}
