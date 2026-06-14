<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    public static function get(string $key, $default = null): mixed
    {
        return Cache::remember("setting.{$key}", 3600, function () use ($key, $default) {
            $setting = static::where('key', $key)->first();

            return $setting?->value ?? $default;
        });
    }

    public static function set(string $key, $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget("setting.{$key}");
        Cache::forget('site_settings_global');
    }

    public static function getJson(string $key, $default = []): array
    {
        $value = static::get($key);

        return $value ? json_decode($value, true) : $default;
    }
}
