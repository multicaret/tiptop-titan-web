<?php

namespace App\Http\Resources;

use App\Models\Cart;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Cart */
class CartResource extends JsonResource
{
    /**
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'productsCount' => $this->products()->count(),
            'total' => [
                'raw' => $this->total,
                'formatted' => Currency::format($this->total),
            ],
            'withoutDiscountTotal' => [
                'raw' => (double) $this->without_discount_total,
                'formatted' => Currency::format($this->without_discount_total),
            ],
            'status' => $this->status,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,

            'userId' => $this->user_id,
            'chainId' => $this->chain_id,
            'branchId' => $this->branch_id,
            'products' => CartProductResource::collection($this->cartProducts),
            'restaurant' => $this->when(optional($this->branch)->is_food, new FoodBranchResource($this->branch)),
        ];
    }
}
