<?php

namespace App\Http\Resources;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Currency;
use App\Models\Place;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Branch */
class BranchResource extends JsonResource
{
    /**
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {

        return [
            'id' => (int) $this->id,
            'regionId' => $this->region_id,
            'cityId' => $this->city_id,
            'minimumOrder' => [
                'raw' => $this->minimum_order,
                'formatted' => Currency::format($this->minimum_order),
            ],
            'underMinimumOrderDeliveryFee' => [
                'raw' => $this->under_minimum_order_delivery_fee,
                'formatted' => Currency::format($this->under_minimum_order_delivery_fee),
            ],
            'fixedDeliveryFee' => [
                'raw' => $this->fixed_delivery_fee,
                'formatted' => Currency::format($this->fixed_delivery_fee),
            ],
            'restaurantMinimumOrder' => [
                'raw' => $this->restaurant_minimum_order,
                'formatted' => Currency::format($this->restaurant_minimum_order),
            ],
            'restaurantUnderMinimumOrderDeliveryFee' => [
                'raw' => $this->restaurant_under_minimum_order_delivery_fee,
                'formatted' => Currency::format($this->restaurant_under_minimum_order_delivery_fee),
            ],
            'restaurantFixedDeliveryFee' => [
                'raw' => $this->restaurant_fixed_delivery_fee,
                'formatted' => Currency::format($this->restaurant_fixed_delivery_fee),
            ],
            'primaryPhoneNumber' => $this->primary_phone_number,
            'secondaryPhoneNumber' => $this->secondary_phone_number,
            'whatsappPhoneNumber' => $this->whatsapp_phone_number,
            'rating' => [
                'colorHexadecimal' => Controller::ratingColorHexadecimal($this->avg_rating),
                'colorRGBA' => Controller::ratingColorRGBA($this->avg_rating),
                'averageRaw' => $this->avg_rating,
                'averageFormatted' => (float) number_format($this->avg_rating, 1),
                'countRaw' => $this->rating_count,
                'countFormatted' => Controller::numberToReadable($this->rating_count),
            ],
            'latitude' => (float) $this->latitude,
            'longitude' => (float) $this->longitude,
            'chain' => new ChainResource($this->chain),
        ];
    }
}
