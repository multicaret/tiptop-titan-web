<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Models\CartProduct;
use Illuminate\Http\Request;

class CartController extends BaseApiController
{
    public function addRemoveProduct(Request $request)
    {
        $cart = Cart::retrieve($request->input('chain_id'), $request->input('branch_id'));

        if ( ! is_null($cartProduct = CartProduct::where('cart_id', $cart->id)
                                                     ->where('product_id', $request->input('product_id'))
                                                     ->first())) {
            if ($request->input('is_adding') == true) {
                if ($cartProduct->product->is_storage_tracking_enabled) {
                    if ($cartProduct->product->available_quantity > $cartProduct->quantity) {
                        $cartProduct->increment('quantity');
                    } else {
                        return $this->respondValidationFails(
                            'The requested product is currently unavailable',
                            ['availableQuantity' => $cartProduct->product->available_quantity],
                        );
                    }
                } else {
                    $cartProduct->increment('quantity');
                }

            } else {
                if ($cartProduct->quantity == 1) {
                    $delete = $cartProduct->delete();
                } else {
                    $cartProduct->decrement('quantity');
                }
            }
        } elseif ($request->input('is_adding') == true) {
            $cartProduct = new CartProduct();
            $cartProduct->cart_id = $cart->id;
            $cartProduct->product_id = $request->input('product_id');
            $cartProduct->quantity = 1;
            $cartProduct->save();
        }
        if ( ! is_null($cartProduct)) {
            $quantity = isset($delete) && ! ! $delete ? 0 : $cartProduct->quantity;
            if ($request->input('is_adding') == true) {
                $cart->total += $cartProduct->product->discounted_price;
                $cart->without_discount_total += $cartProduct->product->price;
            } else {
                $cart->total -= $cartProduct->product->discounted_price;
                $cart->without_discount_total -= $cartProduct->product->price;
            }
        }

        $cart->save();

        if (isset($quantity)) {
            return $this->respond([
                'cart' => new CartResource($cart),
            ]);
        }

        return $this->respondNotFound([]);
    }

    public function clearCart(Request $request)
    {
        $cart = Cart::whereUserId(auth()->id())->whereStatus(Cart::STATUS_IN_PROGRESS)->first();
        if ( ! is_null($cart)) {
            $cart->products()->delete();

            $cart->delete();

            return $this->respond([
                'type' => 'success',
                'text' => 'Successfully Deleted',
            ]);
        }

        return $this->respondValidationFails('There isn\'t a cart to delete');

    }
}
