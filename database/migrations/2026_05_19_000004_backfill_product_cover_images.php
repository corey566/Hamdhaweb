<?php

use App\Models\Product;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Product::query()
            ->whereNull('cover_image_path')
            ->with(['images' => fn ($q) => $q->orderBy('sort_order')])
            ->chunkById(50, function ($products) {
                foreach ($products as $product) {
                    $first = $product->images->first();
                    if (! $first) {
                        continue;
                    }

                    $product->update([
                        'cover_image_path' => $first->image_path,
                        'cover_thumbnail_path' => $first->thumbnail_path ?? $first->image_path,
                    ]);
                }
            });
    }

    public function down(): void
    {
        // Non-destructive backfill — no rollback.
    }
};
