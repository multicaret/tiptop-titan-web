<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * App\Models\CategoryProductUpSell
 *
 * @property int $id
 * @property int $category_id
 * @property int $product_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Taxonomy $category
 * @property-read \App\Models\Product $product
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryProductUpSell newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryProductUpSell newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryProductUpSell query()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryProductUpSell whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryProductUpSell whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryProductUpSell whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryProductUpSell whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryProductUpSell whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CategoryProductUpSell extends Pivot
{
    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Taxonomy::class, 'category_id');
    }

    public function product(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
