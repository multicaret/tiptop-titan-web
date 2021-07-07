<?php

namespace App\Http\Resources;

use App\Models\Currency;
use App\Models\Location;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Order */
class JetOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'referenceCode' => $this->reference_code,
            'full_name' => $this->destination_full_name,
            'phone' => $this->destination_phone,
            'address' => $this->destination_address,
            'latitude' => $this->destination_latitude,
            'longitude' => $this->destination_longitude,
            'deliveryFee' => [
                'raw' => (double) $this->delivery_fee,
                'formatted' => Currency::format($this->delivery_fee),
            ],
            'grandTotal' => [
                'raw' => (double) $this->grand_total,
                'formatted' => Currency::format($this->grand_total),
            ],
            'status' => $this->status,
            'statusName' => $this->status_name,
            'driverName' => optional($this->driver)->first,
            'driverAvatar' => optional($this->driver)->avatar,
            'trackingLink' => optional($this->tookanInfo)->delivery_tracking_link,
//            'paymentMethod' => new PaymentMethodResource($this->paymentMethod),
            'images' => $this->gallery,

        ];
    }
}
