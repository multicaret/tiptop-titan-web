<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * App\Models\BranchManager
 *
 * @property int $id
 * @property int $branch_id
 * @property int $manager_id
 * @property int $is_primary
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|BranchManager newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BranchManager newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BranchManager query()
 * @method static \Illuminate\Database\Eloquent\Builder|BranchManager whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BranchManager whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BranchManager whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BranchManager whereIsPrimary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BranchManager whereManagerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BranchManager whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class BranchManager extends Pivot
{
    //
}
