<?php

namespace App\Http\Resources;

use App\Models\Taxonomy;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Taxonomy */
class GroceryCategoryParentWithChildrenResource extends JsonResource
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
        $children = cache()
            ->tags('taxonomies', 'api-home')
            ->rememberForever('children_categories_of_parent_'.$this->id, function () {
                $children = $this
                    ->children()
                    ->orderBy('order_column')
                    ->get();

                return GroceryCategoryChildResource::collection($children);
            });


        return [
            'id' => $this->id,
            'icon' => $this->icon,
            'englishTitle' => $this->translate('en')->title,
            'title' => $this->title,
            'description' => [
                'raw' => strip_tags($this->description),
                'formatted' => $this->description,
            ],
            'hasChildren' => $this->hasChildren(),
            'children' => $children,
            'cover' => $this->cover,
            'thumbnail' => $this->cover_small,
        ];
    }
}
