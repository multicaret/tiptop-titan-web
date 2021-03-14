<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\OrderIndexResource;
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


}
