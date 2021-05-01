<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Carbon;

/**
 * App\Models\BranchOwner
 *
 * @property int $id
 * @property int $branch_id
 * @property int $user_id
 * @property int $is_primary
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|BranchOwner newModelQuery()
 * @method static Builder|BranchOwner newQuery()
 * @method static Builder|BranchOwner query()
 * @method static Builder|BranchOwner whereBranchId($value)
 * @method static Builder|BranchOwner whereCreatedAt($value)
 * @method static Builder|BranchOwner whereId($value)
 * @method static Builder|BranchOwner whereIsPrimary($value)
 * @method static Builder|BranchOwner whereUpdatedAt($value)
 * @method static Builder|BranchOwner whereUserId($value)
 * @mixin Eloquent
 * @noinspection PhpFullyQualifiedNameUsageInspection
 * @noinspection PhpUnnecessaryFullyQualifiedNameInspection
 */
class BranchOwner extends Pivot
{
    //
}
