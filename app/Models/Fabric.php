<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Fabric extends Model
{
    protected $fillable = ['name', 'slug', 'sort_order'];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    protected static function booted(): void
    {
        static::creating(function (Fabric $fabric) {
            if (empty($fabric->slug)) {
                $fabric->slug = Str::slug($fabric->name);
            }
        });
    }
}
