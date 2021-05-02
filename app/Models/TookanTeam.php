<?php

namespace App\Models;

use App\Traits\HasStatuses;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\TookanTeam
 *
 * @property int $id
 * @property int $creator_id
 * @property int $editor_id
 * @property string|null $tookan_team_id
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
 * @method static Builder|TookanTeam active()
 * @method static Builder|TookanTeam draft()
 * @method static Builder|TookanTeam inactive()
 * @method static Builder|TookanTeam newModelQuery()
 * @method static Builder|TookanTeam newQuery()
 * @method static Builder|TookanTeam notActive()
 * @method static Builder|TookanTeam query()
 * @method static Builder|TookanTeam whereCreatedAt($value)
 * @method static Builder|TookanTeam whereCreatorId($value)
 * @method static Builder|TookanTeam whereDescription($value)
 * @method static Builder|TookanTeam whereEditorId($value)
 * @method static Builder|TookanTeam whereId($value)
 * @method static Builder|TookanTeam whereName($value)
 * @method static Builder|TookanTeam whereStatus($value)
 * @method static Builder|TookanTeam whereTookanTeamId($value)
 * @method static Builder|TookanTeam whereUpdatedAt($value)
 * @mixin Eloquent
 */
class TookanTeam extends Model
{
    use HasStatuses;

    public const STATUS_DRAFT = 1;
    public const STATUS_ACTIVE = 2;
    public const STATUS_INACTIVE = 3;

    protected $guarded = [];

    public function user()
    {
        return $this->hasMany(User::class);
    }
}
