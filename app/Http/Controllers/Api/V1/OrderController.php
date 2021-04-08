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

        $branchId = $request->input('branch_id');
        $chainId = $request->input('chain_id');
        $userCart = Cart::retrieve($chainId, $branchId, auth()->id());
        $branch = Branch::find($branchId);

        if ($userCart->total < $branch->minimum_order && $branch->under_minimum_order_delivery_fee == 0) {
            $message = trans('api.cart_total_under_minimum');

            return $this->setStatusCode(Response::HTTP_BAD_REQUEST)
                        ->respondWithMessage($message);
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
        $deliveryFeeCalculated = $branch->calculateDeliveryFee($userCart->total);
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
        $freeDeliveryThreshold = $branch->free_delivery_threshold;

        $deliveryFee = 0;
        if ($userCart->total >= $minimumOrder) {
            $deliveryFee = $branch->fixed_delivery_fee + $underMinimumOrderDeliveryFee;
        }

        if ($userCart->total >= $freeDeliveryThreshold) {
            $deliveryFee = 0;
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
        $newOrder->private_total = $newOrder->total;
        $newOrder->private_delivery_fee = $newOrder->delivery_fee;
        $newOrder->private_grand_total = $newOrder->grand_total;
//        $newOrder->private_payment_method_commission = $request->input('private_payment_method_commission');
        $newOrder->notes = $request->input('notes');
        $newOrder->status = Order::STATUS_NEW;
        $newOrder->completed_at = now();
        $newOrder->type = $branch->type;
        $newOrder->save();

        // Todo: work on payment method & do it.
        $cart = Cart::find($newOrder->cart_id);
        $cart->status = Cart::STATUS_COMPLETED;
        $cart->save();

        // Deduct the purchased quantity from the available quantity of each product.
        foreach ($cart->products as $product) {
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


        if ( ! is_null($couponRedeemCode = $request->input('coupon_redeem_code'))) {
            $coupon = Coupon::where('redeem_code', $couponRedeemCode)->first();

            [
                $isExpirationDateAndUsageValid, $validationExpirationAndUsageMessage
            ] = $coupon->validateExpirationDateAndUsageCount();

            [$isAmountValid, $totalDiscountedAmount] = $coupon->validateCouponDiscountAmount($newOrder->total);

            $deliveryFee = $branch->calculateDeliveryFee($newOrder->total);
            if ($coupon->has_free_delivery) {
                $deliveryFee = 0;
            }

            if ($isExpirationDateAndUsageValid && $isAmountValid) {
                $couponDiscountAmount = $userCart->total - $totalDiscountedAmount;
                $newOrder->coupon_id = $coupon->id;
                $newOrder->delivery_fee = $deliveryFee;
                $newOrder->coupon_discount_amount = $couponDiscountAmount;
                $newOrder->grand_total = $userCart->total + $deliveryFee - ($couponDiscountAmount);
                $newOrder->private_delivery_fee = $newOrder->delivery_fee;
                $newOrder->private_grand_total = $newOrder->grand_total;
                $newOrder->save();

                CouponUsage::storeCouponUsage($totalDiscountedAmount, $coupon, $cart->id, $user->id, $newOrder->id);
            }
        }


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


    public function storeRate(Order $order, Request $request): JsonResponse
    {
        $branchRatingValue = $request->input('branch_rating_value');
        if ($order->type === Chain::CHANNEL_GROCERY_OBJECT) {
            $order->rating_issue_id = $request->input('grocery_issue_id');
        }
        if ($order->type === Chain::CHANNEL_FOOD_OBJECT) {
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
