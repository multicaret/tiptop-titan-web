<?php

namespace App\Http\Resources;

use App\Models\Currency;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Order */
class OrderMiniResource extends JsonResource
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
            'address' => new LocationResource($this->address),
            'completedAt' => [
                'formatted' => $this->completed_at->translatedFormat(config('defaults.datetime.normal_format')),
                'diffForHumans' => $this->completed_at->diffForHumans(),
                'timestamp' => $this->completed_at->timestamp,
            ],
            'totalAfterCouponDiscount' => [
                'raw' => $this->total - $this->coupon_discount_amount,
                'formatted' => Currency::format($this->total - $this->coupon_discount_amount),
            ],
            'grandTotal' => [
                'raw' => (double) $this->grand_total,
                'formatted' => Currency::format($this->grand_total),
            ],
            'branchName' => $this->cart->branch->title,
            'branchLogo' => $this->cart->chain->logo,
        ];
    }
}
