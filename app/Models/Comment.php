<?php

namespace App\Models;

use Baum\Node as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;

    const STATUS_SHOWN = 1;
    const STATUS_REPORTED = 2;

    /**
     * Determine if the comment has children.
     *
     * @return bool
     */
    public function hasChildren()
    {
        return $this->children()->count() > 0;
    }

    /**
     * Get all of comment's children.
     *
     * @param  array  $columns
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getChildren($columns = ['*'])
    {
        return $this->children()->get($columns);
    }

    /**
     * Get all of the owning commentable models.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function commentable()
    {
        return $this->morphTo();
    }

    /**
     * Comment belongs to a user.
     *
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
