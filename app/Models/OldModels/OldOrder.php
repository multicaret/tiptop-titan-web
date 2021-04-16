<?php

namespace App\Models\OldModels;


use App\Models\City;
use App\Models\Country;
use App\Models\Location;
use App\Models\Order;
use App\Models\Region;
use App\Models\User;

class OldOrder extends OldModel
{
    protected $table = 'jo3aan_orders';
    protected $primaryKey = 'id';


    public const IS_DEFAULT = 1;

    public const STATUS_ACTIVE = 'ACTIVE';
    public const STATUS_DISABLED = 'DISABLED';
    public const STATUS_SUSPENDED = 'SUSPENDED';

    public function attributesComparing(): array
    {
        $attributesKeys = [
            'id' => 'id',
            'code' => 'reference_code',
            'customer_id' => 'user_id',
//            '' => 'chain_id',
            'branch_id' => 'branch_id',
//            'driver_id' => '',
            'basket_id' => 'cart_id',
//            '' => 'payment_method_id',
            'address_id' => 'address_id',
//            '' => 'coupon_id',
//            '' => 'city_id',
//            '' => 'previous_order_id',
//            '' => 'type',  // Grocery || Food
//            '' => 'coupon_discount_amount',
//            '' => 'delivery_fee',
//            '' => 'private_payment_method_commission',
//            '' => 'private_total',
//            '' => 'private_delivery_fee',
//            '' => 'private_grand_total',
//            '' => 'is_delivery_by_tiptop',
//            '' => 'branch_rating_value',
//            '' => 'rated_at',
//            '' => 'rating_comment',
//            '' => 'driver_rating_value',
//            '' => 'has_good_food_quality_rating',
//            '' => 'has_good_packaging_quality_rating',
//            '' => 'has_good_order_accuracy_rating',
//            '' => 'rating_issue_id',
//            '' => 'completed_at',

//            'type' => '',
//            'status' => '', // typeComparing()
//            'restaurant_notes' => '',
            'customer_notes' => 'notes',
//            'payment_status' => '',
            'price' => 'total',
            'cost' => 'grand_total',
//            'app_share' => '',
//            'branch_share' => '',
//            'app_percentage' => '',
//            'amount_range_from' => '',
//            'amount_range_to' => '',
            'delivery_fee' => 'delivery_fee',
            'is_delivery_by_tiptop' => 'is_delivery_by_tiptop',  // delivery_type
//            'driver_approved' => '',
//            'drivers_notified' => '',
//            'currently_notified_driver_id' => '',
//            'notify_date' => '',
//            'number_of_notified' => '',
            'discount' => '',

            'deleted_at' => 'deleted_at',
            'created_at' => 'created_at',
            'updated_at' => 'updated_at',
        ];

        if (self::validateLatLong($this->latitude, $this->longitude)) {
            $attributesKeys = array_merge($attributesKeys, [
                'latitude' => 'latitude',
                'longitude' => 'longitude'
            ]);
        }

        return $attributesKeys;
    }

    public function typeComparing(): array
    {
        return [
            'PENDING' => Order::STATUS_PREPARING,
            'CANCELED' => Order::STATUS_CANCELLED,
            'DELIVERED' => Order::STATUS_DELIVERED,
            'DECLINED' => Order::STATUS_DECLINED,
            'NOT_DELIVERED' => Order::STATUS_NOT_DELIVERED,
            'PREPARING' => Order::STATUS_PREPARING,
            'READY' => Order::STATUS_WAITING_COURIER,
            'ON_THE_WAY' => Order::STATUS_ON_THE_WAY,
        ];
    }

    public function getIsDeliveryByTiptopAttribute () {
        return $this->delivery_type === 'APP';
    }
}
