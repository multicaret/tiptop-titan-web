<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
