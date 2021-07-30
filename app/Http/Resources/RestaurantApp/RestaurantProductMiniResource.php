<?php

namespace App\Http\Resources\RestaurantApp;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

/** @mixin Product */
class RestaurantProductMiniResource extends JsonResource
{
    /**
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'excerpt' => [
                'raw' => Str::limit(strip_tags($this->description), 500),
                'formatted' => Str::limit(strip_tags($this->description), 500),
            ],
            'price' => [
                'raw' => (double) $this->price,
                'formatted' => $this->price_formatted,
            ],
            /*'discountedPrice' => $this->price_discount_amount === 0 ? null : [
                'raw' => (double) $this->discounted_price,
                'formatted' => $this->discounted_price_formatted,
            ],*/
            'media' => [
                'coverSmall' => $this->cover_small,
            ],
            'isActive' => $this->status == Product::STATUS_ACTIVE,
            'isDraft' => $this->status == Product::STATUS_DRAFT,
        ];
    }
}
