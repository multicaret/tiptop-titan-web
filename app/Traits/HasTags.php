<?php

namespace App\Traits;

use App\Models\Taxonomy;

trait HasTags
{
    public function tags()
    {
        return $this->morphToMany(Taxonomy::class, 'taggable')
                    ->withPivot(['order_column'])
                    ->withTimestamps();
    }

    public function getTags()
    {
        return $this->tags()->with('translations')->get()->pluck('title', 'id')->all();
    }
}
