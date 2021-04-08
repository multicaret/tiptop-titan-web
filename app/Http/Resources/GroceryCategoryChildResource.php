<?php

namespace App\Http\Resources;

use App\Models\Product;
use App\Models\Taxonomy;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Taxonomy */
class GroceryCategoryChildResource extends JsonResource
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
        $productsIds = $this->products()
                            ->where([['available_quantity', '>', 0], ['is_storage_tracking_enabled', true]])
                            ->orWhere('is_storage_tracking_enabled', false)
                            ->distinct('product_id')
                            ->pluck('product_id');
        $products = Product::whereIn('id', $productsIds)->get();

        return [
            'id' => (int) $this->id,
            'type' => $this->getCorrectTypeName(),
            'icon' => $this->icon,
            'englishTitle' => $this->translate('en')->title,
            'title' => $this->title,
            'description' => [
                'raw' => strip_tags($this->description),
                'formatted' => $this->description,
            ],
            'cover' => $this->cover,
            'thumbnail' => $this->cover_small,
            'products' => ProductMiniResource::collection($products),
        ];
    }
}
