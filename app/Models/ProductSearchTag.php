<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Carbon;

/**
 * App\Models\ProductSearchTag
 *
 * @property int $id
 * @property int $product_id
 * @property int $tag_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|ProductSearchTag newModelQuery()
 * @method static Builder|ProductSearchTag newQuery()
 * @method static Builder|ProductSearchTag query()
 * @method static Builder|ProductSearchTag whereCreatedAt($value)
 * @method static Builder|ProductSearchTag whereId($value)
 * @method static Builder|ProductSearchTag whereProductId($value)
 * @method static Builder|ProductSearchTag whereTagId($value)
 * @method static Builder|ProductSearchTag whereUpdatedAt($value)
 * @mixin Eloquent
 */
class ProductSearchTag extends Pivot
{
    //
}
