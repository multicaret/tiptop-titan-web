<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Carbon;

/**
 * App\Models\CartProduct
 *
 * @property int $id
 * @property int $cart_id
 * @property int $product_id
 * @property int $quantity
 * @property array|null $product_object
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \App\Models\Cart $cart
 * @property-read \App\Models\Product $product
 * @method static Builder|CartProduct newModelQuery()
 * @method static Builder|CartProduct newQuery()
 * @method static Builder|CartProduct query()
 * @method static Builder|CartProduct whereCartId($value)
 * @method static Builder|CartProduct whereCreatedAt($value)
 * @method static Builder|CartProduct whereId($value)
 * @method static Builder|CartProduct whereProductId($value)
 * @method static Builder|CartProduct whereProductObject($value)
 * @method static Builder|CartProduct whereQuantity($value)
 * @method static Builder|CartProduct whereUpdatedAt($value)
 * @mixin Eloquent
 */
class CartProduct extends Pivot
{
    protected $casts = [
        'product_object' => 'json',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function cart()
    {
        return $this->belongsTo(Cart::class, 'cart_id');
    }
}
