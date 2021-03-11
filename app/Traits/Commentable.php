<?php

namespace App\Traits;

use App\Models\Comment;

trait Commentable
{
    /**
     * This model has many comments.
     *
     * @return mixed
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function ratings()
    {
        return $this->morphMany(Comment::class, 'commentable')->whereNotNull('rating');
    }
}
