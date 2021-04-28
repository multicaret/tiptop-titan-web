<?php

namespace App\Models;

use App\Traits\HasStatuses;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\TokanTeam
 *
 * @property int $id
 * @property int $creator_id
 * @property int $editor_id
 * @property string $name
 * @property string|null $description
 * @property int $status 1:draft, 2:active, 3:Inactive, 4..n:CUSTOM
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read bool $is_active
 * @property-read bool $is_inactive
 * @property-read array $status_js
 * @property-read mixed $status_name
 * @property-read Collection|\App\Models\User[] $user
 * @property-read int|null $user_count
 * @method static Builder|TokanTeam active()
 * @method static Builder|TokanTeam draft()
 * @method static Builder|TokanTeam inactive()
 * @method static Builder|TokanTeam newModelQuery()
 * @method static Builder|TokanTeam newQuery()
 * @method static Builder|TokanTeam notActive()
 * @method static Builder|TokanTeam query()
 * @method static Builder|TokanTeam whereCreatedAt($value)
 * @method static Builder|TokanTeam whereCreatorId($value)
 * @method static Builder|TokanTeam whereDescription($value)
 * @method static Builder|TokanTeam whereEditorId($value)
 * @method static Builder|TokanTeam whereId($value)
 * @method static Builder|TokanTeam whereName($value)
 * @method static Builder|TokanTeam whereStatus($value)
 * @method static Builder|TokanTeam whereUpdatedAt($value)
 * @mixin Eloquent
 */
class TokanTeam extends Model
{
    use HasStatuses;

    public const STATUS_DRAFT = 1;
    public const STATUS_ACTIVE = 2;
    public const STATUS_INACTIVE = 3;

    protected $fillable = [];

    public function user()
    {
        return $this->hasMany(User::class);
    }
}
