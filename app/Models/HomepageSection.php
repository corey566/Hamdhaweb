<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomepageSection extends Model
{
    protected $fillable = [
        'section_key', 'title', 'subtitle', 'content',
        'image_path', 'cta_text', 'cta_url', 'extra_data',
        'is_visible', 'sort_order',
    ];

    protected $casts = [
        'extra_data' => 'array',
        'is_visible' => 'boolean',
    ];

    public function getExtraDataAttribute($value): array
    {
        if (is_array($value)) {
            return $value;
        }

        if (is_string($value) && ! empty($value)) {
            $decoded = json_decode($value, true);

            return is_array($decoded) ? $decoded : [];
        }

        return [];
    }

    public static function getSection(string $key): ?self
    {
        return cache()->remember("homepage_section.{$key}", 3600, function () use ($key) {
            return static::where('section_key', $key)->first();
        });
    }

    public static function clearSectionCache(string $key): void
    {
        cache()->forget("homepage_section.{$key}");
    }

    public static function updateSection(string $key, array $data): void
    {
        $section = static::where('section_key', $key)->first();
        if ($section) {
            $section->update($data);
            static::clearSectionCache($key);
        }
    }
}
