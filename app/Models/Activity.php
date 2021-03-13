<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
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
     * @return \Illuminate\Database\Eloquent\Collection;
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
