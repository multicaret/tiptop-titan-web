<?php

namespace App\Http\Resources;

use App\Models\CartProductOption;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin CartProductOption */
class CartProductOptionResource extends JsonResource
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'cartProductId' => $this->cart_product_id,
            'productOptionId' => $this->product_option_id,
            'selectionIds' => $this->selections()->pluck('selectable_id')->toArray(),
        ];
    }
}
