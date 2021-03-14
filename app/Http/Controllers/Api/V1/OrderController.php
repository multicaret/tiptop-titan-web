<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\OrderIndexResource;
use App\Models\Basket;
use App\Models\Order;
use App\Models\PaymentMethod;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends BaseApiController
{

    public function index(Request $request)
    {
        $previousOrders = auth()->user()->order->whereNotNull('completed_at');
        if (!is_null($previousOrders)) {
            return OrderIndexResource::collection($previousOrders);
        }

        return $this->respondNotFound();
    }

    public function show(Order $order)
    {
        //
    }

    public function destroy(Order $order)
    {
        //
    }


    public function checkoutCreate(): JsonResponse
    {
        $paymentMethods = PaymentMethod::all()->map(function ($method) {
            return [
                'title' => $method->title,
                'description' => $method->description,
                'instructions' => $method->instructions,
                'logo' => $method->logo,
            ];
        });
        $response = [
            'paymentMethods' => $paymentMethods
        ];

        return $this->respond($response);
    }


    public function checkoutStore(Request $request): JsonResponse
    {
        $validationData = [
            "chain_id" => 'required',
            "branch_id" => 'required',
            "basket_id" => 'required',
            "payment_method_id" => 'required',
            "address_id" => 'required',
        ];
        $request->validate($validationData);

        $newOrder = new Order();
        $newOrder->chain_id = $request->input('chain_id');
        $newOrder->branch_id = $request->input('branch_id');
        $newOrder->basket_id = $request->input('basket_id');
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
        $basket = Basket::find($newOrder->basket_id);
        $basket->status = Basket::STATUS_COMPLETED;
        $basket->save();

        $response = [
            'order' => $newOrder
        ];
        return $this->respond($response);
    }


}
