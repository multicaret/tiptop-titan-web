<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Carbon;

/**
 * App\Models\CategoryProductUpSell
 *
 * @property int $id
 * @property int $category_id
 * @property int $product_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \App\Models\Taxonomy $category
 * @property-read \App\Models\Product $product
 * @method static Builder|CategoryProductUpSell newModelQuery()
 * @method static Builder|CategoryProductUpSell newQuery()
 * @method static Builder|CategoryProductUpSell query()
 * @method static Builder|CategoryProductUpSell whereCategoryId($value)
 * @method static Builder|CategoryProductUpSell whereCreatedAt($value)
 * @method static Builder|CategoryProductUpSell whereId($value)
 * @method static Builder|CategoryProductUpSell whereProductId($value)
 * @method static Builder|CategoryProductUpSell whereUpdatedAt($value)
 * @mixin Eloquent
 * @noinspection PhpFullyQualifiedNameUsageInspection
 * @noinspection PhpUnnecessaryFullyQualifiedNameInspection
 */
class CategoryProductUpSell extends Pivot
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
