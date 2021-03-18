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

        if ( ! is_null($basketProduct = BasketProduct::where('basket_id', $basket->id)
                                                     ->where('product_id', $request->input('product_id'))
                                                     ->first())) {
            if ($request->input('is_adding') == true) {
                if ($basketProduct->product->is_storage_tracking_enabled) {
                    if ($basketProduct->product->available_quantity > $basketProduct->quantity) {
                        $basketProduct->increment('quantity');
                    } else {
                        return $this->respondValidationFails('The requested product is currently unavailable');
                    }
                } else {
                    $basketProduct->increment('quantity');
                }

            } else {
                if ($basketProduct->quantity == 1) {
                    $delete = $basketProduct->delete();
                } else {
                    $basketProduct->decrement('quantity');
                }
            }
        } elseif ($request->input('is_adding') == true) {
            $basketProduct = new BasketProduct();
            $basketProduct->basket_id = $basket->id;
            $basketProduct->product_id = $request->input('product_id');
            $basketProduct->quantity = 1;
            $basketProduct->save();
        }
        if ( ! is_null($basketProduct)) {
            $quantity = isset($delete) && ! ! $delete ? 0 : $basketProduct->quantity;
        }
        $basket->products_count = $basket->products()->count();

        $basket->total += $basketProduct->product->getDiscountedPrice();
        $basket->without_discount_total += $basketProduct->product->price;
        $basket->save();

        if (isset($quantity)) {
            return $this->respond([
                'basket' => new BasketResource($basket),
            ]);
        }

        return $this->respondNotFound([]);
    }

    public function clearBasket(Request $request)
    {
        $basket = Basket::whereUserId(auth()->id())->whereStatus(Basket::STATUS_IN_PROGRESS)->first();
        if ( ! is_null($basket)) {
            $basket->products()->delete();

            $basket->delete();

            return $this->respond([
                'type' => 'success',
                'text' => 'Successfully Deleted',
            ]);
        }

        return $this->respondValidationFails('There isn\'t a basket to delete');

    }
}
