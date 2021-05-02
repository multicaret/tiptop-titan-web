<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Carbon;

/**
 * App\Models\BranchDriver
 *
 * @property int $id
 * @property int $branch_id
 * @property int $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|BranchDriver newModelQuery()
 * @method static Builder|BranchDriver newQuery()
 * @method static Builder|BranchDriver query()
 * @method static Builder|BranchDriver whereBranchId($value)
 * @method static Builder|BranchDriver whereCreatedAt($value)
 * @method static Builder|BranchDriver whereId($value)
 * @method static Builder|BranchDriver whereUpdatedAt($value)
 * @method static Builder|BranchDriver whereUserId($value)
 * @mixin Eloquent
 * @noinspection PhpFullyQualifiedNameUsageInspection
 * @noinspection PhpUnnecessaryFullyQualifiedNameInspection
 */
class BranchDriver extends Pivot
{
    //
}
