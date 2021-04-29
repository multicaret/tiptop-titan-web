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
use App\Models\User;
use DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends BaseApiController
{

    public function indexGrocery(Request $request)
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

        $previousOrders = Order::groceries()
                               ->whereUserId($user->id)
                               ->whereChainId($chainId)
                               ->whereNotNull('completed_at')
                               ->latest()
                               ->get();
        if ( ! is_null($previousOrders)) {
            return $this->respond(OrderResource::collection($previousOrders));
        }

        return $this->respondNotFound();
    }


    public function indexFood(Request $request)
    {
        /*$validationRules = [
        ];

        $validator = validator()->make($request->all(), $validationRules);
        if ($validator->fails()) {
            return $this->respondValidationFails($validator->errors());
        }*/

        $user = auth()->user();
        $previousOrders = Order::foods()
                               ->whereUserId($user->id)
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
            'selected_address_id' => 'required',
        ];

        $validator = validator()->make($request->all(), $validationRules);
        if ($validator->fails()) {
            return $this->respondValidationFails($validator->errors());
        }

        $branchId = $request->input('branch_id');
        $chainId = $request->input('chain_id');
        $userCart = Cart::retrieve($chainId, $branchId, auth()->id());
        $branch = Branch::find($branchId);

//        if ($userCart->total < $branch->minimum_order && $branch->under_minimum_order_delivery_fee == 0) {
//            $message = trans('api.cart_total_under_minimum');
//
//            return $this->setStatusCode(Response::HTTP_BAD_REQUEST)
//                        ->respondWithMessage($message);
//        }


        $paymentMethods = PaymentMethod::active()->get()->map(function ($method) {
            return [
                'id' => $method->id,
                'title' => $method->title,
                'description' => $method->description,
                'instructions' => $method->instructions,
                'logo' => $method->logo,
            ];
        });

        $deliveryFeeCalculated = $branch->calculateDeliveryFee($userCart->total, $branch->has_tip_top_delivery, false,
            $request->input('selected_address_id'));
        $grandTotal = $deliveryFeeCalculated + $userCart->total;

        return $this->respond([
            'paymentMethods' => $paymentMethods,
            'deliveryFee' => [
                'raw' => $deliveryFeeCalculated,
                'formatted' => Currency::format($deliveryFeeCalculated),
            ],
            'total' => [
                'raw' => (double) $userCart->total,
                'formatted' => Currency::format($userCart->total),
            ],
            'grandTotal' => [
                'raw' => (double) $grandTotal,
                'formatted' => Currency::format($grandTotal),
            ]
        ]);
    }


    public function store(Request $request): JsonResponse
    {
        $validationRules = [
            'chain_id' => 'required',
            'branch_id' => 'required',
            'cart_id' => 'required',
            'payment_method_id' => 'required',
            'selected_address_id' => 'required',
        ];

        $user = auth()->user();
        $activeCart = Cart::with('branch')
                          ->whereId($request->input('cart_id'))
                          ->first();
        $branch = $activeCart->branch;

        if ($branch->type == Branch::CHANNEL_FOOD_OBJECT) {
            $validationRules['delivery_type'] = 'required';
        }

        $validator = validator()->make($request->all(), $validationRules);
        if ($validator->fails()) {
            return $this->respondValidationFails($validator->errors());
        }

        $address = Location::find($request->input('selected_address_id'));
        if (is_null($address)) {
            return $this->respondNotFound('Address not found');
        }

        $isDeliveryTypeTipTop = $request->input('delivery_type', 'tiptop') == 'tiptop';

        $validateCartValue = $branch->validateCartValue($activeCart->total, $isDeliveryTypeTipTop);
        if ( ! $validateCartValue['isValid']) {
            return $this->respondValidationFails(['cart_value' => $validateCartValue['message']]);
        }

        DB::beginTransaction();
        $newOrder = new Order();
        $newOrder->is_delivery_by_tiptop = $isDeliveryTypeTipTop;
        $newOrder->user_id = $user->id;
        $newOrder->chain_id = $request->input('chain_id');
        $newOrder->branch_id = $request->input('branch_id');
        $newOrder->cart_id = $request->input('cart_id');
        $newOrder->payment_method_id = $request->input('payment_method_id');
        $newOrder->address_id = $address->id;
        $newOrder->city_id = $address->city_id;
        $newOrder->customer_notes = $request->input('notes');
        $newOrder->type = $branch->type;
        $newOrder->save();

        // Todo: work on payment method & do it.
        $activeCart->status = Cart::STATUS_COMPLETED;
        $activeCart->save();

        // Deduct the purchased quantity from the available quantity of each product.
        foreach ($activeCart->products as $product) {
            if ($product->is_storage_tracking_enabled) {
                if ($product->available_quantity > 0) {
                    $newAvailableQuantity = $product->available_quantity - $product->pivot->quantity;
                    if ($newAvailableQuantity >= 0) {
                        $product->available_quantity = $newAvailableQuantity;
                    }
                    $product->save();
                } else {
                    $productName = $product->title;

                    return $this->respondWithMessage("{$productName} Product Unavailable");
                }
            }
        }


        $hasFreeDeliveryCoupon = false;
        $couponDiscountAmount = 0;
        if ( ! is_null($couponRedeemCode = $request->input('coupon_redeem_code'))) {
            $coupon = Coupon::where('redeem_code', $couponRedeemCode)->first();
            if (is_null($coupon)) {
                return $this->respondWithMessage("There is no such coupon code($couponRedeemCode)");
            }

            [
                $isExpirationDateAndUsageValid, $validationExpirationAndUsageMessage
            ] = $coupon->validateExpirationDateAndUsageCount();

            [$isAmountValid, $totalDiscountedAmount] = $coupon->validateCouponDiscountAmount($newOrder->total);

            $hasFreeDeliveryCoupon = $coupon->has_free_delivery;

            if ($isExpirationDateAndUsageValid && $isAmountValid) {
                $couponDiscountAmount = $activeCart->total - $totalDiscountedAmount;
                $newOrder->coupon_id = $coupon->id;

                CouponUsage::storeCouponUsage($totalDiscountedAmount, $coupon, $activeCart->id, $user->id,
                    $newOrder->id);
            }
        }

        $deliveryFee = $branch->calculateDeliveryFee($newOrder->total, $isDeliveryTypeTipTop, $hasFreeDeliveryCoupon,
            $address->id);
        $grandTotal = $activeCart->total + $deliveryFee - ($couponDiscountAmount);

        $newOrder->coupon_discount_amount = $couponDiscountAmount;
        $newOrder->delivery_fee = $deliveryFee;
        $newOrder->total = $activeCart->total;
        $newOrder->grand_total = $grandTotal;
        $newOrder->private_total = $newOrder->total;
        $newOrder->private_delivery_fee = $newOrder->delivery_fee;
        $newOrder->private_grand_total = $newOrder->grand_total;
        $newOrder->status = Order::STATUS_NEW;
        $newOrder->completed_at = now();
        $newOrder->save();

        $user->increment('total_number_of_orders');
        $user->save();

        DB::commit();

        return $this->respond(new OrderResource($newOrder));
    }


    /**
     * @param  Order  $order
     * @param  Request  $request
     * @return JsonResponse
     */
    public function createRate(Order $order, Request $request): JsonResponse
    {
        $response = [];
        if ($order->type === Order::CHANNEL_GROCERY_OBJECT) {
            $response = [
                'availableIssues' => $this->getIssuesLists(),
            ];
        } elseif ($order->type === Order::CHANNEL_FOOD_OBJECT) {
            $response = [
                ['key' => 'has_good_food_quality_rating', 'label' => 'Good Food Quality'],
                ['key' => 'has_good_packaging_quality_rating', 'label' => 'Good Packaging Quality'],
                ['key' => 'has_good_order_accuracy_rating', 'label' => 'Good Order Accuracy'],
            ];
        }

        return $this->respond($response);
    }

    public function storeDriverRate(Order $order, User $driver, Request $request): JsonResponse
    {
        if ($order->status != Order::STATUS_DELIVERED) {
            return $this->respondWithMessage(trans('strings.Can not rate a not delivered order'));
        }

        DB::beginTransaction();
        $driverRatingValue = $request->input('driver_rating_value');
        $order->driver_rating_value = $driverRatingValue;
//      Todo: Remember to increase Driver avg rating
        $driver->avg_rating = $driver->average_rating;
        $driver->increment('rating_count');
        $driver->save();

        $order->driver_rating_comment = $request->input('comment');
        $order->driver_rating_value = $driverRatingValue;
        $order->driver_rated_at = now();
        $order->save();

        auth()->user()->rate($driver, $driverRatingValue);

//        todo: calculate driver's average rating properly
//        $driver->avg_rating = $driver->average_rating;
        $driver->increment('rating_count');
        $driver->save();
        DB::commit();

        return $this->respondWithMessage(trans('strings.successfully_done'));
    }

    public function storeRate(Order $order, Request $request): JsonResponse
    {
        if ($order->status != Order::STATUS_DELIVERED) {
            return $this->respondWithMessage(trans('strings.Can not rate a not delivered order'));
        }
        DB::beginTransaction();

        $branchRatingValue = $request->input('branch_rating_value');
        if (is_null($branchRatingValue)) {
            return $this->respondValidationFails([
                'rating_value' => 'Rating value must not be null'
            ]);
        }
        if ($order->type === Chain::CHANNEL_GROCERY_OBJECT) {
            $order->rating_issue_id = $request->input('grocery_issue_id');
        }
        if ($order->type === Chain::CHANNEL_FOOD_OBJECT) {
            $order->has_good_food_quality_rating = $request->input('food_rating_factors.has_good_food_quality_rating');
            $order->has_good_packaging_quality_rating = $request->input('food_rating_factors.has_good_packaging_quality_rating');
            $order->has_good_order_accuracy_rating = $request->input('food_rating_factors.has_good_order_accuracy_rating');
        }

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

    /**
     * @param $branch
     * @param $userCart
     * @return int
     */
    private function foo($branch, $userCart, $deliveryType): int
    {
        $minimumOrder = $branch->minimum_order;
        $underMinimumOrderDeliveryFee = $branch->under_minimum_order_delivery_fee;
        $freeDeliveryThreshold = $branch->free_delivery_threshold;

        $deliveryFee = 0;
        if ($userCart->total >= $minimumOrder) {
            $deliveryFee = $branch->fixed_delivery_fee + $underMinimumOrderDeliveryFee;
        }

        if ($userCart->total >= $freeDeliveryThreshold) {
            $deliveryFee = 0;
        }

        return $deliveryFee;
    }


}
