<?php

namespace App\Models;

use App\Services\ImageService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'model_number', 'name', 'slug', 'price', 'discount_price',
        'description', 'cover_image_path', 'cover_thumbnail_path',
        'fabric_id', 'colors', 'is_visible', 'is_featured',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'is_visible' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_product')
            ->withPivot('is_primary');
    }

    public function primaryCategory(): ?Category
    {
        return $this->categories()->wherePivot('is_primary', true)->first();
    }

    public function tags(): BelongsToMany
    {
        return $this->categories()->wherePivot('is_primary', false);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function primaryImage(): ?ProductImage
    {
        return $this->images()->first();
    }

    public function coverThumbnailUrl(): ?string
    {
        if ($this->cover_thumbnail_path) {
            return asset('storage/'.$this->cover_thumbnail_path);
        }

        $first = $this->images->first();

        return $first ? asset('storage/'.($first->thumbnail_path ?? $first->image_path)) : null;
    }

    public function coverImageUrl(): ?string
    {
        if ($this->cover_image_path) {
            return asset('storage/'.$this->cover_image_path);
        }

        $first = $this->images->first();

        return $first ? asset('storage/'.$first->image_path) : null;
    }

    public function hoverImageUrl(): ?string
    {
        $second = $this->images->skip(1)->first();

        return $second ? asset('storage/'.($second->thumbnail_path ?? $second->image_path)) : null;
    }

    /**
     * @return array<int, array{full: string, thumb: string}>
     */
    public function galleryImages(): array
    {
        $items = [];
        $seen = [];

        if ($this->cover_image_path) {
            $full = asset('storage/'.$this->cover_image_path);
            $thumb = asset('storage/'.($this->cover_thumbnail_path ?? $this->cover_image_path));
            $items[] = ['full' => $full, 'thumb' => $thumb];
            $seen[$full] = true;
        }

        foreach ($this->images as $image) {
            $full = asset('storage/'.$image->image_path);
            if (isset($seen[$full])) {
                continue;
            }
            $items[] = [
                'full' => $full,
                'thumb' => asset('storage/'.($image->thumbnail_path ?? $image->image_path)),
            ];
            $seen[$full] = true;
        }

        return $items;
    }

    public function fabricLabel(): string
    {
        return $this->fabric?->name ?? 'Fabric unavailable';
    }

    public function sizeCharts(): BelongsToMany
    {
        return $this->belongsToMany(SizeChart::class, 'product_size_chart')
            ->withPivot('sort_order')
            ->orderByPivot('sort_order');
    }

    public function fabric(): BelongsTo
    {
        return $this->belongsTo(Fabric::class);
    }

    public function scopeVisible($query)
    {
        return $query->where('is_visible', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeWithPrimaryCategory($query)
    {
        return $query->with(['categories' => function ($q) {
            $q->wherePivot('is_primary', true);
        }]);
    }

    public function scopeSearch($query, string $term)
    {
        $like = '%'.$term.'%';

        return $query->where(function ($q) use ($like) {
            $q->where('name', 'LIKE', $like)
                ->orWhere('model_number', 'LIKE', $like)
                ->orWhereHas('fabric', fn ($fabric) => $fabric->where('name', 'LIKE', $like));
        });
    }

    public function getDisplayPriceAttribute(): string
    {
        return number_format($this->discount_price ?? $this->price);
    }

    public function getHasDiscountAttribute(): bool
    {
        return $this->discount_price !== null && $this->discount_price < $this->price;
    }

    protected static function booted(): void
    {
        static::creating(function (Product $product) {
            if (empty($product->model_number)) {
                $prefix = Setting::get('model_number_prefix', 'HM');

                $next = cache()->lock('model_number_generation', 10)->block(5, function () {
                    $next = (int) Setting::get('model_number_next', 1);
                    Setting::set('model_number_next', (string) ($next + 1));

                    return $next;
                });

                $product->model_number = $prefix.'-'.str_pad($next, 4, '0', STR_PAD_LEFT);
            }

            if (empty($product->slug)) {
                $baseSlug = Str::slug($product->name);
                $slug = $baseSlug;
                $i = 1;
                while (Product::where('slug', $slug)->exists()) {
                    $slug = $baseSlug.'-'.$i++;
                }
                $product->slug = $slug;
            }
        });

        static::deleting(function (Product $product) {
            $imageService = app(ImageService::class);
            $imageService->deleteImage($product->cover_image_path);
            $imageService->deleteImage($product->cover_thumbnail_path);
            foreach ($product->images as $image) {
                $imageService->deleteImage($image->image_path);
                $imageService->deleteImage($image->thumbnail_path);
            }
            $product->categories()->detach();
            $product->sizeCharts()->detach();
        });
    }
}
