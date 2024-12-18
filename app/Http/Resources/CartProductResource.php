<?php

namespace App\Http\Resources;

use App\Models\CartProduct;
use App\Models\Currency;
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
            'cartProductId' => $this->id,
            'product' => new ProductResource($this->product),
            'quantity' => $this->quantity,
            'price' => [
                'raw' => (double) $this->options_price + optional($this->product)->discounted_price,
                'formatted' => Currency::format($this->options_price + optional($this->product)->discounted_price),
            ],
            'totalPrice' => [
                'raw' => (double) $this->total_options_price + (optional($this->product)->discounted_price * $this->quantity),
                'formatted' => Currency::format($this->total_options_price + (optional($this->product)->discounted_price * $this->quantity)),
            ],
            'selectedOptions' => CartProductOptionResource::collection($this->cartProductOptions),
        ];
    }
}
