<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Carbon;

/**
 * App\Models\CategoryProductCrossSell
 *
 * @property int $id
 * @property int $category_id
 * @property int $product_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \App\Models\Taxonomy $category
 * @property-read \App\Models\Product $product
 * @method static Builder|CategoryProductCrossSell newModelQuery()
 * @method static Builder|CategoryProductCrossSell newQuery()
 * @method static Builder|CategoryProductCrossSell query()
 * @method static Builder|CategoryProductCrossSell whereCategoryId($value)
 * @method static Builder|CategoryProductCrossSell whereCreatedAt($value)
 * @method static Builder|CategoryProductCrossSell whereId($value)
 * @method static Builder|CategoryProductCrossSell whereProductId($value)
 * @method static Builder|CategoryProductCrossSell whereUpdatedAt($value)
 * @mixin Eloquent
 */
class CategoryProductCrossSell extends Pivot
{
    public function category(): BelongsTo
    {
        return $this->belongsTo(Taxonomy::class, 'category_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
