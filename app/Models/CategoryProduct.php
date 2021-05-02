<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Carbon;

/**
 * App\Models\CategoryProduct
 *
 * @property int $id
 * @property int $category_id
 * @property int $product_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|CategoryProduct newModelQuery()
 * @method static Builder|CategoryProduct newQuery()
 * @method static Builder|CategoryProduct query()
 * @method static Builder|CategoryProduct whereCategoryId($value)
 * @method static Builder|CategoryProduct whereCreatedAt($value)
 * @method static Builder|CategoryProduct whereId($value)
 * @method static Builder|CategoryProduct whereProductId($value)
 * @method static Builder|CategoryProduct whereUpdatedAt($value)
 * @mixin Eloquent
 * @noinspection PhpFullyQualifiedNameUsageInspection
 * @noinspection PhpUnnecessaryFullyQualifiedNameInspection
 */
class CategoryProduct extends Pivot
{
    //
}
