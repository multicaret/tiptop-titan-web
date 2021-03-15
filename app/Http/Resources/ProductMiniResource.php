<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Product */
class ProductMiniResource extends JsonResource
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'title' => $this->title,
            'customBannerText' => $this->custom_banner_text,
            'unitText' => $this->unit_text,
            'quantity' => $this->quantity,
            'minimumOrderableQuantity' => $this->minimum_orderable_quantity,
            'price' => [
                'amount' => $this->price,
                'amountFormatted' => $this->price_formatted,
            ],
            'discountedPrice' => [
                'amount' => $this->price_discount_amount,
                'amountFormatted' => $this->discounted_price_formatted,
            ],
            'media' => [
                'cover' => $this->cover,
                'gallery' => $this->gallery,
            ],
            'weight' => $this->weight,
        ];
    }
}
