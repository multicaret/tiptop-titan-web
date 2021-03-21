<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\OrderIndexResource;
use App\Http\Resources\OrderShowResource;
use App\Models\Branch;
use App\Models\Cart;
use App\Models\Order;
use App\Models\PaymentMethod;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends BaseApiController
{

    public function index(Request $request)
    {
        $previousOrders = auth()->user()->order->whereNotNull('completed_at');
        if ( ! is_null($previousOrders)) {
            return OrderIndexResource::collection($previousOrders);
        }

        return $this->respondNotFound();
    }

    public function show($id)
    {
        $order = Order::find($id);
        if ( ! is_null($order)) {
            return new OrderShowResource($order);

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


    public function checkoutCreate(Request $request): JsonResponse
    {
        $validationData = [
            "chain_id" => 'required',
            "branch_id" => 'required',
        ];
        $request->validate($validationData);

        $branchId = $request->input('branch_id');
        $chainId = $request->input('chain_id');
        $userCart = Cart::retrieve($chainId, $branchId, auth()->id());
        $branch = Branch::find($branchId);
        $underMinimumOrderDeliveryFee = $branch->under_minimum_order_delivery_fee;
        $minimumOrder = $branch->minimum_order;
        $deliveryFee = null;
        if ($userCart->total >= $minimumOrder) {
            $deliveryFee = $branch->fixed_delivery_fee;
        } else {
            $deliveryFee = $underMinimumOrderDeliveryFee;
        }

        $paymentMethods = PaymentMethod::all()->map(function ($method) {
            return [
                'title' => $method->title,
                'description' => $method->description,
                'instructions' => $method->instructions,
                'logo' => $method->logo,
            ];
        });
        $grandTotal = !is_null($deliveryFee) ? $deliveryFee + $userCart->total : $userCart->total;
        $response = [
            'paymentMethods' => $paymentMethods,
            'deliveryFee' => $deliveryFee,
            'total' => $userCart->total,
            'grandTotal' => $grandTotal
        ];

        return $this->respond($response);
    }


    public function checkoutStore(Request $request): JsonResponse
    {
        $validationData = [
            "chain_id" => 'required',
            "branch_id" => 'required',
            "cart_id" => 'required',
            "payment_method_id" => 'required',
            "address_id" => 'required',
        ];
        $request->validate($validationData);

        $newOrder = new Order();
        $newOrder->user_id = $request->input('user_id');
        $newOrder->chain_id = $request->input('chain_id');
        $newOrder->branch_id = $request->input('branch_id');
        $newOrder->cart_id = $request->input('cart_id');
        $newOrder->payment_method_id = $request->input('payment_method_id');
        $newOrder->address_id = $request->input('address_id');
        $newOrder->previous_order_id = $request->input('previous_order_id');
        $newOrder->total = $request->input('total');
        $newOrder->coupon_discount_amount = $request->input('coupon_discount_amount');
        $newOrder->delivery_fee = $request->input('delivery_fee');
        $newOrder->grand_total = $request->input('grand_total');
        $newOrder->private_payment_method_commission = $request->input('private_payment_method_commission');
        $newOrder->private_total = $request->input('private_total');
        $newOrder->private_delivery_fee = $request->input('private_delivery_fee');
        $newOrder->private_grand_total = $request->input('private_grand_total');
        $newOrder->avg_rating = $request->input('avg_rating');
        $newOrder->rating_count = $request->input('rating_count');
        $newOrder->completed_at = $request->input('completed_at');
        $newOrder->notes = $request->input('notes');
        $newOrder->status = $request->input('status');
        $newOrder->save();

        // Todo: work on payment method & do it.
        $cart = Cart::find($newOrder->cart_id);
        $cart->status = Cart::STATUS_COMPLETED;
        $cart->save();

        $response = [
            'order' => $newOrder
        ];

        return $this->respond($response);
    }


}
