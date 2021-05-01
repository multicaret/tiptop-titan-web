<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\CartProductOption;
use App\Models\CartProductOptionSelection;
use App\Models\Product;
use App\Models\ProductOption;
use App\Models\ProductOptionIngredient;
use App\Models\ProductOptionSelection;
use App\Models\Taxonomy;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends BaseApiController
{


    public function groceryAdjustQuantity(Request $request): JsonResponse
    {
        $validationRules = [
            'chain_id' => 'required',
            'branch_id' => 'required',
            'product_id' => 'required',
            'is_adding' => 'required',
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
        $cartProduct = $this->getOrCreateProductCart($cart, $productId, Product::CHANNEL_GROCERY_OBJECT,
            $isAddingMethod);
        if ($isAddingMethod) {
            if ($cartProduct->product->is_storage_tracking_enabled) {
                if ($cartProduct->product->available_quantity <= $cartProduct->quantity) {
                    $errorData = ['availableQuantity' => $cartProduct->product->available_quantity];
                    $errorsMessage = 'The requested product is currently unavailable';

                    return $this->respondValidationFails($errorsMessage, $errorData);
                }
            }
            $cartProduct->increment('quantity');
            $this->updateCartPrices($cartProduct, $cart, 'increment');
        } elseif ( ! is_null($cartProduct) && $cartProduct->quantity > 0) {
            $this->updateCartPrices($cartProduct, $cart, 'decrement');
            $cartProduct->decrement('quantity');
            if ($cartProduct->quantity === 0) {
                $cartProduct->delete();
            }
        }

        $cart->save();

        return $this->respond([
            'cart' => new CartResource($cart),
        ]);
    }


    public function foodAdjustCartData(Request $request)
    {
        $validationRules = [
            'chain_id' => 'required',
            'branch_id' => 'required',
            'product_id' => 'required',
            'quantity' => 'required',
        ];

        $validator = validator()->make($request->all(), $validationRules);
        if ($validator->fails()) {
            return $this->respondValidationFails($validator->errors());
        }

        $cartProductId = $request->input('cart_product_id');
        $selectedOptions = $request->input('selected_options');
        $chainId = $request->input('chain_id');
        $branchId = $request->input('branch_id');
        $productId = $request->input('product_id');
        $requestQuantity = $request->input('quantity');

        \DB::beginTransaction();
        $cart = Cart::retrieve($chainId, $branchId);

        $cartProduct = $this->getOrCreateProductCart($cart, $productId, Product::CHANNEL_FOOD_OBJECT,
            is_null($cartProductId),
            $cartProductId);

        if (is_null($cartProduct)) {
            info('$cartProduct is null', [
                'method' => 'CartController@foodAdjustCartData',
                'cartId' => $cart->id,
                'productId' => $productId,
                'cartProductId' => $cartProductId,
            ]);
        }

        if ($requestQuantity > 0) { // 1 -> 3
            if ( ! is_null($cartProductId)) {
                $this->updateCartPrices($cartProduct, $cart, 'decrement', $cartProduct->quantity);
            }
            $cartProduct->quantity = $requestQuantity;
            $cartProduct->save();

            // Disabled since food products don't storage
            // Checking available quantity
            /*if ($cartProduct->product->is_storage_tracking_enabled) {
                if ($cartProduct->product->available_quantity <= $cartProduct->quantity) {
                    $errorData = ['availableQuantity' => $cartProduct->product->available_quantity];
                    $errorsMessage = 'The requested product is currently unavailable';

                    return $this->respondValidationFails($errorsMessage, $errorData);
                }
            }*/

            $this->resetCartProductOptions($cartProduct);
            if ( ! is_null($selectedOptions)) {
                // Todo: update delete method with delete by ids
                foreach ($selectedOptions as $selectedOption) {
                    $productOption = ProductOption::find($selectedOption['product_option_id']);
                    if (is_null($productOption)) {
                        // A Product option just got deleted from the other side of the world! React to this change!
                        return $this->respondValidationFails([
                            'optionDeleted' => 'Unfortunately, one of the selected option is not available anymore',
                        ]);
                    }
                    $cartProductOption = CartProductOption::firstOrCreate([
                        'cart_product_id' => $cartProduct->id,
                        'product_option_id' => $productOption->id
                    ]);
                    $isBasedOnIngredients = $cartProductOption->productOption->is_based_on_ingredients;

                    foreach ($selectedOption['selected_ids'] as $selectionId) {
                        $selectableType = $isBasedOnIngredients ? Taxonomy::class : ProductOptionSelection::class;
                        $productOptionSelection = $selectableType::find($selectionId);
                        if (is_null($productOptionSelection)) {
                            // A Product option just got deleted from the other side of the world! React to this change!
                            return $this->respondValidationFails([
                                'selectionDeleted' => 'Unfortunately, one of the selections of ('.$productOption->title.') is not available anymore',
                            ]);
                        }

                        CartProductOptionSelection::firstOrCreate([
                            'cart_product_id' => $cartProduct->id,
                            'cart_product_option_id' => $cartProductOption->id,
                            'product_option_id' => $productOption->id,
                            'selectable_type' => $selectableType,
                            'selectable_id' => $productOptionSelection->id,
                        ]);
                        $optionPrice = $this->getOptionPrice($selectableType, $productOptionSelection->id,
                            $productOption->id);
                        $cartProduct->options_price += $optionPrice;
                    }
                }
                // Add total product option prices(aka: $cartProduct->options_price) * quantity
                $cartProduct->total_options_price = $cartProduct->options_price * $cartProduct->quantity;
                $this->updateCartPrices($cartProduct, $cart, 'increment', $cartProduct->quantity);
            }
        } else {
            $this->updateCartPrices($cartProduct, $cart, 'decrement', $cartProduct->quantity);
            $cartProduct->delete();
        }

        $cartProduct->save();
        $cart->save();
        \DB::commit();

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

    public function getOrCreateProductCart(
        Cart $cart,
        $productId,
        $type,
        $isAdding,
        $cartProductId = null
    ): ?CartProduct {
        $cartProduct = null;
        if ($type === Product::CHANNEL_GROCERY_OBJECT) {
            $cartProduct = CartProduct::where('cart_id', $cart->id)
                                      ->where('product_id', $productId)
                                      ->first();
        } elseif ($type === Product::CHANNEL_FOOD_OBJECT && ! is_null($cartProductId)) {
            $cartProduct = CartProduct::find($cartProductId);
        }

        if (is_null($cartProduct) && $isAdding) {
            $cartProductId = CartProduct::insertGetId([
                'cart_id' => $cart->id,
                'product_id' => $productId,
                'product_object' => Product::find($productId),
                'quantity' => 0,
                'options_price' => 0,
                'total_options_price' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $cartProduct = CartProduct::find($cartProductId);
        }

        return $cartProduct;
    }

    private function getOptionPrice(string $selectableModel, $selectionId, $id): int
    {
        $price = 0;
        try {
            if ($selectableModel === Taxonomy::class) {
                $price = ProductOptionIngredient::where('product_option_id', $id)
                                                ->where('ingredient_id', $selectionId)
                                                ->first()->price;
            } elseif ($selectableModel === ProductOptionSelection::class) {
                $price = ProductOptionSelection::find($selectionId)->price;
            }
        } catch (Exception $exception) {
            info($exception->getMessage());
        }

        return $price;
    }

    public function updateCartPrices(CartProduct $cartProduct, Cart $cart, string $action, $quantity = 1): void
    {
        if ($action === 'decrement') {
            $cart->total -= ($quantity * $cartProduct->product->discounted_price);
            $cart->without_discount_total -= ($quantity * $cartProduct->product->price);
            $cart->total -= $cartProduct->total_options_price;
            $cart->without_discount_total -= $cartProduct->total_options_price;
            if ($cartProduct->product->is_storage_tracking_enabled) {
                $cartProduct->product->increment('available_quantity');
            }
        } elseif ($action === 'increment') {
            $cart->total += ($quantity * $cartProduct->product->discounted_price);
            $cart->without_discount_total += ($quantity * $cartProduct->product->price);
            $cart->total += $cartProduct->total_options_price;
            $cart->without_discount_total += $cartProduct->total_options_price;
            if ($cartProduct->product->is_storage_tracking_enabled) {
                $cartProduct->product->decrement('available_quantity');
            }
        }
    }

    public function resetCartProductOptions(CartProduct $cartProduct): void
    {
        $cartProduct->options_price = 0;
        $cartProduct->cartProductOptions()->delete();
        $cartProduct->cartProductOptionsSelections()->delete();
    }

    public function destroyGroceryProduct(Cart $cart, $productId): JsonResponse
    {
        \DB::beginTransaction();
        $cartProduct = CartProduct::whereCartId($cart->id)->where('product_id', $productId)->first();
        $cart->total -= $cartProduct->product->discounted_price;
        $cart->without_discount_total -= $cartProduct->product->price;
        $cart->save();
        $cartProduct->delete();
        \DB::commit();

        return $this->respond([
            'cart' => new CartResource($cart),
        ]);
    }

    public function destroyFoodProduct(Cart $cart, $cartProductId)
    {
        \DB::beginTransaction();
        $cartProduct = CartProduct::find($cartProductId);
        $cart->total -= ($cartProduct->options_price + $cartProduct->product->discounted_price) * $cartProduct->quantity;
        $cart->without_discount_total -= ($cartProduct->options_price + $cartProduct->product->price) * $cartProduct->quantity;
        $cart->save();
        $cartProduct->delete();
        \DB::commit();

        return $this->respond([
            'cart' => new CartResource($cart),
        ]);
    }
}
