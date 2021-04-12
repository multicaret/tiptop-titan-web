<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * App\Models\ProductOptionIngredient
 *
 * @property int $id
 * @property int $product_option_id
 * @property int $ingredient_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOptionIngredient newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOptionIngredient newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOptionIngredient query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOptionIngredient whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOptionIngredient whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOptionIngredient whereIngredientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOptionIngredient whereProductOptionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOptionIngredient whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ProductOptionIngredient extends Pivot
{
    //
}
