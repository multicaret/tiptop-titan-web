<?php

namespace App\Http\Resources;

use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Post */
class PageResource extends JsonResource
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
            'id' => $this->id,
            'title' => $this->title,
            'content' => [
                'raw' => strip_tags($this->content),
                'formatted' => $this->content,
            ],
            'excerpt' => [
                'raw' => strip_tags($this->excerpt),
                'formatted' => $this->excerpt,
            ],
            'views' => [
                'raw' => $this->views,
                'formatted' => Controller::numberToReadable($this->views),
            ],
            'categories' => TaxonomyResource::collection($this->categories),
            'type' => $this->getCorrectType(),
            'rating' => [
                'average' => $this->avg_rating,
                'raw' => $this->rating_count,
                'formatted' => Controller::numberToReadable($this->rating_count),
            ],
            'url' => $this->path,
            'cover' => $this->cover,
            'thumbnail' => $this->thumbnail,
            'likes' => [
                'raw' => $this->likes,
                'formatted' => Controller::numberToReadable($this->likes),
            ],
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
        ];
    }
}
