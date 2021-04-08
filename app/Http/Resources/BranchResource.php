<?php

namespace App\Http\Resources;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Currency;
use App\Models\Place;
use App\Models\WorkingHour;
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
        $workingHours = WorkingHour::retrieve($this);

        if ( ! $this->is_open_now) {
            $workingHours['isOpen'] = false;
        }

        return [
            'id' => (int) $this->id,
            'title' => $this->title,
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
            'hasTipTopDelivery' => $this->has_tip_top_delivery,
            'fixedDeliveryFee' => [
                'raw' => $this->fixed_delivery_fee,
                'formatted' => Currency::format($this->fixed_delivery_fee),
            ],
            'freeDeliveryThreshold' => [
                'raw' => $this->free_delivery_threshold,
                'formatted' => Currency::format($this->free_delivery_threshold),
            ],
            'hasRestaurantDelivery' => $this->has_restaurant_delivery,
            'restaurantFreeDeliveryThreshold' => [
                'raw' => $this->restaurant_free_delivery_threshold,
                'formatted' => Currency::format($this->restaurant_free_delivery_threshold),
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
            'workingHours' => $workingHours,
            'latitude' => (float) $this->latitude,
            'longitude' => (float) $this->longitude,
            'chain' => new ChainResource($this->chain),
        ];
    }
}
