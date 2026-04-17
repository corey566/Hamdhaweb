<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PriceBucket extends Model
{
    protected $fillable = ['label', 'min_price', 'max_price', 'sort_order'];

    protected $casts = [
        'min_price' => 'decimal:2',
        'max_price' => 'decimal:2',
    ];

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }
}
