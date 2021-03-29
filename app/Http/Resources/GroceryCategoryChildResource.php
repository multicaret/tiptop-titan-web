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

        $currentCategoryId = $this->id;
        $products = $this->products()
                         ->where('available_quantity', '>', 0)
                         ->orWhere('is_storage_tracking_enabled', false)
                         ->whereHas('categories', function ($query) use ($currentCategoryId) {
                             return $query->where('taxonomies.id', $currentCategoryId);
                         })
                         ->get();

        return [
            'id' => (int) $this->id,
            'type' => $this->getCorrectTypeName(),
            'icon' => $this->icon,
            'title' => $this->title,
            'description' => [
                'raw' => strip_tags($this->description),
                'formatted' => $this->description,
            ],
            'cover' => $this->cover,
            'thumbnail' => $this->cover_small,
            'products' => ProductResource::collection($products),
        ];
    }
}
