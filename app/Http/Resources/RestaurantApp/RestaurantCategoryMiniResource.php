<?php

namespace App\Http\Resources\RestaurantApp;

use App\Models\Taxonomy;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Taxonomy */
class RestaurantCategoryMiniResource extends JsonResource
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
        $isMenuCategory = $this->type == Taxonomy::TYPE_MENU_CATEGORY;
//        if ($isMenuCategory) {
        $menuProducts = $this->menuProducts();
        $user = auth('sanctum')->user();
        if (is_null($user) || ( ! is_null($user) && ! $user->is_branch_manager)) {
            $menuProducts = $menuProducts->notDraft();
        }
        $products = RestaurantProductMiniResource::collection($menuProducts->orderByDesc('order_column')->get());

//        }

        return [
            'id' => $this->id,
            'icon' => $this->icon,
            'title' => $this->title,
            'hasChildren' => $this->hasChildren(),
            'products' => $products,
        ];
    }
}
