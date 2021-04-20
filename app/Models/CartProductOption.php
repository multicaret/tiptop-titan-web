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
 */
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
