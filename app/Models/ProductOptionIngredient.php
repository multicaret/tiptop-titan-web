<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Carbon;

/**
 * App\Models\ProductOptionIngredient
 *
 * @property int $id
 * @property int $product_option_id
 * @property int $ingredient_id
 * @property float $price
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|ProductOptionIngredient newModelQuery()
 * @method static Builder|ProductOptionIngredient newQuery()
 * @method static Builder|ProductOptionIngredient query()
 * @method static Builder|ProductOptionIngredient whereCreatedAt($value)
 * @method static Builder|ProductOptionIngredient whereId($value)
 * @method static Builder|ProductOptionIngredient whereIngredientId($value)
 * @method static Builder|ProductOptionIngredient wherePrice($value)
 * @method static Builder|ProductOptionIngredient whereProductOptionId($value)
 * @method static Builder|ProductOptionIngredient whereUpdatedAt($value)
 * @mixin Eloquent
 */
class ProductOptionIngredient extends Pivot
{
    //
}
