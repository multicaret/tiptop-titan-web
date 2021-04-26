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
                'raw' => (double) $this->options_price + $this->product->price,
                'formatted' => Currency::format($this->options_price + $this->product->price),
            ],
            'totalPrice' => [
                'raw' => (double) $this->total_options_price + ($this->product->price * $this->quantity),
                'formatted' => Currency::format($this->total_options_price + ($this->product->price * $this->quantity)),
            ],
            'selectedOptions' => $this->selected_options,
        ];
    }
}
