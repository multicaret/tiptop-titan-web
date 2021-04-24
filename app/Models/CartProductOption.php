<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * App\Models\CartProductOption
 *
 * @property-read CartProduct $cartProduct
 * @property-read Collection|Taxonomy[] $ingredients
 * @property-read int|null $ingredients_count
 * @property-read ProductOption $productOption
 * @property-read Collection|ProductOptionSelection[] $selections
 * @property-read int|null $selections_count
 * @method static Builder|CartProductOption newModelQuery()
 * @method static Builder|CartProductOption newQuery()
 * @method static Builder|CartProductOption query()
 * @mixin Eloquent
 * @property int $id
 * @property int $cart_product_id
 * @property int $product_option_id
 * @property array|null $product_option_object
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static Builder|CartProductOption whereCartProductId($value)
 * @method static Builder|CartProductOption whereCreatedAt($value)
 * @method static Builder|CartProductOption whereId($value)
 * @method static Builder|CartProductOption whereProductOptionId($value)
 * @method static Builder|CartProductOption whereProductOptionObject($value)
 * @method static Builder|CartProductOption whereUpdatedAt($value)
 */
class CartProductOption extends Pivot
{
    protected $casts = [
        'product_option_object' => 'json',
    ];

    public static function boot()
    {
        static::creating(function ($model) {
            $model->product_option_object = ProductOptionfind($model->product_option_id);
        });
        parent::boot();
    }

    public function cartProduct(): BelongsTo
    {
        return $this->belongsTo(CartProduct::class, 'cart_product_id');
    }

    public function productOption(): BelongsTo
    {
        return $this->belongsTo(ProductOption::class, 'product_option_id');
    }

    public function selections()
    {
        return $this->hasMany(CartProductOptionSelection::class, 'cart_product_id', 'cart_product_id')
                    ->where('selectable_type', ProductOptionSelection::class);
    }

    public function ingredients()
    {
        return $this->hasMany(CartProductOptionSelection::class, 'cart_product_id', 'cart_product_id')
                    ->where('selectable_type', Taxonomy::class);
    }
}
