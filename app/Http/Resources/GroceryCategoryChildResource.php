<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Taxonomy */
class GroceryCategoryChildResource extends JsonResource
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
            'type' => $this->getCorrectTypeName(),
            'icon' => $this->icon,
            'title' => $this->title,
            'description' => [
                'raw' => strip_tags($this->description),
                'formatted' => $this->description,
            ],
            'cover' => $this->cover,
            'thumbnail' => $this->thumbnail,
            'products' => ProductResource::collection($this->products),
        ];
    }
}
