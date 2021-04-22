<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\CartProductOption;
use App\Models\CartProductOptionSelection;
use App\Models\Product;
use App\Models\ProductOptionIngredient;
use App\Models\ProductOptionSelection;
use App\Models\Taxonomy;
use Exception;
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

        $productIdInCart = $request->input('product_id_in_cart');
        $selectedOptions = $request->input('selected_options');
        $chainId = $request->input('chain_id');
        $branchId = $request->input('branch_id');
        $productId = $request->input('product_id');
        $isAddingMethod = $request->input('is_adding');
        $cart = Cart::retrieve($chainId, $branchId);

        $cartProduct = $this->getProductCart($cart, $productId, $productIdInCart);

        if ($isAddingMethod) {
            if ($cartProduct->product->is_storage_tracking_enabled) {
                if ($cartProduct->product->available_quantity <= $cartProduct->quantity) {
                    $errorData = ['availableQuantity' => $cartProduct->product->available_quantity];
                    $errorsMessage = 'The requested product is currently unavailable';

                    return $this->respondValidationFails($errorsMessage, $errorData);
                }
            }
            $cartProduct->increment('quantity');
            if ( ! is_null($selectedOptions)) {
                // Todo: 2 update options if have one
                $cartProduct->price = 0;
                foreach ($selectedOptions as $selectedOption) {
                    $cartProductOption = CartProductOption::query()->firstOrCreate([
                        'cart_product_id' => $cartProduct->id,
                        'product_option_id' => $selectedOption['id']
                    ]);

                    $onIngredients = $cartProductOption->productOption->is_based_on_ingredients;
                    foreach ($selectedOption['selection_ids'] as $selectionId) {
                        $selectableType = $onIngredients ? Taxonomy::class : ProductOptionSelection::class;
                        CartProductOptionSelection::query()->firstOrCreate([
                            'cart_product_id' => $cartProduct->id,
                            'product_option_id' => $selectedOption['id'],
                            'selectable_type' => $selectableType,
                            'selectable_id' => $selectionId,
                        ]);
                        $optionPrice = $this->getOptionPrice($selectableType, $selectionId,
                            $selectedOption['id']);
                        $cartProduct->price += $optionPrice;
                    }
                }
                $cartProduct->total_price = $cartProduct->price * $cartProduct->quantity;
                $this->updateCartPrices($cartProduct, $cart, 'increment');
            }
        } else {
            $cartProduct->decrement('quantity');
            $cartProduct->total_price = $cartProduct->price * $cartProduct->quantity;
            $this->updateCartPrices($cartProduct, $cart, 'decrement');
            if ( ! $cartProduct->quantity) {
                $cartProduct->delete();

                return $this->respondNotFound([]);
            }
        }
        $cartProduct->save();
        $cart->save();


        return $this->respond([
            'cart' => new CartResource($cart),
        ]);
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
            } catch (Exception $e) {
                dd($e->getMessage());
            }

            return $this->respondWithMessage('Successfully Deleted');
        }

        return $this->respondValidationFails('There isn\'t a cart to delete');

    }

    public function getProductCart(Cart $cart, $productId, $productIdInCart): CartProduct
    {
//        $cartProduct = CartProduct::query()->where('cart_id', $cart->id)
//                                  ->where('product_id', $productId)
//                                  ->first();
        if ( is_null($cartProduct = CartProduct::query()->find($productIdInCart))) {
            $productIdInCart = CartProduct::query()->insertGetId([
                'cart_id' => $cart->id,
                'product_id' => $productId,
                'product_object' => Product::find($productId),
                'total_price' => 0,
                'price' => 0,
                'quantity' => 0,
            ]);
            $cartProduct = CartProduct::query()->find($productIdInCart);
        }
        return $cartProduct;
    }

    private function getOptionPrice(string $selectableModel, $selectionId, $id): int
    {
        $price = 0;
        try {
            if ($selectableModel === Taxonomy::class) {
                $price = ProductOptionIngredient::query()
                                                ->where('product_option_id', $id)
                                                ->where('ingredient_id', $selectionId)
                                                ->first()->price;
            } elseif ($selectableModel === ProductOptionSelection::class) {
                $price = ProductOptionSelection::query()->find($selectionId)->price;
            }
        } catch (Exception $exception) {
            info($exception->getMessage());
        }

        return $price;
    }

    public function updateCartPrices(CartProduct $cartProduct, Cart $cart, string $action): void
    {
        if ($action === 'decrement') {
            $cart->total -= $cartProduct->product->discounted_price;
            $cart->without_discount_total -= $cartProduct->product->price;
            $cart->total -= $cartProduct->total_price;
            $cart->without_discount_total -= $cartProduct->total_price;
        } elseif ($action === 'increment') {
            $cart->total += $cartProduct->product->discounted_price;
            $cart->without_discount_total += $cartProduct->product->price;
            $cart->total += $cartProduct->total_price;
            $cart->without_discount_total += $cartProduct->total_price;
        }
    }
}
