<?php

namespace App\Models;

use App\Traits\HasStatuses;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\TokanTeam
 *
 * @property int $id
 * @property int $creator_id
 * @property int $editor_id
 * @property string $name
 * @property string|null $description
 * @property int $status 1:draft, 2:active, 3:Inactive, 4..n:CUSTOM
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read bool $is_active
 * @property-read bool $is_inactive
 * @property-read mixed $status_name
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $user
 * @property-read int|null $user_count
 * @method static \Illuminate\Database\Eloquent\Builder|TokanTeam active()
 * @method static \Illuminate\Database\Eloquent\Builder|TokanTeam draft()
 * @method static \Illuminate\Database\Eloquent\Builder|TokanTeam inactive()
 * @method static \Illuminate\Database\Eloquent\Builder|TokanTeam newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TokanTeam newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TokanTeam notActive()
 * @method static \Illuminate\Database\Eloquent\Builder|TokanTeam query()
 * @method static \Illuminate\Database\Eloquent\Builder|TokanTeam whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TokanTeam whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TokanTeam whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TokanTeam whereEditorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TokanTeam whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TokanTeam whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TokanTeam whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TokanTeam whereUpdatedAt($value)
 * @mixin \Eloquent
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
