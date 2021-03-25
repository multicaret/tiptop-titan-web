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

        $isFavorited = auth()->check() ? $this->isFavoritedBy(auth()->user()) : false;

        return [
            'id' => (int) $this->id,
            'uuid' => $this->uuid,
            'title' => $this->title,
            'description' => [
                'raw' => strip_tags($this->description),
                'formatted' => $this->description,
            ],
            'excerpt' => [
                'raw' => strip_tags($this->excerpt),
                'formatted' => $this->excerpt,
            ],
            'notes' => [
                'raw' => strip_tags($this->notes),
                'formatted' => $this->notes,
            ],
            'customBannerText' => $this->custom_banner_text,
            'unitText' => $this->unit_text,
            'availableQuantity' => $this->available_quantity,
            'sku' => $this->sku,
            'upc' => $this->upc,
            'minimumOrderableQuantity' => $this->minimum_orderable_quantity,
            'avgRating' => $this->avg_rating,
            'ratingCount' => $this->rating_count,
            'price' => [
                'raw' => (double) $this->price,
                'formatted' => $this->price_formatted,
            ],
            'discountedPrice' => $this->discounted_price === 0 ? null : [
                'raw' => (double) $this->discounted_price,
                'formatted' => $this->discounted_price_formatted,
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
            'isFavorited' => $isFavorited,

            'unit' => new UnitResource($this->unit),
        ];
    }
}
