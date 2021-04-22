<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Carbon;

/**
 * App\Models\BranchManager
 *
 * @property int $id
 * @property int $branch_id
 * @property int $manager_id
 * @property int $is_primary
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|BranchManager newModelQuery()
 * @method static Builder|BranchManager newQuery()
 * @method static Builder|BranchManager query()
 * @method static Builder|BranchManager whereBranchId($value)
 * @method static Builder|BranchManager whereCreatedAt($value)
 * @method static Builder|BranchManager whereId($value)
 * @method static Builder|BranchManager whereIsPrimary($value)
 * @method static Builder|BranchManager whereManagerId($value)
 * @method static Builder|BranchManager whereUpdatedAt($value)
 * @mixin Eloquent
 * @property int $user_id
 * @method static Builder|BranchManager whereUserId($value)
 */
class BranchManager extends Pivot
{
    //
}
