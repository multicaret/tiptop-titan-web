<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Carbon;

/**
 * App\Models\ProductSearchTag
 *
 * @method static Builder|ProductSearchTag newModelQuery()
 * @method static Builder|ProductSearchTag newQuery()
 * @method static Builder|ProductSearchTag query()
 * @mixin Eloquent
 * @property int $id
 * @property int $product_id
 * @property int $search_tag_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|ProductSearchTag whereCreatedAt($value)
 * @method static Builder|ProductSearchTag whereId($value)
 * @method static Builder|ProductSearchTag whereProductId($value)
 * @method static Builder|ProductSearchTag whereSearchTagId($value)
 * @method static Builder|ProductSearchTag whereUpdatedAt($value)
 */
class ProductSearchTag extends Pivot
{
    //
}
