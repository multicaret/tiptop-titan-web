<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Models\CartProduct;
use Illuminate\Http\Request;

class CartController extends BaseApiController
{
    public function adjustQuantity(Request $request)
    {
        $validationRules = [
            'chain_id' => 'required',
            'branch_id' => 'required',
            'product_id' => 'required',
        ];

        $validator = validator()->make($request->all(), $validationRules);
        if ($validator->fails()) {
            return $this->respondValidationFails($validator->errors());
        }

        $chainId = $request->input('chain_id');
        $branchId = $request->input('branch_id');
        $productId = $request->input('product_id');
        $isAddingMethod = $request->input('is_adding');
        $cart = Cart::retrieve($chainId, $branchId);

        if ( ! is_null($cartProduct = CartProduct::where('cart_id', $cart->id)
                                                 ->where('product_id', $productId)
                                                 ->first())) {
            if ($isAddingMethod == true) {
                if ($cartProduct->product->is_storage_tracking_enabled) {
                    if ($cartProduct->product->available_quantity > $cartProduct->quantity) {
                        $cartProduct->increment('quantity');
                    } else {
                        return $this->respondValidationFails(
                            'The requested product is currently unavailable',
                            [
                                'availableQuantity' => $cartProduct->product->available_quantity
                            ],
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
        } elseif ($isAddingMethod == true) {
            $cartProduct = new CartProduct();
            $cartProduct->cart_id = $cart->id;
            $cartProduct->product_id = $productId;
            $cartProduct->quantity = 1;
            $cartProduct->save();
        }
        if ( ! is_null($cartProduct)) {
            $quantity = isset($delete) && ! ! $delete ? 0 : $cartProduct->quantity;
            if ($isAddingMethod == true) {
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

    public function destroy(Request $request)
    {
        $chainId = $request->input('chain_id');
        $branchId = $request->input('branch_id');
        $cart = Cart::whereChainId($chainId)
                    ->whereBranchId($branchId)
                    ->whereUserId(auth()->id())
                    ->whereStatus(Cart::STATUS_IN_PROGRESS)
                    ->first();

        if ( ! is_null($cart)) {
            try {
                $cart->delete();
            } catch (\Exception $e) {
                dd($e->getMessage());
            }

            return $this->respondWithMessage('Successfully Deleted');
        }

        return $this->respondValidationFails('There isn\'t a cart to delete');

    }
}
