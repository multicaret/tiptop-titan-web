<?php

namespace App\Http\Resources;

use App\Models\Currency;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Basket */
class BasketResource extends JsonResource
{
    /**
     * @param  \Illuminate\Http\Request  $request
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
                'raw' => $this->without_discount_total,
                'formatted' => Currency::format($this->without_discount_total),
            ],
            'status' => $this->status,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,

            'userId' => $this->user_id,
            'chainId' => $this->chain_id,
            'branchId' => $this->branch_id,
            'products' => BasketProductResource::collection($this->basketProducts),
        ];
    }
}
