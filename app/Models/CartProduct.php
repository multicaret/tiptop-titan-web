<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * App\Models\CartProduct
 *
 * @property int $id
 * @property int $cart_id
 * @property int $product_id
 * @property int $quantity
 * @property array|null $product_object
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Cart $cart
 * @property-read \App\Models\Product $product
 * @method static \Illuminate\Database\Eloquent\Builder|CartProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CartProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CartProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder|CartProduct whereCartId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CartProduct whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CartProduct whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CartProduct whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CartProduct whereProductObject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CartProduct whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CartProduct whereUpdatedAt($value)
 * @mixin \Eloquent
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
