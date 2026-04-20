<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Models\ProductImage;
use App\Services\ImageService;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $product = $this->record;

        $categoryIds = $product->categories()->pluck('categories.id')->toArray();
        if (! empty($categoryIds)) {
            $product->categories()->updateExistingPivot($categoryIds[0], ['is_primary' => true]);
            foreach (array_slice($categoryIds, 1) as $catId) {
                $product->categories()->updateExistingPivot($catId, ['is_primary' => false]);
            }
        }

        $imageService = app(ImageService::class);
        $state = $this->form->getState()['product_images'] ?? [];
        $existingImageIds = [];
        $newSortOrder = 0;

        foreach ($state as $file) {
            if (is_string($file) && ! empty($file)) {
                $image = ProductImage::where('product_id', $product->id)
                    ->where(function ($q) use ($file) {
                        $q->where('image_path', $file)
                            ->orWhere('thumbnail_path', $file);
                    })
                    ->first();

                if ($image) {
                    $image->update(['sort_order' => $newSortOrder++]);
                    $existingImageIds[] = $image->id;
                } else {
                    $result = $imageService->processProductImage($file);
                    $newImage = ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $result['image_path'],
                        'thumbnail_path' => $result['thumbnail_path'],
                        'sort_order' => $newSortOrder++,
                    ]);
                    $existingImageIds[] = $newImage->id;
                }
            }
        }

        if (! empty($existingImageIds)) {
            $toDelete = ProductImage::where('product_id', $product->id)
                ->whereNotIn('id', $existingImageIds)
                ->get();
        } else {
            $toDelete = $product->images;
        }

        foreach ($toDelete as $image) {
            $imageService->deleteImage($image->image_path);
            $imageService->deleteImage($image->thumbnail_path);
            $image->delete();
        }

        cache()->forget('featured_products');
        cache()->forget('new_arrivals');
    }

    protected function afterDelete(): void
    {
        cache()->forget('featured_products');
        cache()->forget('new_arrivals');
    }
}
