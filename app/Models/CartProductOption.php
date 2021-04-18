<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\Pivot;

class CartProductOption extends Pivot
{
    protected $casts = [
        'product_option_object' => 'json',
    ];

    public function cartProduct(): BelongsTo
    {
        return $this->belongsTo(CartProduct::class, 'cart_product_id');
    }

    public function productOption(): BelongsTo
    {
        return $this->belongsTo(ProductOption::class, 'product_option_id');
    }

    public function selections(): MorphToMany
    {
        return $this->morphToMany(ProductOptionSelection::class, 'selectable')
                    ->withTimestamps();
    }

    public function ingredients(): MorphToMany
    {
        return $this->morphToMany(Taxonomy::class, 'selectable')
                    ->withTimestamps();
    }
}
