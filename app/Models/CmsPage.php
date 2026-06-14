<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CmsPage extends Model
{
    protected $fillable = [
        'slug', 'title', 'banner_image', 'content', 'sections', 'is_visible', 'sort_order',
    ];

    protected $casts = [
        'sections' => 'array',
        'is_visible' => 'boolean',
    ];

    public function scopeVisible($query)
    {
        return $query->where('is_visible', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    protected static function booted(): void
    {
        static::creating(function (CmsPage $page) {
            if (empty($page->slug)) {
                $page->slug = Str::slug($page->title);
            }
        });

        static::saved(fn () => cache()->forget('cms_page_nav'));
        static::deleted(fn () => cache()->forget('cms_page_nav'));
    }
}
