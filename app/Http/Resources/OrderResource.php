<?php

namespace App\Http\Resources;

use App\Models\Currency;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Order */
class OrderResource extends JsonResource
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
            'rating' => [
                'branchHasBeenRated' => ! is_null($this->branch_rating_value),
                'branchRatingValue' => (double) $this->branch_rating_value,
                'driverHasBeenRated' => ! is_null($this->driver_rating_value),
                'driverRatingValue' => (double) $this->driver_rating_value,
                'ratingComment' => $this->rating_comment,
                'hasGoodFoodQualityRating' => $this->has_good_food_quality_rating,
                'hasGoodPackagingQualityRating' => $this->has_good_packaging_quality_rating,
                'hasGoodOrderAccuracyRating' => $this->has_good_order_accuracy_rating,
                'ratingIssue' => [
                    'id' => (int) optional($this->ratingIssue)->id,
                    'title' => optional($this->ratingIssue)->title,
                ],
            ],
            'status' => $this->status,
            'statusName' => $this->status_name,
            'customerNotes' => $this->customer_notes,
            'driverName' => optional($this->driver)->name,
            'driverAvatar' => optional($this->driver)->avatar,
            'trackingLink' => optional($this->tookanInfo)->delivery_tracking_link,
            'user' => new UserResource($this->user),
            'cart' => new CartResource($this->cart),
            'paymentMethod' => new PaymentMethodResource($this->paymentMethod),
        ];
    }
}
