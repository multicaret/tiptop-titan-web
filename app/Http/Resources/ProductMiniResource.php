<?php

namespace App\Http\Resources;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Product */
class ProductMiniResource extends JsonResource
{
    /**
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => (int) $this->id,
            'englishTitle' => optional($this->translate('en'))->title,
            'title' => $this->title,
            'excerpt' => [
                'raw' => strip_tags($this->excerpt),
                'formatted' => $this->excerpt,
            ],
            'customBannerText' => $this->custom_banner_text,
            'unitText' => $this->unit_text,
            'availableQuantity' => $this->available_quantity,
            'minimumOrderableQuantity' => $this->minimum_orderable_quantity,
            'price' => [
                'raw' => (double) $this->price,
                'formatted' => $this->price_formatted,
            ],
            'discountedPrice' => $this->discounted_price === 0 ? null : [
                'raw' => (double) $this->discounted_price,
                'formatted' => $this->discounted_price_formatted,
            ],
            'media' => [
                'cover' => $this->cover,
                'coverThumbnail' => $this->cover_thumbnail,
                'coverFull' => $this->cover_full,
                'gallery' => $this->gallery,
            ],
        ];
    }
}
