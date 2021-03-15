<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Product */
class ProductResource extends JsonResource
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
            'description' => $this->description,
            'excerpt' => $this->excerpt,
            'notes' => $this->notes,
            'customBannerText' => $this->custom_banner_text,
            'unitText' => $this->unit_text,
            'quantity' => $this->quantity,
            'sku' => $this->sku,
            'upc' => $this->upc,
            'minimumOrderableQuantity' => $this->minimum_orderable_quantity,
            'avgRating' => $this->avg_rating,
            'ratingCount' => $this->rating_count,
            'price' => [
                'amount' => $this->price,
                'amountFormatted' => $this->price_formatted,
            ],
            'discountedPrice' => [
                'amount' => $this->price_discount_amount,
                'amountFormatted' => $this->discounted_price_formatted,
            ],
            'barcodes' => $this->barcodes,
            'media' => [
                'cover' => $this->cover,
                'gallery' => $this->gallery,
            ],
            'width' => $this->width,
            'height' => $this->height,
            'depth' => $this->depth,
            'weight' => $this->weight,

            'unit' => new UnitResource($this->unit),
        ];
    }
}
