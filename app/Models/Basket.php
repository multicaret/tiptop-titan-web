<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Basket extends Model
{
    const STATUS_IN_PROGRESS = 1;
    const STATUS_COMPLETED = 2;


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

    public function products(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'basket_product', 'basket_id', 'product_id')
                    ->withTimestamps();
    }

    public function retrieveInstance($chainId, $branchId, $userId = null): Basket
    {
        if (is_null($userId)) {
            $userId = auth()->id();
        }

        if (is_null($basket = Basket::where('user_id', $userId)
                                    ->where('branch_id', $branchId)
                                    ->first())) {
            $basket = new Basket();
            $basket->user_id = $userId;
            $basket->branch_id = $branchId;
            $basket->branch_id = $branchId;
            $basket->chain_id = $chainId;
            $basket->save();
        }else{
            // Todo: basket products handling goes here
        }

        return $basket;
    }
}
