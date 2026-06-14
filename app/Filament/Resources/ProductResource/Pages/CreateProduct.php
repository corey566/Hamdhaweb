<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Models\ProductImage;
use App\Services\ImageService;
use Filament\Resources\Pages\CreateRecord;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected function afterSave(): void
    {
        $product = $this->record;
        $imageService = app(ImageService::class);

        $categoryIds = $product->categories()->pluck('categories.id')->toArray();
        if (! empty($categoryIds)) {
            $product->categories()->updateExistingPivot($categoryIds[0], ['is_primary' => true]);
            foreach (array_slice($categoryIds, 1) as $catId) {
                $product->categories()->updateExistingPivot($catId, ['is_primary' => false]);
            }
        }

        $cover = $this->form->getState()['cover_image'] ?? null;
        if ($cover) {
            $result = $imageService->processProductImage($cover);
            $product->update([
                'cover_image_path' => $result['image_path'],
                'cover_thumbnail_path' => $result['thumbnail_path'],
            ]);
        }

        $uploadedFiles = $this->form->getState()['product_images'] ?? [];
        if (! empty($uploadedFiles)) {
            $sortOrder = 0;
            foreach ($uploadedFiles as $file) {
                if ($file instanceof TemporaryUploadedFile || (is_string($file) && ! empty($file))) {
                    $result = $imageService->processProductImage($file);
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $result['image_path'],
                        'thumbnail_path' => $result['thumbnail_path'],
                        'sort_order' => $sortOrder++,
                    ]);
                }
            }
        }

        cache()->forget('featured_products');
        cache()->forget('new_arrivals');
    }
}
