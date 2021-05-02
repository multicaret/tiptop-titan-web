<?php

namespace App\Http\Controllers\Tookan;


use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class TookanController extends Controller
{

    //pickups
    public function pickupSuccessWebHook(Request $request)
    {
    }

    public function pickupFailWebHook(Request $request)
    {

    }

    public function pickupRequestReceived(Request $request)
    {

    }

    public function pickupAgentArrived(Request $request)
    {

    }

    //deliveries
    public function deliverySuccessWebHook(Request $request)
    {

        if (empty($request->order_id)) {
            return;
        }

        $order = Order::where('reference_code', $request->order_id)->firstOrFail();

        if ( ! in_array($order->status,
            [Order::STATUS_ON_THE_WAY, Order::STATUS_PREPARING, Order::STATUS_AT_THE_ADDRESS])) {
            return;
        }

        $order->status = Order::STATUS_DELIVERED;

        $order->save();


    }

    public function deliveryFailWebHook(Request $request)
    {
        //    info('deliveryFailWebHook');

        return;


    }

    public function deliveryRequestReceived(Request $request)
    {
        //    info('deliveryRequestReceived');

        return;
    }

    public function deliveryAgentStarted(Request $request)
    {
        //   info('deliveryAgentStarted');

        if (empty($request->job_id) || empty($request->order_id) || empty($request->fleet_id)) {
            return;
        }

        $order = Order::where('reference_code', $request->order_id)->firstOrFail();

        if ( ! in_array($order->status, [Order::STATUS_PREPARING, Order::STATUS_WAITING_COURIER])) {
            return;
        }

        $order->status = Order::STATUS_ON_THE_WAY;
        $order->save();


    }

    public function deliveryAgentArrived(Request $request)
    {
        // info('deliveryAgentArrived', ['Request Body', $request->all()]);
        $order = Order::where('reference_code', $request->order_id)->firstOrFail();

        if ( ! in_array($order->status,
            [Order::STATUS_PREPARING, Order::STATUS_WAITING_COURIER, Order::STATUS_ON_THE_WAY])) {
            return;
        }

        $order->status = Order::STATUS_AT_THE_ADDRESS;

        $order->save();
    }

    public function driverAssigned(Request $request)
    {
        //  info('driverAssigned', ['Request Body', $request->all()]);
        if (empty($request->job_id) || empty($request->order_id) || empty($request->fleet_id)) {
            return;
        }

        $order = Order::where('reference_code', $request->order_id)->firstOrFail();
        $driver = User::where('tookan_id', $request->fleet_id)->firstOrFail();

        $order->driver_id = $driver->id;

        $order->save();

    }


}
