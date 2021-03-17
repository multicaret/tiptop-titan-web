<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class BasketProduct extends Pivot
{
    protected $casts = [
        'product_object' => 'json',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function basket()
    {
        return $this->belongsTo(Basket::class, 'basket_id');
    }
}
