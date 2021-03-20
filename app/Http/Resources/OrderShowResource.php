<?php

namespace App\Http\Resources;

use App\Models\Currency;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Order */
class OrderShowResource extends JsonResource
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
            'id' => $this->id,
            'address' => [
                'icon' => $this->address->icon,
                'alias' => $this->address->name,
                'written' => $this->address->address1,
            ],
            'completedAt' => [
                'formatted' => $this->completed_at->format(config('defaults.date.short_format')),
                'diffForHumans' => $this->completed_at->diffForHumans(),
                'timestamp' => $this->completed_at->timestamp,
            ],
            'cart' => new CartResource($this->cart),
            'paymentBreakdown' => [
                'total' => [
                    'raw' => $this->grand_total,
                    'formatted' => Currency::format($this->total),
                ],
                'delivery_fee' => [
                    'raw' => $this->grand_total,
                    'formatted' => Currency::format($this->deilvery_fee),
                ],
                'grandTotal' => [
                    'raw' => $this->grand_total,
                    'formatted' => Currency::format($this->grand_total),
                ],
            ]
        ];
    }
}
