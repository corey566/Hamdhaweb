<?php

namespace App\Console\Commands;

use App\Models\HomepageSection;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\SizeChart;
use App\Services\ImageService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ClearDemoMediaCommand extends Command
{
    protected $signature = 'media:clear-demo
                            {--keep-db : Only delete files; do not clear database image paths}';

    protected $description = 'Remove demo/sample images from storage and clear related database paths';

    public function handle(ImageService $imageService): int
    {
        $disk = Storage::disk('public');
        $dirs = ['products', 'products/thumbs', 'homepage', 'size-charts', 'categories', 'tmp', 'cms'];

        $deletedFiles = 0;
        foreach ($dirs as $dir) {
            if ($disk->exists($dir)) {
                foreach ($disk->allFiles($dir) as $file) {
                    $disk->delete($file);
                    $deletedFiles++;
                }
            }
        }

        $placeholder = public_path('images/placeholder.webp');
        if (File::exists($placeholder)) {
            File::delete($placeholder);
            $this->line('Removed public/images/placeholder.webp');
        }

        if (! $this->option('keep-db')) {
            ProductImage::query()->each(function (ProductImage $image) use ($imageService) {
                $imageService->deleteImage($image->image_path);
                $imageService->deleteImage($image->thumbnail_path);
            });
            ProductImage::query()->delete();

            Product::query()->each(function (Product $product) use ($imageService) {
                $imageService->deleteImage($product->cover_image_path);
                $imageService->deleteImage($product->cover_thumbnail_path);
                $product->update([
                    'cover_image_path' => null,
                    'cover_thumbnail_path' => null,
                ]);
            });

            $hero = HomepageSection::where('section_key', 'hero')->first();
            if ($hero) {
                $slides = $hero->extra_data['slides'] ?? [];
                foreach ($slides as $slide) {
                    $imageService->deleteImage($slide);
                }
                $imageService->deleteImage($hero->image_path);
                $hero->update([
                    'image_path' => null,
                    'extra_data' => array_merge($hero->extra_data ?? [], ['slides' => []]),
                ]);
                HomepageSection::clearSectionCache('hero');
            }

            $mission = HomepageSection::where('section_key', 'mission')->first();
            if ($mission?->image_path) {
                $imageService->deleteImage($mission->image_path);
                $mission->update(['image_path' => null]);
                HomepageSection::clearSectionCache('mission');
            }

            SizeChart::query()->each(function (SizeChart $chart) use ($imageService) {
                if ($chart->image_path) {
                    $imageService->deleteImage($chart->image_path);
                    $chart->update(['image_path' => null]);
                }
            });

            cache()->forget('featured_products');
            cache()->forget('new_arrivals');
            cache()->forget('homepage_section.hero');
            cache()->forget('homepage_section.mission');
        }

        $this->info("Deleted {$deletedFiles} file(s) from storage/app/public.");
        $this->info($this->option('keep-db')
            ? 'Database image paths were kept.'
            : 'Product, homepage, and size chart image paths were cleared in the database.');

        return self::SUCCESS;
    }
}
