<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * App\Models\CategoryBranch
 *
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryBranch newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryBranch newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryBranch query()
 * @mixin \Eloquent
 * @property int $id
 * @property int $category_id
 * @property int $branch_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryBranch whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryBranch whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryBranch whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryBranch whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryBranch whereUpdatedAt($value)
 */
class CategoryBranch extends Pivot
{
    //
}
