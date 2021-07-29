<?php

namespace App\Http\Resources\RestaurantApp;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Currency;
use App\Models\WorkingHour;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Branch */
class RestaurantFoodBranchResource extends JsonResource
{

    /**
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $workingHours = WorkingHour::retrieve($this->resource);

        if ( ! $this->is_open_now) {
            $workingHours['isOpen'] = false;
        }

        return [
            'id' => $this->id,
            'title' => $this->title,
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
            'jetDelivery' => [
                'isDeliveryEnabled' => $this->has_jet_delivery,
                'minimumOrder' => [
                    'raw' => $this->jet_minimum_order,
                    'formatted' => Currency::format($this->jet_minimum_order),
                ],
                'fixedDeliveryFee' => [
                    'raw' => $this->jet_fixed_delivery_fee,
                    'formatted' => Currency::format($this->jet_fixed_delivery_fee),
                ],
                'extraFeesPerKm' => $this->jet_extra_delivery_fee_per_km,
                'commissionRate' => $this->jet_delivery_commission_rate
            ],
            'rating' => [
                'colorHexadecimal' => Controller::ratingColorHexadecimal($this->avg_rating),
                'colorRGBA' => Controller::ratingColorRGBA($this->avg_rating),
                'averageRaw' => (float) $this->avg_rating,
                'averageFormatted' => number_format($this->avg_rating, 1),
                'countRaw' => $this->rating_count,
                'countFormatted' => Controller::numberToReadable($this->rating_count),
            ],
            'workingHours' => $workingHours,
            'chainMedia' => [
                'logo' => $this->chain->logo,
                'cover' => $this->chain->cover,
                'gallery' => $this->chain->gallery,
            ],
            'categories' => RestaurantCategoryMiniResource::collection($this->menuCategories()->orderBy('order_column')->get()),
        ];
    }
}
