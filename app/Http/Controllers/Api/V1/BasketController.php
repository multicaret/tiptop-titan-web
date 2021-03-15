<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\BasketResource;
use App\Models\Basket;
use App\Models\BasketProduct;
use Illuminate\Http\Request;

class BasketController extends BaseApiController
{
    public function addRemoveProduct(Request $request)
    {
        $basket = Basket::retrieve($request->input('chain_id'), $request->input('branch_id'));

        if (is_null($basketProduct = BasketProduct::where('basket_id', $basket->id)
                                                  ->where('product_id', $request->input('product_id'))
                                                  ->first())) {
            $basketProduct = new BasketProduct();
            $basketProduct->basket_id = $basket->id;
            $basketProduct->product_id = $request->input('product_id');
            $basketProduct->quantity = 1;
            $basketProduct->save();
        } else {
            if ($request->input('is_adding') == true) {
                $basketProduct->increment('quantity');
            } else {
                if ($basketProduct->quantity == 1) {
                    $basketProduct->delete();
                } else {
                    $basketProduct->decrement('quantity');
                }
            }
            $basketProduct->save();
        }

        $basket->products_count = $basket->products()->count();
        $basket->save();


        return $this->respond([
            'basket' => new BasketResource($basket),
            'quantity' => $basketProduct->quantity,
        ]);
    }

}
