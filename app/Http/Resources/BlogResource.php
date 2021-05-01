<?php

namespace App\Http\Resources;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Post */
class BlogResource extends JsonResource
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
            'notes' => [
                'raw' => strip_tags($this->notes),
                'formatted' => $this->notes,
            ],
            'views' => [
                'raw' => $this->views,
                'formatted' => Controller::numberToReadable($this->views),
            ],
            'cover' => $this->cover,
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
        ];
    }
}
