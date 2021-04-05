<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\CouponUsage
 *
 * @property int $id
 * @property int $coupon_id
 * @property int $redeemer_id
 * @property int $order_id
 * @property int $cart_id
 * @property int $redeemed_at
 * @property float $discounted_amount
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Coupon $coupon
 * @property-read User $redeemer
 * @method static Builder|CouponUsage newModelQuery()
 * @method static Builder|CouponUsage newQuery()
 * @method static Builder|CouponUsage query()
 * @method static Builder|CouponUsage whereCartId($value)
 * @method static Builder|CouponUsage whereCouponId($value)
 * @method static Builder|CouponUsage whereCreatedAt($value)
 * @method static Builder|CouponUsage whereDiscountedAmount($value)
 * @method static Builder|CouponUsage whereId($value)
 * @method static Builder|CouponUsage whereOrderId($value)
 * @method static Builder|CouponUsage whereRedeemedAt($value)
 * @method static Builder|CouponUsage whereRedeemerId($value)
 * @method static Builder|CouponUsage whereUpdatedAt($value)
 * @mixin Eloquent
 */
class CouponUsage extends Model
{

    protected $casts = [
        'redeemed_at' => 'datetime'
    ];

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class, 'coupon_id');
    }

    public function redeemer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'redeemer_id');
    }

    /**
     * @param $totalDiscountedAmount
     * @param  Coupon  $coupon
     * @param  Int  $cartId
     * @param  Int  $userId
     * @param  Int  $orderId
     */
    public static function storeCouponUsage(
        $totalDiscountedAmount,
        Coupon $coupon,
        int $cartId,
        int $userId,
        int $orderId
    ): void {
        $coupon->money_redeemed_so_far += $totalDiscountedAmount;
        $coupon->total_redeemed_count++;
        $coupon->save();

        $couponUsage = new self;
        $couponUsage->coupon_id = $coupon->id;
        $couponUsage->cart_id = $cartId;
        $couponUsage->redeemer_id = $userId;
        $couponUsage->order_id = $orderId;
        $couponUsage->redeemed_at = now();
        $couponUsage->discounted_amount = $totalDiscountedAmount;
        $couponUsage->save();
    }
}
