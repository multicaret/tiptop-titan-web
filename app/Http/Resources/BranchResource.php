<?php

namespace App\Http\Resources;

use App\Models\Currency;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Branch */
class BranchResource extends JsonResource
{
    /**
     * @param  \Illuminate\Http\Request  $request
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
            'primaryPhoneNumber' => $this->primary_phone_number,
            'secondaryPhoneNumber' => $this->secondary_phone_number,
            'whatsappPhoneNumber' => $this->whatsapp_phone_number,
            'latitude' => (float) $this->latitude,
            'longitude' => (float) $this->longitude,
            'chain' => new ChainResource($this->chain),
        ];
    }
}
