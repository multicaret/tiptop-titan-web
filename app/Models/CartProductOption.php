<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\Pivot;

class CartProductOption extends Pivot
{
    protected $casts = [
        'product_option_object' => 'json',
    ];

    public function cartProduct(): BelongsToMany
    {
        return $this->belongsToMany(CartProduct::class, 'cart_product_id');
    }

    public function productOption(): BelongsTo
    {
        return $this->belongsTo(ProductOption::class, 'product_option_id');
    }
}
