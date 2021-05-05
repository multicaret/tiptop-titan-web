<?php

namespace App\Http\Resources;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Product */
class ProductResource extends JsonResource
{
    /**
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {

        $isFavorited = auth('sanctum')->check() ? $this->isFavoritedBy(auth('sanctum')->user()) : false;

        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'englishTitle' => $this->translate('en')->title,
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
            'unit' => new UnitResource($this->unit),
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
            'discountedPrice' => $this->price_discount_amount == 0 ? null : [
                'raw' => (double) $this->discounted_price,
                'formatted' => $this->discounted_price_formatted,
            ],
            'media' => [
                'cover' => $this->cover,
                'coverSmall' => $this->cover_small,
                'coverThumbnail' => $this->cover_thumbnail,
                'coverFull' => $this->cover_full,
                'gallery' => $this->gallery,
            ],
            'width' => $this->width,
            'height' => $this->height,
            'depth' => $this->depth,
            'weight' => $this->weight,
            'isFavorited' => $isFavorited,
            'isDisabled' => $this->status == Product::STATUS_INACTIVE,

            'options' => ProductOptionResource::collection($this->options),
        ];
    }
}
