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
        return [Actions\DeleteAction::make()];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['cover_image'] = $this->record->cover_image_path;
        $data['product_images'] = $this->record->images->pluck('image_path')->toArray();

        return $data;
    }

    protected function afterSave(): void
    {
        $product = $this->record->fresh();
        $imageService = app(ImageService::class);

        $categoryIds = $product->categories()->pluck('categories.id')->toArray();
        if (! empty($categoryIds)) {
            $product->categories()->updateExistingPivot($categoryIds[0], ['is_primary' => true]);
            foreach (array_slice($categoryIds, 1) as $catId) {
                $product->categories()->updateExistingPivot($catId, ['is_primary' => false]);
            }
        }

        $cover = $this->form->getState()['cover_image'] ?? null;
        if ($cover && $cover !== $product->cover_image_path) {
            $imageService->deleteImage($product->cover_image_path);
            $imageService->deleteImage($product->cover_thumbnail_path);
            $result = $imageService->processProductImage($cover);
            $product->update([
                'cover_image_path' => $result['image_path'],
                'cover_thumbnail_path' => $result['thumbnail_path'],
            ]);
        }

        $state = $this->form->getState()['product_images'] ?? [];
        $existingImageIds = [];
        $newSortOrder = 0;

        foreach ($state as $file) {
            if (is_string($file) && ! empty($file)) {
                $image = ProductImage::where('product_id', $product->id)
                    ->where(function ($q) use ($file) {
                        $q->where('image_path', $file)->orWhere('thumbnail_path', $file);
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

        $toDelete = ! empty($existingImageIds)
            ? ProductImage::where('product_id', $product->id)->whereNotIn('id', $existingImageIds)->get()
            : $product->images;

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
