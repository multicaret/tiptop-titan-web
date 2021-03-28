<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CouponInstance
 *
 * @property int $id
 * @property int $coupon_id
 * @property int $redeemer_id
 * @property int $redeemed_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Coupon $coupon
 * @property-read \App\Models\User $redeemer
 * @method static \Illuminate\Database\Eloquent\Builder|CouponInstance newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CouponInstance newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CouponInstance query()
 * @method static \Illuminate\Database\Eloquent\Builder|CouponInstance whereCouponId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CouponInstance whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CouponInstance whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CouponInstance whereRedeemedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CouponInstance whereRedeemerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CouponInstance whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CouponInstance extends Model
{

    protected $casts = [
        'redeemed_at' => 'timestamp'
    ];

    public function coupon(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Coupon::class, 'coupon_id');
    }

    public function redeemer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'redeemer_id');
    }
}
