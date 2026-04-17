<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class ImageService
{
    public function processProductImage(UploadedFile $file): array
    {
        $uuid = Str::uuid();

        $image = Image::read($file);
        $image->scaleDown(width: 1080, height: 1350);
        $fullPath = "products/{$uuid}.webp";
        $image->toWebp(quality: 85)
            ->save(storage_path("app/public/{$fullPath}"));

        $thumb = Image::read($file);
        $thumb->scaleDown(width: 540, height: 675);
        $thumbPath = "products/thumbs/{$uuid}.webp";
        $thumb->toWebp(quality: 80)
            ->save(storage_path("app/public/{$thumbPath}"));

        return [
            'image_path' => $fullPath,
            'thumbnail_path' => $thumbPath,
        ];
    }

    public function processCategoryImage(UploadedFile $file): string
    {
        $uuid = Str::uuid();
        $image = Image::read($file);
        $image->scaleDown(width: 1080, height: 1350);
        $path = "categories/{$uuid}.webp";
        $image->toWebp(quality: 85)
            ->save(storage_path("app/public/{$path}"));

        return $path;
    }

    public function processSizeChartImage(UploadedFile $file): string
    {
        $uuid = Str::uuid();
        $image = Image::read($file);
        $image->scaleDown(width: 1080, height: 1350);
        $path = "size-charts/{$uuid}.webp";
        $image->toWebp(quality: 85)
            ->save(storage_path("app/public/{$path}"));

        return $path;
    }

    public function processHomepageImage(UploadedFile $file): string
    {
        $uuid = Str::uuid();
        $image = Image::read($file);
        $image->scaleDown(width: 1920);
        $path = "homepage/{$uuid}.webp";
        $image->toWebp(quality: 85)
            ->save(storage_path("app/public/{$path}"));

        return $path;
    }

    public function deleteImage(string $path): void
    {
        $fullPath = storage_path("app/public/{$path}");
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
    }
}
