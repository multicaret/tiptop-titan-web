<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Carbon;

/**
 * App\Models\BranchSupervisor
 *
 * @property int $id
 * @property int $branch_id
 * @property int $supervisor_id
 * @property int $is_primary
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|BranchSupervisor newModelQuery()
 * @method static Builder|BranchSupervisor newQuery()
 * @method static Builder|BranchSupervisor query()
 * @method static Builder|BranchSupervisor whereBranchId($value)
 * @method static Builder|BranchSupervisor whereCreatedAt($value)
 * @method static Builder|BranchSupervisor whereId($value)
 * @method static Builder|BranchSupervisor whereIsPrimary($value)
 * @method static Builder|BranchSupervisor whereSupervisorId($value)
 * @method static Builder|BranchSupervisor whereUpdatedAt($value)
 * @mixin Eloquent
 */
class BranchSupervisor extends Pivot
{
    //
}
