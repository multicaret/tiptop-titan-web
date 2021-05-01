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
        $isFavorited = auth('sanctum')->check() ? $this->isFavoritedBy(auth('sanctum')->user()) : false;

        return [
            'id' => $this->id,
            'title' => $this->title,
            'regionId' => $this->region_id,
            'cityId' => $this->city_id,
            'tiptopDelivery' => [
                'isDeliveryEnabled' => $this->has_tip_top_delivery,
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
                'freeDeliveryThreshold' => [
                    'raw' => $this->free_delivery_threshold,
                    'formatted' => Currency::format($this->free_delivery_threshold),
                ],
                'minDeliveryMinutes' => $this->min_delivery_minutes,
                'maxDeliveryMinutes' => $this->max_delivery_minutes,
            ],
            'restaurantDelivery' => [
                'isDeliveryEnabled' => $this->has_restaurant_delivery,
                'minimumOrder' => [
                    'raw' => $this->restaurant_minimum_order,
                    'formatted' => Currency::format($this->restaurant_minimum_order),
                ],
                'underMinimumOrderDeliveryFee' => [
                    'raw' => $this->restaurant_under_minimum_order_delivery_fee,
                    'formatted' => Currency::format($this->restaurant_under_minimum_order_delivery_fee),
                ],
                'fixedDeliveryFee' => [
                    'raw' => $this->restaurant_fixed_delivery_fee,
                    'formatted' => Currency::format($this->restaurant_fixed_delivery_fee),
                ],
                'freeDeliveryThreshold' => [
                    'raw' => $this->restaurant_free_delivery_threshold,
                    'formatted' => Currency::format($this->restaurant_free_delivery_threshold),
                ],
                'minDeliveryMinutes' => $this->restaurant_min_delivery_minutes,
                'maxDeliveryMinutes' => $this->restaurant_max_delivery_minutes,
            ],
            'primaryPhoneNumber' => $this->primary_phone_number,
            'secondaryPhoneNumber' => $this->secondary_phone_number,
            'whatsappPhoneNumber' => $this->whatsapp_phone_number,
            'rating' => [
                'colorHexadecimal' => Controller::ratingColorHexadecimal($this->avg_rating),
                'colorRGBA' => Controller::ratingColorRGBA($this->avg_rating),
                'averageRaw' => (float) $this->avg_rating,
                'averageFormatted' => number_format($this->avg_rating, 1),
                'countRaw' => $this->rating_count,
                'countFormatted' => Controller::numberToReadable($this->rating_count),
            ],
            'workingHours' => $workingHours,
            'latitude' => (float) $this->latitude,
            'longitude' => (float) $this->longitude,
            'chain' => new ChainResource($this->chain),
            'isFavorited' => $isFavorited,
        ];
    }
}
