<?php

namespace App\Http\Resources;

use App\Models\CartProduct;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin CartProduct */
class CartProductResource extends JsonResource
{
    /**
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'productIdInCart' => $this->id,
            'product' => new ProductResource($this->product),
            'quantity' => $this->quantity,
        ];
    }
}
