<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Carbon;

/**
 * App\Models\CartProduct
 *
 * @property int $id
 * @property int $cart_id
 * @property int $product_id
 * @property int $quantity
 * @property float $options_price for Food products only, for their options
 * @property float $total_options_price for Food products only, for their options
 * @property array|null $product_object
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \App\Models\Cart $cart
 * @property-read Collection|\App\Models\CartProductOption[] $cartProductOptions
 * @property-read int|null $cart_product_options_count
 * @property-read Collection|\App\Models\CartProductOptionSelection[] $cartProductOptionsSelections
 * @property-read int|null $cart_product_options_selections_count
 * @property-read \App\Models\Product $product
 * @method static Builder|CartProduct newModelQuery()
 * @method static Builder|CartProduct newQuery()
 * @method static Builder|CartProduct query()
 * @method static Builder|CartProduct whereCartId($value)
 * @method static Builder|CartProduct whereCreatedAt($value)
 * @method static Builder|CartProduct whereId($value)
 * @method static Builder|CartProduct whereOptionsPrice($value)
 * @method static Builder|CartProduct whereProductId($value)
 * @method static Builder|CartProduct whereProductObject($value)
 * @method static Builder|CartProduct whereQuantity($value)
 * @method static Builder|CartProduct whereTotalOptionsPrice($value)
 * @method static Builder|CartProduct whereUpdatedAt($value)
 * @mixin Eloquent
 * @noinspection PhpFullyQualifiedNameUsageInspection
 * @noinspection PhpUnnecessaryFullyQualifiedNameInspection
 */
class CartProduct extends Pivot
{
    protected $hidden = ['product_object'];
    protected $casts = [
        'product_object' => 'json',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class, 'cart_id');
    }

    public function cartProductOptions(): HasMany
    {
        return $this->hasMany(CartProductOption::class, 'cart_product_id');
    }

    public function cartProductOptionsSelections(): HasMany
    {
        return $this->hasMany(CartProductOptionSelection::class, 'cart_product_id');
    }
}
