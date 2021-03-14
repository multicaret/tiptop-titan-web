<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Basket extends Model
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

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'basket_product', 'basket_id', 'product_id')
                    ->withPivot('quantity')
                    ->withPivot('product_object')
                    ->withTimestamps();
    }

    public function retrieve($chainId, $branchId, $userId = null, $status = self::STATUS_IN_PROGRESS): Basket
    {
        if (is_null($userId)) {
            $userId = auth()->id();
        }

        if (is_null($basket = Basket::where('user_id', $userId)
                                    ->where('branch_id', $branchId)
                                    ->where('status', $status)
                                    ->first())) {
            $basket = new Basket();
            $basket->chain_id = $chainId;
            $basket->branch_id = $branchId;
            $basket->user_id = $userId;
            $basket->save();
        }

        return $basket;
    }
}
