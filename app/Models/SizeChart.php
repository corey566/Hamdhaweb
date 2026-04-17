<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SizeChart extends Model
{
    protected $fillable = ['name', 'image_path', 'sort_order'];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_size_chart');
    }
}
