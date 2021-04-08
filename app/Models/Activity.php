<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Activity
 *
 * @property int $id
 * @property int $user_id
 * @property string $subject_type
 * @property int $subject_id
 * @property string $type
 * @property int $is_private
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Model|\Eloquent $subject
 * @property-read \App\Models\User $user
 * @method static Builder|Activity newModelQuery()
 * @method static Builder|Activity newQuery()
 * @method static Builder|Activity query()
 * @method static Builder|Activity whereCreatedAt($value)
 * @method static Builder|Activity whereId($value)
 * @method static Builder|Activity whereIsPrivate($value)
 * @method static Builder|Activity whereSubjectId($value)
 * @method static Builder|Activity whereSubjectType($value)
 * @method static Builder|Activity whereType($value)
 * @method static Builder|Activity whereUpdatedAt($value)
 * @method static Builder|Activity whereUserId($value)
 * @mixin Eloquent
 */
class Activity extends Model
{
    /**
     * Don't auto-apply mass assignment protection.
     *
     * @var array
     */
    protected $guarded = [];

    protected $fillable = ['user_id', 'subject_id', 'subject_type', 'type', 'is_private'];

    /**
     * Fetch the associated subject for the activity.
     *
     * @return MorphTo
     */
    public function subject()
    {
        return $this->morphTo();
    }

    /**
     * Fetch an activity feed for the given user.
     *
     * @param  User  $user
     * @param  int  $take
     *
     * @return Collection;
     */
    public static function feed($user = null, $take = 50)
    {
        $builder = static::with('subject');

        if ($user) {
            $builder->where('user_id', $user->id);
//        } else {
//            $builder->where('user_id', '!=', auth()->id());
            // if statement @ blade should suffice!
//            $builder->where('is_private', 0);
        }

        return $builder
            ->latest()
            ->take($take)
            ->get()
            ->groupBy(function ($activity) {
                return $activity->created_at->format('Y-m-d');
            });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
