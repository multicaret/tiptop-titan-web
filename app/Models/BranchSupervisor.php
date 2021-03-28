<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * App\Models\BranchSupervisor
 *
 * @property int $id
 * @property int $branch_id
 * @property int $supervisor_id
 * @property int $is_primary
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|BranchSupervisor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BranchSupervisor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BranchSupervisor query()
 * @method static \Illuminate\Database\Eloquent\Builder|BranchSupervisor whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BranchSupervisor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BranchSupervisor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BranchSupervisor whereIsPrimary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BranchSupervisor whereSupervisorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BranchSupervisor whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class BranchSupervisor extends Pivot
{
    //
}
