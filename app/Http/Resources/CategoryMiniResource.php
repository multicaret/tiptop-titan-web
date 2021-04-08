<?php

namespace App\Http\Resources;

use App\Models\Taxonomy;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Taxonomy */
class CategoryMiniResource extends JsonResource
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

        $products = null;
        if ($this->type == Taxonomy::TYPE_MENU_CATEGORY) {
            $products = ProductMiniResource::collection($this->products()->orderByDesc('order_column')->get());
        }

        return [
            'id' => (int) $this->id,
            'icon' => $this->icon,
            'englishTitle' => $this->translate('en')->title,
            'title' => $this->title,
            'description' => [
                'raw' => strip_tags($this->description),
                'formatted' => $this->description,
            ],
            'hasChildren' => $this->hasChildren(),
            'cover' => $this->cover,
            'thumbnail' => $this->cover_small,
            'products' => $this->when($this->type == Taxonomy::TYPE_MENU_CATEGORY, $products),
        ];
    }
}
