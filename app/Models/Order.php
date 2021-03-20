<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{

    use SoftDeletes;

    const STATUS_CANCELLED = 0;
    const STATUS_DRAFT = 1;
    const STATUS_WAITING_COURIER = 6;
    const STATUS_PREPARING = 10;
    const STATUS_ON_THE_WAY = 16;
    const STATUS_AT_THE_ADDRESS = 18;
    const STATUS_DELIVERED = 20;

    protected $casts = [
        'total' => 'float',
        'coupon_discount_amount' => 'float',
        'delivery_fee' => 'float',
        'grand_total' => 'float',
        'private_payment_method_commission' => 'float',
        'private_total' => 'float',
        'private_delivery_fee' => 'float',
        'private_grand_total' => 'float',
        'completed_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($query) {
            $query->reference_code = time();
        });
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function chain(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Chain::class);
    }

    public function branch(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }


    public function cart(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    public function paymentMethod(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function address(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function coupon(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    public function previousOrder(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(self::class, 'previous_order_id');
    }

    public function products(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'order_product', 'order_id', 'product_id')
                    ->withPivot('product_object')
                    ->withTimestamps();
    }


}
