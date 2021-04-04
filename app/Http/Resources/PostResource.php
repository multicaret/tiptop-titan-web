<?php

namespace App\Http\Resources;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Post */
class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => (int) $this->id,
            'user' => new UserResource($this->user),
//            'comments' => CommentResource::collection($this->whenLoaded('comments')),
            'title' => $this->title,
            'content' => [
                'raw' => strip_tags($this->content),
                'formatted' => $this->content,
            ],
            'excerpt' => [
                'raw' => strip_tags($this->excerpt),
                'formatted' => $this->excerpt,
            ],
            'notes' => [
                'raw' => strip_tags($this->notes),
                'formatted' => $this->notes,
            ],
            'type' => $this->getCorrectType(),
//            'url' => $this->path, //todo: see if this is necessary
            'cover' => $this->cover,
            'thumbnail' => $this->thumbnail,
            'rating' => [
                'average' => $this->avg_rating,
                'countRaw' => $this->rating_count,
                'countFormatted' => Controller::numberToReadable($this->rating_count),
            ],
            'views' => [
                'raw' => $this->views,
                'formatted' => Controller::numberToReadable($this->views),
            ],
            'likes' => [
                'raw' => $this->likes,
                'formatted' => Controller::numberToReadable($this->likes),
            ],
//            'categories' => TaxonomyResource::collection($this->categories),
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
            'category' => new TaxonomyResource($this->category),
        ];
    }
}
