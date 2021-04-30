<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\CartProductOption */
class CartProductOptionResource extends JsonResource
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $isProductOptionBasedOnIngredients = $this->productOption->is_based_on_ingredients;
        if ($isProductOptionBasedOnIngredients) {
            $selectedOptions = $this->ingredients()->pluck('selectable_id')->all();
        } else {
            $selectedOptions = $this->selections()->pluck('selectable_id')->all();
        }

        return [
            'id' => $this->id,
            'cartProductId' => $this->cart_product_id,
            'productOptionId' => $this->product_option_id,
            'selectedOptions' => $selectedOptions,
        ];
    }
}
