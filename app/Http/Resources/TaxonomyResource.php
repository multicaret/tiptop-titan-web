<?php

namespace App\Http\Resources;

use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Taxonomy */
class TaxonomyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return array
     */
    public function toArray($request)
    {

        return [
            'id' => (int) $this->id,
            'type' => $this->getCorrectTypeName(),
            'icon' => $this->icon,
            'title' => $this->title,
            'description' => [
                'raw' => strip_tags($this->description),
                'formatted' => $this->description,
            ],
            'views' => [
                'raw' => $this->views,
                'formatted' => Controller::numberToReadable($this->views),
            ],
            'tree' => [
                'left' => $this->lft,
                'right' => $this->rgt,
                'depth' => $this->depth,
            ],
            'isRoot' => (bool) $this->depth == 0,
            'hasChildren' => $this->hasChildren(),
            'childrenCount' => $this->children()->count(),
            'cover' => $this->cover,
            'thumbnail' => $this->cover_small,
            'createdAt' => [
                'formatted' => $this->created_at->format(config('defaults.date.short_format')),
                'diffForHumans' => $this->created_at->diffForHumans(),
                'timestamp' => $this->created_at->timestamp,
            ],
            'updatedAt' => [
                'formatted' => $this->updated_at->format(config('defaults.date.short_format')),
                'diffForHumans' => $this->updated_at->diffForHumans(),
                'timestamp' => $this->updated_at->timestamp,
            ],
            'user' => new UserResource($this->user),
            'editor' => new UserResource($this->editor),
            'parent' => new TaxonomyResource($this->parent),
        ];
    }
}
