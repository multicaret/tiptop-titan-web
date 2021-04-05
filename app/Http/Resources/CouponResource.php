<?php

namespace App\Http\Resources;

use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Coupon */
class CouponResource extends JsonResource
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
            'redeemCode' => $this->redeem_code,
            'type' => $this->type,
            'discountBy' => $this->discount_by_percentage ? 'percentage' : 'amount',
            'discountAmount' => $this->discount_amount,
            'hasFreeDelivery' => $this->has_free_delivery,
            'maxAllowedDiscountAmount' => $this->max_allowed_discount_amount,
            'status' => $this->status,
            'expiredAt' => [
                'formatted' => $this->expired_at ? $this->expired_at->format(config('defaults.date.short_format')) : null,
                'diffForHumans' => $this->expired_at ? $this->expired_at->diffForHumans() : null,
                'timestamp' => $this->expired_at ? $this->expired_at->timestamp : null,
            ],
            'createdAt' => [
                'formatted' => $this->created_at->format(config('defaults.date.short_format')),
                'diffForHumans' => $this->created_at->diffForHumans(),
                'timestamp' => $this->created_at->timestamp,
            ],
            'updatedAt' => [
                'formatted' => $this->updated_at->format(config('defaults.date.short_format')),
                'diffForHumans' => $this->updated_at->diffForHumans(),
                'timestamp' => $this->updated_at->timestamp,
            ],
        ];
    }
}
