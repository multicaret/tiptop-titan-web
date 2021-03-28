<?php

namespace App\Http\Resources;

use App\Models\Currency;
use App\Models\Location;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Order */
class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => (int) $this->id,
            'address' => new LocationResource(Location::withTrashed()->where('id', $this->address_id)->first()),
            'completedAt' => [
                'formatted' => $this->completed_at->format(config('defaults.date.short_format')),
                'diffForHumans' => $this->completed_at->diffForHumans(),
                'timestamp' => $this->completed_at->timestamp,
            ],
            'deliveryFee' => [
                'raw' => (double) $this->branch->fixed_delivery_fee,
                'formatted' => Currency::format($this->branch->fixed_delivery_fee),
            ],
            'grandTotal' => [
                'raw' => (double) $this->grand_total,
                'formatted' => Currency::format($this->grand_total),
            ],
            'hasBeenRated' => $this->has_been_rated,
            'avgRating' => $this->avg_rating,
            'cart' => new CartResource($this->cart),
            'paymentMethod' => new PaymentMethodResource($this->paymentMethod),
        ];
    }
}
