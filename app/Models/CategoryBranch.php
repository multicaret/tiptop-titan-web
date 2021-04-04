<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Carbon;

/**
 * App\Models\CategoryBranch
 *
 * @property int $id
 * @property int $category_id
 * @property int $branch_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|CategoryBranch newModelQuery()
 * @method static Builder|CategoryBranch newQuery()
 * @method static Builder|CategoryBranch query()
 * @method static Builder|CategoryBranch whereBranchId($value)
 * @method static Builder|CategoryBranch whereCategoryId($value)
 * @method static Builder|CategoryBranch whereCreatedAt($value)
 * @method static Builder|CategoryBranch whereId($value)
 * @method static Builder|CategoryBranch whereUpdatedAt($value)
 * @mixin Eloquent
 */
class CategoryBranch extends Pivot
{
    //
}
