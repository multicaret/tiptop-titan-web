<?php

namespace App\Integrations\Tookan;

use App\Models\CartProduct;
use App\Models\Chain;
use App\Models\Order;
use App\Models\Product;
use App\Models\TookanTeam;
use App\Models\User;
use Illuminate\Support\Facades\Http;

class TookanClient
{
    protected $base_url;
    protected $api_key;
    protected $default_team_id;

    public function __construct()
    {
        $this->base_url = config('services.tookan.base_url');
        $this->api_key = config('services.tookan.api_key');
        $this->default_team_id = config('services.tookan.default_team_id');

    }

    public function createTask(Order $order)
    {
        $taskData = $this->prepareTaskData($order);

        return $this->apiRequest('POST', 'create_task', $taskData);
    }

    public function prepareTaskData(Order $order)
    {
        $food_team = TookanTeam::where('name', 'like', '%Food%')->first();
        $market_team = TookanTeam::where('name', 'like', '%Market%')->first();
        $items = [];
        foreach ($order->cart->cartProducts as $cartProduct) {
            $product_title = $cartProduct->product->type == Product::CHANNEL_GROCERY_OBJECT ?
                $cartProduct->product->translate('ar')->title.' - '.$cartProduct->product->translate('ar')->description :
                $cartProduct->product->translate('ar')->title;
            $price = ($cartProduct->product->price + $cartProduct->total_options_price) *  $cartProduct->quantity;
            $items[] = [
                 $product_title,
                 (string) $cartProduct->quantity,
                 (string) $price .' IQD'
            ];
        }
        return [
            'order_id' => $order->reference_code,
            'timezone' => '-180',
            'team_id' => $order->type == Order::CHANNEL_GROCERY_OBJECT ? $market_team->tookan_team_id : $food_team->tookan_team_id,
            'auto_assignment' => 1,
            'job_pickup_phone' => $order->branch->primary_phone_number,
            'job_pickup_name' => $order->branch->contacts->first()->name.' '.$order->branch->title,
            //      'job_pickup_email'        => $order->branch->contact_email,
            'job_pickup_address' => empty($order->branch->full_address) ? 'Not specified' : $order->branch->full_address,
            'job_pickup_latitude' => $order->branch->latitude,
            'job_pickup_longitude' => $order->branch->longitude,
            'job_pickup_datetime' => now()->addMinutes(13)->toDateTimeString(),
            'customer_email' => $order->user->email,
            'customer_username' => $order->user->first.' '.$order->user->last,
            'customer_phone' => $order->user->phone_number,
            'customer_address' => empty($order->address->address1) ? 'Not specified' : $order->address->address1,
            'latitude' => $order->address->latitude,
            'longitude' => $order->address->longitude,
            'job_delivery_datetime' => now()->addMinutes(15)->toDateTimeString(),
            'has_pickup' => 1,
            'has_delivery' => 1,
            'layout_type' => 0,
            'tracking_link' => 1,
            'notify' => 1,
            'pickup_custom_field_template' => 'template_1',
            'custom_field_template' => 'template_1',
            'pickup_meta_data' => [
                [
                    'label' => 'items',
                    'data' => $items
                ]
                ],
            'meta_data' => [
                [
                    'label' => 'price',
                    'data' => (string) $order->total
                ],
                [
                    'label' => 'items',
                    'data' => $items
                ],
//                [
//                    'label' => 'payment_method',
//                    'data' => (string) __('jo3aan::orders.payment_methods.' . $this->order->payment_method .'.label',[],'ar')
//                ],
//                [
//                    'label' => 'payment_status',
//                    'data' => $this->order->payment_status == 'NOT_PAID' ? 'لم يتم الدفع' : 'تم الدفع'
//                ],
//                [
//                    'label' => 'tiptop_order_number',
//                    'data' => $this->order->code
//                ],
//                [
//                    'label' => 'delivery_address_notes',
//                    'data'  =>  $this->order->address->address_description
//                ],
//                [
//                    'label' => 'customer_phone_number',
//                    'data' =>  $this->order->customer->phone_number
//                ],
            ],

        ];
    }

    public function apiRequest($method, $endpoint, $data = [])
    {
        try {
            $url = $this->base_url.$endpoint;

            $request = Http::withHeaders([
                'cache-control' => 'no-cache',
                'content-type' => 'application/json',
                'Connection' => 'keep-alive'
            ]);
            $response = null;
            if ($method == 'POST') {
                $response = $request->post($url, array_merge(['api_key' => $this->api_key], $data));
            } elseif ($method == 'GET') {
                $response = $request->get($url, array_merge(['api_key' => $this->api_key], $data));
            }

            return $response;
        } catch (\Exception $ex) {
            return false;
        }

    }

    public function markTaskAsDelivered(Order $order)
    {
        if (empty(optional($order->tookanInfo)->job_pickup_id) || empty(optional($order->tookanInfo)->job_delivery_id)) {
            return false;
        }

        $deliveredTaskData = $this->prepareDeliveredTaskData($order);

        return $this->apiRequest('POST', 'update_task_status', $deliveredTaskData);

    }

    public function prepareDeliveredTaskData(Order $order)
    {
        return [
            'job_id' => $order->tookanInfo->job_pickup_id.','.$order->tookanInfo->job_delivery_id,
            'job_status' => 2
        ];
    }

    public function cancelTask(Order $order)
    {
        if (empty(optional($order->tookanInfo)->job_pickup_id) || empty(optional($order->tookanInfo)->job_delivery_id)) {
            return false;
        }

        $cancelPickupOrderData = $this->prepareCancelPickupOrderData($order);

        $this->apiRequest('POST', 'delete_task', $cancelPickupOrderData);

        $cancelDeliveryOrderData = $this->prepareCancelDeliveryOrderData($order);

        return $this->apiRequest('POST', 'delete_task', $cancelDeliveryOrderData);

    }

    public function prepareCancelPickupOrderData(Order $order)
    {
        return [
            'job_id' => $order->tookanInfo->job_pickup_id
        ];
    }

    public function prepareCancelDeliveryOrderData(Order $order)
    {
        return [
            'job_id' => $order->tookanInfo->job_delivery_id
        ];
    }

    public function createCaptain(User $user)
    {

        if ($user->role_name != User::ROLE_TIPTOP_DRIVER) {
            return false;
        }

        if (empty(optional($user->team)->tookan_team_id)) {
            $team = TookanTeam::first();
            if (empty($team)) {
                return false;
            }
            $user->team_id = $team->id;
            $user->saveQuietly();
            $user->load('team');
        }
        $captainData = $this->prepareCaptainData($user);

        return $this->apiRequest('POST', 'add_agent', $captainData);

    }

    public function prepareCaptainData(User $user)
    {
        info($user);

        return [
            //   'tags'              => $this->driver->tookanInfo->type == 'food' ? 'food' : 'market',
            'email' => $user->email,
            'username' => $user->username,
            'first_name' => $user->first ?? $user->username,
            'last_name' => $user->last,
            'profile_url' => $user->avatar,
            'profile_thumb_url' => $user->avatar,
            'phone' => $user->international_phone,
            'transport_type' => '2',
            'timezone' => '-180',
            'password' => 'Tiptopagent@123',
            'team_id' => (string) $user->team->tookan_team_id,
        ];
    }

    public function updateCaptain(User $user)
    {
        if ($user->role_name != User::ROLE_TIPTOP_DRIVER) {
            return false;
        }

        if (empty(optional($user->team)->tookan_team_id)) {
            $team = TookanTeam::first();
            if (empty($team)) {
                return false;
            }
            $user->team_id = $team->id;
            $user->saveQuietly();
            $user->load('team');
        }
        $captainData = $this->prepareCaptainData($user);

        return $this->apiRequest('POST', 'edit_agent',
            array_merge(['fleet_id' => (string) $user->tookan_id], $captainData));

    }

    public function blockCaptain(User $user, $deleteOperation = false)
    {
        if (empty(optional($user->team)->tookan_team_id || $user->role_name != User::ROLE_TIPTOP_DRIVER)) {
            return false;
        }

        $captainStatusData = $this->prepareCaptainStatusData(($user->status != User::STATUS_ACTIVE || $deleteOperation || ! empty($user->deleted_at)) ? 0 : 1);

        return $this->apiRequest('POST', 'block_and_unblock_fleet',
            array_merge(['fleet_id' => (string) $user->tookan_id], $captainStatusData));

    }

    public function prepareCaptainStatusData($status)
    {
        return [
            'block_status' => $status,
        ];
    }

    public function createTeam(TookanTeam $tookanTeam)
    {

        $teamData = $this->prepareTeamData($tookanTeam);

        return $this->apiRequest('POST', 'create_team', $teamData);

    }

    public function prepareTeamData(TookanTeam $tookanTeam)
    {
        return [
            'team_name' => $tookanTeam->name,
            'battery_usage' => 1,
        ];
    }

    public function updateTeam(TookanTeam $tookanTeam, $team_id)
    {

        $teamData = $this->prepareTeamData($tookanTeam);

        return $this->apiRequest('POST', 'update_team', array_merge(['team_id' => (string) $team_id], $teamData));

    }
}
