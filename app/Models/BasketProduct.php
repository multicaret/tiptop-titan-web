<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class BasketProduct extends Pivot
{
    protected $casts = [
        'product_object' => 'json',
    ];
}
