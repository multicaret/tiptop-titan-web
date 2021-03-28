<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * App\Models\CategoryProductCrossSell
 *
 * @property int $id
 * @property int $category_id
 * @property int $product_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Taxonomy $category
 * @property-read \App\Models\Product $product
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryProductCrossSell newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryProductCrossSell newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryProductCrossSell query()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryProductCrossSell whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryProductCrossSell whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryProductCrossSell whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryProductCrossSell whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryProductCrossSell whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CategoryProductCrossSell extends Pivot
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
