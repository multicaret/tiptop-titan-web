<?php

namespace App\Http\Controllers\Tookan;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TookanController extends Controller
{

    //pickups
    public function pickupSuccessWebHook(Request $request)
    {
        info('taskPickupSuccessWebHook');
    }

    public function pickupFailWebHook(Request $request)
    {
        info('pickupFailWebHook');

    }

    public function pickupRequestReceived(Request $request)
    {
        info('pickupRequestReceived');

    }

    public function pickupAgentArrived(Request $request)
    {
        info('pickupAgentArrived');

    }

    //deliveries
    public function deliverySuccessWebHook(Request $request)
    {

        info('deliverySuccessWebHook');
        return;

        if (empty($request->job_id) || empty($request->order_id)) {
            return;
        }
        $this->data['model'] = null;
        $this->data['model'] = Order::with('customer', 'branch.restaurant','coupon')->where('code', $request->order_id)->firstOrFail();
        $user = $this->data['model']->customer;

        try {


            DB::transaction(function () use ($user, $request) {
                $this->initSystemFinancialAccounts($request);

                if (in_array($this->data['model']->status, ['CANCELED','DELIVERED','DECLINED','NOT_DELIVERED']))
                    return;

                $this->data['model']->status = 'DELIVERED';
                $this->data['model']->payment_status = 'PAID';
                $this->data['model']->save();


                // Add transactions to indicate the application share of this order.
                $receipt = new Receipt;
                $receipt->name_key = 'customer_paid';
                $receipt->description_key = 'customer_paid';
                $receipt->save();

                $app_percentage = 0;
                $cost = $this->data['model']->cost;
                if ( ! is_null($branch = $this->data['model']->branch) && ! is_null($restaurant = $branch->restaurant)) {
                    if ($this->data['model']->delivery_type == 'APP') {
                        $restaurant_app_percentage = $restaurant->delivery_app_percentage;
                        $cost = $cost - $this->data['model']->delivery_fee;
                    } else {
                        $restaurant_app_percentage = $restaurant->app_percentage;
                    }
                    $matching_percentage = collect($restaurant_app_percentage)->filter(function ($value, $key) use (
                        $cost
                    ) {
                        switch ($value) {
                            case ! is_null($value['from']):
                                return $cost > $value['from'];
                                break;

                            default:
                                return false;
                                break;
                        }
                    })->last();

                    $app_percentage = ! is_null($matching_percentage) ? $matching_percentage['percentage'] : 0;
                }

                $app_share = ($cost * $app_percentage) / 100;

                $this->data['model']->app_share = $app_share;
                $this->data['model']->branch_share = $cost - $app_share;
                $this->data['model']->app_percentage = $app_percentage;
                $this->data['model']->amount_range_from = ! is_null($matching_percentage) ? $matching_percentage['from'] : 0;
                $this->data['model']->amount_range_to = ! is_null($matching_percentage) ? $matching_percentage['to'] : null;
                $this->data['model']->save();

                $this->data['branch_account'] = $this->data['model']->branch->account;

                $this->data['model']->transactions()->create([
                    'receipt_id' => $receipt->id,
                    'entity_code' => null,
                    'account_id' => $this->data['Sales']->id,
                    'auth_id' => $user->id,
                    'transaction_type' => 'FROM',
                    'amount' => $app_share
                ]);

                $this->data['model']->transactions()->create([
                    'receipt_id' => $receipt->id,
                    'entity_code' => null,
                    'account_id' => $this->data['branch_account']->id,
                    'auth_id' => $user->id,
                    'transaction_type' => 'TO',
                    'amount' => $app_share
                ]);

                $this->data['branch_account']->balance -= $app_share;
                $this->data['branch_account']->monthly_sales += $cost;
                $this->data['Sales']->balance += $app_share;

                $this->data['branch_account']->save();
                $this->data['Sales']->save();

                if ( ! empty($this->data['discount_method'] = $this->data['model']->discount_method) && $this->data['discount_method'] instanceof Instance) {
                    // Add transactions to indicate the application share of this order.
                    $receipt = new Receipt;
                    $receipt->name_key = 'app_discount_for_branch';
                    $receipt->description_key = 'app_discount_for_branch';
                    $receipt->save();

                    $this->data['model']->transactions()->create([
                        'receipt_id' => $receipt->id,
                        'entity_code' => null,
                        'account_id' => $this->data['branch_account']->id,
                        'auth_id' => $user->id,
                        'transaction_type' => 'FROM',
                        'amount' => $this->data['model']->discount_method_amount
                    ]);

                    $this->data['model']->transactions()->create([
                        'receipt_id' => $receipt->id,
                        'entity_code' => null,
                        'account_id' => $this->data['Purchases']->id,
                        'auth_id' => $user->id,
                        'transaction_type' => 'TO',
                        'amount' => $this->data['model']->discount_method_amount
                    ]);

                    $this->data['discount_method']->use_stage = 'CONSUMED';
                    $this->data['discount_method']->save();
                }

                if (isset($request->fleet_id)) {
                    $driver = User::whereHas('tookanInfo', function ($query) use ($request) {
                        $query->where('tookan_id', $request->fleet_id);
                    })->first();
                    if (empty($this->data['model']->driver_id) && !empty($driver)) {
                        $this->data['model']->driver_id = $driver->id;
                        $this->data['model']->save();
                        $this->data['model']->progress()->create([
                            'actor_id' => $driver->id,
                            'key' => 'DELIVERED'
                        ]);
                    }
                    else{
                        $this->data['model']->progress()->create([
                            'actor_id' => 1,
                            'key' => 'DELIVERED'
                        ]);
                    }

                    $history = $this->data['model']->progress->where('key', 'PENDING')->first();
                    if ( ! empty($history)) {
                        $diff = Carbon::parse(now())->diffInMinutes(Carbon::parse($history->created_at));
                        $this->data['model']->delivery_time = (int) $diff;
                        $this->data['model']->save();
                    }
                }


            });
        }
        catch (\Exception $e){
            info('exc',[$e]);

        }
        $notification_type = 'ORDER_DELIVERED';

        //onesignal push notifications

        $branch = $this->data['model']->branch->load('admins');
        $branchAdmins = $branch->admins;
        $customer = $this->data['model']->customer;

        $BranchAdminsNotificationPayload = [
            'order_code' => $this->data['model']->code,
            'order_id' => $this->data['model']->id,
            'status' => $this->data['model']->status,
            'icon' => asset(__('cms::app.logos.front')),
            'notify_type' => 'MOBILE',
            'sub_type' => $notification_type,
            'entity_id' => $this->data['model']->id,
            'entity_type' => 'ORDER',

        ];
        $customerNotificationPayload = [
            'order_code' => $this->data['model']->code,
            'order_id' => $this->data['model']->id,
            'status' => $this->data['model']->status,
            'icon' => asset(__('cms::app.logos.front')),
            'notify_type' => 'MOBILE',
            'sub_type' => $notification_type,
            'entity_id' => $this->data['model']->id,
            'entity_type' => 'ORDER',
        ];

        //OneSignalNotification::send($branchAdmins, new BranchAdminOrderDelivered($BranchAdminsNotificationPayload));
        OneSignalNotification::send($customer, new CustomerOrderDelivered($customerNotificationPayload));

        return true;
    }

    public function deliveryFailWebHook(Request $request)
    {
        info('deliveryFailWebHook');
        return;
        if (empty($request->job_id) || empty($request->order_id)) {
            return;
        }

        $this->data['model'] = Order::with('customer', 'branch.restaurant','coupon')->where('code', $request->order_id)->firstOrFail();
        $user = $this->data['model']->customer;

        DB::transaction(function () use ($request) {
            $this->data['model']->status = 'NOT_DELIVERED';

            if (!empty($this->data['model']->coupon)){

                    $coupon = $this->data['model']->coupon;
                    $coupon->use_stage = 'AVAILABLE';
                    $coupon->save();

            }
            $this->data['model']->save();


            if (isset($request->fleet_id))
            {
                $driver = User::whereHas('tookanInfo',function ($query) use ($request){
                    $query->where('tookan_id',$request->fleet_id);
                })->first();

                if (!empty($driver)){
                    $this->data['model']->progress()->create([
                        'actor_id' => $driver->id,
                        'key'      => 'NOT_DELIVERED'
                    ]);
                }
                else{
                    $this->data['model']->progress()->create([
                        'actor_id' => 1,
                        'key'      => 'NOT_DELIVERED'
                    ]);
                }
            }
        });



        //onesignal push notifications

        $customer = $this->data['model']->customer;
        $branchAdmins = $this->data['model']->branch->admins;

        $customerNotificationPayload = [
            'order_code' => $this->data['model']->code,
            'order_id' => $this->data['model']->id,
            'status' => $this->data['model']->status,
            'icon' => asset(__('cms::app.logos.front')),
            'notify_type' => 'MOBILE',
            'sub_type' => 'ORDER_UPDATED',
            'entity_id' => $this->data['model']->id,
            'entity_type' => 'ORDER',
        ];



        $BranchAdminsNotificationPayload = array(
            'order_code'    => $this->data['model']->code ,
            'order_id'      => $this->data['model']->id ,
            'status'        => $this->data['model']->status,
            'icon' 	        => asset( __('cms::app.logos.front') ),
            'sound'		    => 'default',
            'notify_type'   => 'MOBILE',
            'sub_type'      => 'ORDER_CANCELED',
            'entity_id'     => $this->data['model']->id,
            'entity_type'   => 'ORDER',
        );

//           OneSignalNotification::send($branchAdmins, new BranchAdminOrderCanceled($BranchAdminsNotificationPayload));
//           OneSignalNotification::send($branchAdmins, new BranchAdminOrderCanceledNewApp($BranchAdminsNotificationPayload));

           OneSignalNotification::send($customer, new CustomerOrderStatusUpdated($customerNotificationPayload));

        //end onesignal pushing
    }

    public function deliveryRequestReceived(Request $request)
    {
        info('deliveryRequestReceived');
        return;
    }

    public function deliveryAgentStarted(Request $request)
    {
        info('deliveryAgentStarted');
        return;
        if (empty($request->job_id) || empty($request->order_id) || empty($request->fleet_id)) {
            return;
        }
        $order = Order::with('customer', 'branch.restaurant','coupon')->where('code', $request->order_id)->firstOrFail();
        $customer = $order->customer;

        if (in_array($order->status, ['CANCELED','DELIVERED','DECLINED','NOT_DELIVERED']))
            return;
        $driver = User::whereHas('tookanInfo',function ($query) use ($request){
            $query->where('tookan_id',$request->fleet_id);
        })->firstOrFail();
        info('driver',[$driver]);

        $order->status = 'ON_THE_WAY';
        $order->save();
        $order->progress()->create([
            'actor_id' => $driver->id,
            'key'      => 'ON_THE_WAY'
        ]);
        $customerNotificationPayload = array(
            'order_code'    => $order->code ,
            'number'        => $order->code ,
            'order_id'      => $order->id ,
            'status'        => $order->status,
            'icon' 	        => asset( __('cms::app.logos.front') ),
            'notify_type'   => 'MOBILE',
            'sub_type'      => 'ORDER_DELIVERED',
            'entity_id'     => $order->id,
            'entity_type'   => 'ORDER',

        );
        OneSignalNotification::send($customer, new CustomerDriverOnTheWay($customerNotificationPayload));

    }

    public function deliveryAgentArrived(Request $request)
    {
        info('deliveryAgentArrived');
        return;
        if (empty($request->job_id) || empty($request->order_id) || empty($request->fleet_id)) {
            return;
        }
        $order = Order::with('customer', 'branch.restaurant','coupon')->where('code', $request->order_id)->firstOrFail();
        $customer = $order->customer;

        $customerNotificationPayload = array(
            'order_code'    => $order->code ,
            'number'        => $order->code ,
            'order_id'      => $order->id ,
            'status'        => $order->status,
            'icon' 	        => asset( __('cms::app.logos.front') ),
            'notify_type'   => 'MOBILE',
            'sub_type'      => 'ORDER_DELIVERED',
            'entity_id'     => $order->id,
            'entity_type'   => 'ORDER',

        );
        OneSignalNotification::send($customer, new CustomerDriverArrived($customerNotificationPayload));
    }

    public function driverAssigned(Request $request)
    {
        info('driverAssigned');
        return;
        if (empty($request->job_id) || empty($request->order_id) || empty($request->fleet_id)) {
            return;
        }
        $order = Order::with('customer', 'branch.restaurant','coupon')->where('code', $request->order_id)->firstOrFail();
        $driver = User::whereHas('tookanInfo',function ($query) use ($request){
           $query->where('tookan_id',$request->fleet_id);
        })->first();
        if (!empty($driver)) {
            $order->driver_id = $driver->id;
        }
        else
        {
            $order->driver_id = 1;

        }
        $order->save();

    }



}
