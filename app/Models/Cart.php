<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Cart extends Model
{

    const STATUS_IN_PROGRESS = 0;
    const STATUS_COMPLETED = 1;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function chain(): BelongsTo
    {
        return $this->belongsTo(Chain::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function cartProducts()
    {
        return $this->hasMany(CartProduct::class, 'cart_id');
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'cart_product', 'cart_id', 'product_id')
                    ->withPivot('quantity')
                    ->withPivot('product_object')
                    ->withTimestamps();
    }

    public static function retrieve($chainId, $branchId, $userId = null, $status = self::STATUS_IN_PROGRESS): Cart
    {
        if (is_null($userId)) {
            $userId = auth()->id();
        }

        if (is_null($cart = Cart::where('user_id', $userId)
                                    ->where('branch_id', $branchId)
                                    ->where('status', $status)
                                    ->first())) {
            $cart = new Cart();
            $cart->chain_id = $chainId;
            $cart->branch_id = $branchId;
            $cart->user_id = $userId;
            $cart->status = $status;
            $cart->save();
        }

        return $cart;
    }
}
