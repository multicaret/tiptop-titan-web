<?php

namespace App\Http\Resources;

use App\Models\Product;
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
        $isMenuCategory = $this->type == Taxonomy::TYPE_MENU_CATEGORY;
        if ($isMenuCategory) {
            $menuProducts = $this->menuProducts()
                                 ->where('status', '!=', Product::STATUS_DRAFT);
            $products = ProductMiniResource::collection($menuProducts->orderByDesc('order_column')->get());
        }

        return [
            'id' => $this->id,
            'icon' => $this->icon,
            'englishTitle' => optional($this->translate('en'))->title,
            'title' => $this->title,
            'description' => [
                'raw' => strip_tags($this->description),
                'formatted' => $this->description,
            ],
            'hasChildren' => $this->hasChildren(),
            'cover' => $this->cover,
            'thumbnail' => $this->cover_small,
            'products' => $this->when($isMenuCategory, $products),
        ];
    }
}
