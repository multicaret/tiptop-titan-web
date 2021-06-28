<?php

namespace App\Http\Controllers\Api\Restaurants\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\OrderMiniResource;
use App\Http\Resources\OrderResource;
use App\Models\Branch;
use App\Models\Order;
use Carbon\Carbon;
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

        $orders = Order::whereBranchId($restaurant->id)
                       ->whereIn('status', $statuses);

        $hasDeliveredOrCancelledStatus = in_array(Order::STATUS_DELIVERED, $statuses) ||
            in_array(Order::STATUS_CANCELLED, $statuses);
        if ($hasDeliveredOrCancelledStatus) {
            $orders = $orders->where(function ($query) {
                $query->where('created_at', Carbon::today())
                      ->where('created_at', Carbon::yesterday(), 'or');
            });
        }
        $orders = $orders->latest()
                         ->get();

        if ($request->has('use_mini_resource')) {
            $ordersCollection = OrderMiniResource::collection($orders);
        } else {
            $ordersCollection = OrderResource::collection($orders);
        }

        return $this->respond([
            'orders' => $ordersCollection,
            'counts' => [
                Order::STATUS_NEW => Order::whereBranchId($restaurant->id)
                                          ->where('status', Order::STATUS_NEW)
                                          ->count(),
                Order::STATUS_PREPARING => Order::whereBranchId($restaurant->id)
                                                ->where('status', Order::STATUS_PREPARING)
                                                ->count(),
                Order::STATUS_WAITING_COURIER => Order::whereBranchId($restaurant->id)
                                                      ->where('status', Order::STATUS_WAITING_COURIER)
                                                      ->count(),
                Order::STATUS_DELIVERED => Order::whereBranchId($restaurant->id)
                                                ->where('status', Order::STATUS_DELIVERED)
                                                ->count(),
                Order::STATUS_CANCELLED => Order::whereBranchId($restaurant->id)
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

        return $this->respond(new OrderResource($order));
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
            'order' => new OrderResource($restaurant)
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
