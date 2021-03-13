<?php

namespace App\Http\Resources;

use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Post */
class PrivacyResource extends JsonResource
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
