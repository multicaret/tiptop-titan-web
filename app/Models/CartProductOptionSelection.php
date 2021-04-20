<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * App\Models\CartProductOptionSelection
 *
 * @property-read CartProduct $cartProduct
 * @property-read ProductOption $productOption
 * @property-read Model|Eloquent $selectable
 * @method static Builder|CartProductOptionSelection newModelQuery()
 * @method static Builder|CartProductOptionSelection newQuery()
 * @method static Builder|CartProductOptionSelection query()
 * @mixin Eloquent
 */
class CartProductOptionSelection extends Pivot
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

    public function selectable(): MorphTo
    {
        return $this->morphTo();
    }
}
