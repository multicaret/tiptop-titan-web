<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\OrderResource;
use App\Models\Branch;
use App\Models\Cart;
use App\Models\Chain;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\Currency;
use App\Models\Location;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\Taxonomy;
use DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends BaseApiController
{

    public function index(Request $request)
    {
        $validationRules = [
            'chain_id' => 'required',
        ];

        $validator = validator()->make($request->all(), $validationRules);
        if ($validator->fails()) {
            return $this->respondValidationFails($validator->errors());
        }

        $user = auth()->user();
        $chainId = $request->input('chain_id');

        $previousOrders = Order::whereUserId($user->id)
                               ->whereChainId($chainId)
                               ->whereNotNull('completed_at')
                               ->latest()
                               ->get();
        if ( ! is_null($previousOrders)) {
            return $this->respond(OrderResource::collection($previousOrders));
        }

        return $this->respondNotFound();
    }

    public function destroy($id)
    {
        $order = Order::find($id);

        if (is_null($order)) {
            return $this->respondNotFound();
        } elseif ($order->delete()) {
            return $this->respond([
                'type' => 'success',
                'text' => 'Successfully Deleted',
            ]);
        }

        return $this->respond([
            'type' => 'error',
            'text' => 'There seems to be a problem',
        ]);
    }


    public function create(Request $request): JsonResponse
    {
        $validationRules = [
            'chain_id' => 'required',
            'branch_id' => 'required',
        ];

        $validator = validator()->make($request->all(), $validationRules);
        if ($validator->fails()) {
            return $this->respondValidationFails($validator->errors());
        }

        // TODO: work on it later with Suheyl
        $branchId = $request->input('branch_id');
        $chainId = $request->input('chain_id');
        $userCart = Cart::retrieve($chainId, $branchId, auth()->id());
        $branch = Branch::find($branchId);
        $underMinimumOrderDeliveryFee = $branch->under_minimum_order_delivery_fee;
        $minimumOrder = $branch->minimum_order;
        $deliveryFee = null;
        if ($userCart->total >= $minimumOrder) {
            $deliveryFee = $branch->fixed_delivery_fee;
        } elseif ( ! $underMinimumOrderDeliveryFee) {
            $message = trans('api.cart_total_under_minimum');

            return $this->setStatusCode(Response::HTTP_NOT_ACCEPTABLE)
                        ->respondWithMessage($message);
        } else {
            $deliveryFee = $underMinimumOrderDeliveryFee;
        }


        $paymentMethods = PaymentMethod::active()->get()->map(function ($method) {
            return [
                'id' => $method->id,
                'title' => $method->title,
                'description' => $method->description,
                'instructions' => $method->instructions,
                'logo' => $method->logo,
            ];
        });
        $grandTotal = ! is_null($deliveryFee) ? $deliveryFee + $userCart->total : $userCart->total;
        $response = [
            'paymentMethods' => $paymentMethods,
            'deliveryFee' => [
                'raw' => $deliveryFee,
                'formatted' => Currency::format($deliveryFee),
            ],
            'total' => [
                'raw' => (double) $userCart->total,
                'formatted' => Currency::format($userCart->total),
            ],
            'grandTotal' => [
                'raw' => (double) $grandTotal,
                'formatted' => Currency::format($grandTotal),
            ]
        ];

        return $this->respond($response);
    }


    public function store(Request $request): JsonResponse
    {
        $validationRules = [
            'chain_id' => 'required',
            'branch_id' => 'required',
            'cart_id' => 'required',
            'payment_method_id' => 'required',
            'address_id' => 'required',
        ];

        $validator = validator()->make($request->all(), $validationRules);
        if ($validator->fails()) {
            return $this->respondValidationFails($validator->errors());
        }


        $address = Location::find($request->input('address_id'));
        if (is_null($address)) {
            return $this->respondNotFound('Address not found');
        }

        $user = auth()->user();
        $userCart = Cart::whereId($request->input('cart_id'))->first();
        $branch = $userCart->branch;
        $minimumOrder = $branch->minimum_order;
        $underMinimumOrderDeliveryFee = $branch->under_minimum_order_delivery_fee;

        $deliveryFee = null;
        if ($userCart->total >= $minimumOrder) {
            $deliveryFee = $branch->fixed_delivery_fee;
        } else {
            $deliveryFee = $underMinimumOrderDeliveryFee;
        }

        DB::beginTransaction();
        $newOrder = new Order();
        $newOrder->user_id = auth()->id();
        $newOrder->chain_id = $request->input('chain_id');
        $newOrder->branch_id = $request->input('branch_id');
        $newOrder->cart_id = $request->input('cart_id');
        $newOrder->payment_method_id = $request->input('payment_method_id');
        $newOrder->address_id = $address->id;
        $newOrder->city_id = $address->city_id;
        $newOrder->total = $userCart->total;
        $newOrder->delivery_fee = $deliveryFee;
        $newOrder->grand_total = $userCart->total + $deliveryFee;
//        $newOrder->coupon_discount_amount = $deliveryFee;
        $newOrder->private_total = $newOrder->total;
        $newOrder->private_delivery_fee = $newOrder->delivery_fee;
        $newOrder->private_grand_total = $newOrder->grand_total;
        $newOrder->completed_at = now();
//        $newOrder->private_payment_method_commission = $request->input('private_payment_method_commission');
//        $newOrder->avg_rating = $request->input('avg_rating');
//        $newOrder->rating_count = $request->input('rating_count');
        $newOrder->notes = $request->input('notes');
        $newOrder->status = Order::STATUS_NEW;
        $newOrder->type = $branch->type;
        $newOrder->save();

        // Todo: work on payment method & do it.
        $cart = Cart::find($newOrder->cart_id);
        $cart->status = Cart::STATUS_COMPLETED;
        $cart->save();

        // Deduct the purchased quantity from the available quantity of each product.
        foreach ($cart->products as $product) {
            if ($product->is_storage_tracking_enabled) {
                if ($product->available_quantity != 0) {
                    $product->available_quantity = $product->available_quantity - $product->pivot->quantity;
                    $product->save();
                } else {
                    $productName = $product->title;

                    return $this->respondWithMessage("{$productName} Product Unavailable");
                }
            }
        }

        $user->increment('total_number_of_orders');
        $user->save();


        $cartTotalAfterDiscount = null;
        if ($request->has('coupon_id')) {
            $coupon = Coupon::whereId($request->input('coupon_id'))->first();
            if ( ! is_null($coupon && $coupon->expired_at < now())) {
                if ($coupon->min_cart_value_allowed < $newOrder->grand_total) {
                    if ($coupon->max_usable_count > $coupon->total_redeemed_count) {
                        // Todo: fix
                        $coupon->money_redeemed_so_far += $coupon->discount_amount;
                        $coupon->total_redeemed_count++;
                        $coupon->save();

                        $couponUsage = new CouponUsage;
                        $couponUsage->coupon_id = $coupon->id;
                        $couponUsage->cart_id = $cart->id;
                        $couponUsage->redeemer_id = $user->id;
                        $couponUsage->order_id = $newOrder->id;
                        $couponUsage->redeemed_at = now();
                        // Todo: fix
                        $couponUsage->discounted_amount = $coupon->discount_amount;
                        $couponUsage->save();
                    } else {
                        return $this->respondWithMessage('Max usable count is over');
                    }

                } else {
                    return $this->respondWithMessage('Cart total smaller than minimum cart value allowed');
                }
            } else {
                return $this->respondNotFound('Coupon code is wrong or expired');
            }

            $hasFreeDelivery = $coupon->has_free_delivery;
            $grandTotal = $newOrder->grand_total;
            $couponDiscountAmount = $coupon->discount_amount;
            $couponMaxAllowedDiscountAmount = $coupon->max_allowed_discount_amount;

            if ($hasFreeDelivery && $coupon->discount_by_percentage) {
                // to get discounted amount
                $discountedAmount = $grandTotal - $grandTotal * $couponDiscountAmount / 100;

                if ($discountedAmount > $couponMaxAllowedDiscountAmount) {
                    $cartTotalAfterDiscount = ($grandTotal - $couponMaxAllowedDiscountAmount);
                } else {
                    $cartTotalAfterDiscount = ($couponDiscountAmount / 100) * $grandTotal;
                }
            } elseif ( ! $hasFreeDelivery && $coupon->discount_by_percentage) {
                $discountedAmount = $newOrder->total - $newOrder->total * $couponDiscountAmount / 100;

                if ($discountedAmount > $couponMaxAllowedDiscountAmount) {
                    $cartTotalAfterDiscount = ($newOrder->total - $couponMaxAllowedDiscountAmount) + $newOrder->delivery_fee;
                } else {
                    $cartTotalAfterDiscount = ($couponDiscountAmount / 100) * $newOrder->total + $newOrder->delivery_fee;
                }
            } elseif ($hasFreeDelivery && ! $coupon->discount_by_percentage) {
                if ($couponDiscountAmount > $couponMaxAllowedDiscountAmount) {
                    $cartTotalAfterDiscount = ($grandTotal - $couponMaxAllowedDiscountAmount);
                } else {
                    $cartTotalAfterDiscount = ($grandTotal - $couponDiscountAmount);
                }
            } elseif ( ! $hasFreeDelivery && ! $coupon->discount_by_percentage) {
                if ($couponDiscountAmount > $couponMaxAllowedDiscountAmount) {
                    $cartTotalAfterDiscount = ($newOrder->total - $couponMaxAllowedDiscountAmount) + $newOrder->delivery_fee;
                } else {
                    $cartTotalAfterDiscount = ($newOrder->total - $couponDiscountAmount) + $newOrder->delivery_fee;
                }
            }

        }


        DB::commit();

        return $this->respond(
            [
                'order' => new OrderResource($newOrder),
                'cartTotalAfterDiscount' => $cartTotalAfterDiscount
            ]
        );
    }


    /**
     * @param  Order  $order
     * @param  Request  $request
     * @return JsonResponse
     */
    public function createRate(Order $order, Request $request): JsonResponse
    {
        $response = [];
        if ($order->type === Order::TYPE_GROCERY_OBJECT) {
            $response = [
                'availableIssues' => $this->getIssuesLists(),
            ];
        } elseif ($order->type === Order::TYPE_FOOD_OBJECT) {
            $response = [
                ['key' => 'has_good_food_quality_rating', 'label' => 'Good Food Quality'],
                ['key' => 'has_good_packaging_quality_rating', 'label' => 'Good Packaging Quality'],
                ['key' => 'has_good_order_accuracy_rating', 'label' => 'Good Order Accuracy'],
            ];
        }

        return $this->respond($response);
    }


    public function storeRate(Order $order, Request $request): JsonResponse
    {
        $branchRatingValue = $request->input('branch_rating_value');
        if ($order->type === Chain::TYPE_GROCERY_OBJECT) {
            $order->rating_issue_id = $request->input('grocery_issue_id');
        }
        if ($order->type === Chain::TYPE_FOOD_OBJECT) {
            $driverRatingValue = $request->input('driver_rating_value');
            $order->driver_rating_value = $driverRatingValue;
            $order->has_good_food_quality_rating = $request->input('food_rating_factors.has_good_food_quality_rating');
            $order->has_good_packaging_quality_rating = $request->input('food_rating_factors.has_good_packaging_quality_rating');
            $order->has_good_order_accuracy_rating = $request->input('food_rating_factors.has_good_order_accuracy_rating');
            /*
             * Todo: Remember to increase Driver avg rating
            $driver->avg_rating = $driver->average_rating;
            $driver->increment('rating_count');
            $driver->save();
            */
        }

        DB::beginTransaction();
        $order->rating_comment = $request->input('comment');
        $order->branch_rating_value = $branchRatingValue;
        $order->rated_at = now();
        $order->save();

        $branch = Branch::find($order->branch_id);
        auth()->user()->rate($branch, $branchRatingValue);

        $branch->avg_rating = $branch->average_rating;
        $branch->increment('rating_count');
        $branch->save();
        DB::commit();

        return $this->respondWithMessage(trans('strings.successfully_done'));
    }

    private function getIssuesLists()
    {
        return Taxonomy::ratingIssues()->get()->map(function ($item) {
            return ['id' => $item->id, 'title' => $item->getTranslation()->title];
        });
    }


}
