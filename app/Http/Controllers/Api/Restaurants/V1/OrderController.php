<?php

namespace App\Http\Controllers\Api\Restaurants\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\OrderRestaurantResource;
use App\Models\Branch;
use App\Models\Order;
use DB;
use Illuminate\Http\Request;

class OrderController extends BaseApiController
{

    public function index(Request $request, $restaurant)
    {
        /*$validationRules = [
        ];

        $validator = validator()->make($request->all(), $validationRules);
        if ($validator->fails()) {
            return $this->respondValidationFails($validator->errors());
        }*/

        $restaurant = Branch::find($restaurant);

        if (is_null($restaurant)) {
            return $this->respondNotFound();
        }

        if (str_contains($request->status, ',')) {
            $statuses = explode(',', $request->status);
        } else {
            $statuses = [$request->status];
        }

        $orders = Order::foods()
                       ->whereBranchId($restaurant->id)
                       ->whereIn('status', $statuses)
                       ->latest()
                       ->get();

        return $this->respond([
            'orders' => OrderRestaurantResource::collection($orders),
            'counts' => [
                Order::STATUS_NEW => Order::foods()
                                          ->whereBranchId($restaurant->id)
                                          ->where('status', Order::STATUS_NEW)
                                          ->count(),
                Order::STATUS_PREPARING => Order::foods()
                                                ->whereBranchId($restaurant->id)
                                                ->where('status', Order::STATUS_PREPARING)
                                                ->count(),
                Order::STATUS_WAITING_COURIER => Order::foods()
                                                      ->whereBranchId($restaurant->id)
                                                      ->where('status', Order::STATUS_WAITING_COURIER)
                                                      ->count(),
                Order::STATUS_DELIVERED => Order::foods()
                                                ->whereBranchId($restaurant->id)
                                                ->where('status', Order::STATUS_DELIVERED)
                                                ->count(),
                Order::STATUS_CANCELLED => Order::foods()
                                                ->whereBranchId($restaurant->id)
                                                ->where('status', Order::STATUS_CANCELLED)
                                                ->count(),
            ]
        ]);
    }


    public function show($restaurant, Order $order)
    {

        if (is_null($restaurant) || is_null($order)) {
            return $this->respondNotFound();
        }

        return $this->respond(new OrderRestaurantResource($order));
    }

    public function update(Request $request, $restaurant, $order)
    {
        $rules = [
            'status' => 'required',
        ];

        $validator = validator()->make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->respondValidationFails($validator->errors());
        }

        $restaurant = Branch::find($restaurant);
        $order = Order::find($order);

        if (is_null($restaurant) || is_null($order) || $order->branch_id != $restaurant->id) {
            return $this->respondNotFound();
        }

        DB::beginTransaction();
        $order->status = $request->status;
        $order->save();

        DB::commit();

        return $this->respond([
            'success' => true,
            'message' => 'Successfully Updated',
        ]);

        /*return $this->respond([
            'order' => new OrderRestaurantResource($restaurant)
        ]);*/
    }


    /*    public function destroy($id)
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
        }*/
}
