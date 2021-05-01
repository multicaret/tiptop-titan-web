<?php

namespace App\Http\Resources;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Post */
class StaticPageResource extends JsonResource
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
            'views' => [
                'raw' => $this->view_count,
                'formatted' => Controller::numberToReadable($this->view_count),
            ],
        ];
    }
}
