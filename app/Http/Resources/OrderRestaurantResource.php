<?php

namespace App\Http\Resources;

use App\Models\Currency;
use App\Models\Location;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Order */
class OrderRestaurantResource extends JsonResource
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
            'id' => (int) $this->id,
            'address' => new LocationResource(Location::withTrashed()->where('id', $this->address_id)->first()),
            'completedAt' => [
                'formatted' => $this->completed_at->format(config('defaults.datetime.normal_format')),
                'diffForHumans' => $this->completed_at->diffForHumans(),
                'timestamp' => $this->completed_at->timestamp,
            ],
            'couponDiscountAmount' => [
                'raw' => (double) $this->coupon_discount_amount,
                'formatted' => Currency::format($this->coupon_discount_amount),
            ],
            'totalAfterCouponDiscount' => [
                'raw' => $this->total - $this->coupon_discount_amount,
                'formatted' => Currency::format($this->total - $this->coupon_discount_amount),
            ],
            'couponCode' => optional($this->coupon)->redeem_code,
            'deliveryType' => $this->is_delivery_by_tiptop ? 'tiptop' : 'restaurant',
            'deliveryFee' => [
                'raw' => (double) $this->delivery_fee,
                'formatted' => Currency::format($this->delivery_fee),
            ],
            'grandTotal' => [
                'raw' => (double) $this->grand_total,
                'formatted' => Currency::format($this->grand_total),
            ],
            'user' => new UserResource($this->user),
            'products' => CartProductResource::collection($this->cart->cartProducts),
            'status' => $this->status,
            'paymentMethod' => new PaymentMethodResource($this->paymentMethod),
        ];
    }
}
