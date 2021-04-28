<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\OrderDailyReport
 *
 * @property int $id
 * @property int $region_id
 * @property Carbon $day
 * @property int $total_grocery_orders_count
 * @property int $total_food_orders_count
 * @property int $total_orders_count
 * @property int $total_delivered_grocery_orders_count
 * @property int $total_delivered_food_orders_count
 * @property int $total_delivered_orders_count
 * @property int $average_delivery_time
 * @property float $average_orders_value
 * @property int $orders_count_between_09_12
 * @property int $orders_count_between_12_15
 * @property int $orders_count_between_15_18
 * @property int $orders_count_between_18_21
 * @property int $orders_count_between_21_00
 * @property int $orders_count_between_00_03
 * @property int $orders_count_between_03_09
 * @property int $registered_users_count
 * @property int $ordered_users_count
 * @property int $ios_devices_count
 * @property int $android_devices_count
 * @property int $other_devices_count
 * @property int $total_mobile_users_count
 * @property int $total_web_users_count
 * @property int $is_peak_of_this_month
 * @property int $is_nadir_of_this_month
 * @property int $is_peak_of_this_quarter
 * @property int $is_nadir_of_this_quarter
 * @property int $is_peak_of_this_year
 * @property int $is_nadir_of_this_year
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|OrderDailyReport newModelQuery()
 * @method static Builder|OrderDailyReport newQuery()
 * @method static Builder|OrderDailyReport query()
 * @method static Builder|OrderDailyReport whereAndroidDevicesCount($value)
 * @method static Builder|OrderDailyReport whereAverageDeliveryTime($value)
 * @method static Builder|OrderDailyReport whereAverageOrdersValue($value)
 * @method static Builder|OrderDailyReport whereCreatedAt($value)
 * @method static Builder|OrderDailyReport whereDay($value)
 * @method static Builder|OrderDailyReport whereId($value)
 * @method static Builder|OrderDailyReport whereIosDevicesCount($value)
 * @method static Builder|OrderDailyReport whereIsNadirOfThisMonth($value)
 * @method static Builder|OrderDailyReport whereIsNadirOfThisQuarter($value)
 * @method static Builder|OrderDailyReport whereIsNadirOfThisYear($value)
 * @method static Builder|OrderDailyReport whereIsPeakOfThisMonth($value)
 * @method static Builder|OrderDailyReport whereIsPeakOfThisQuarter($value)
 * @method static Builder|OrderDailyReport whereIsPeakOfThisYear($value)
 * @method static Builder|OrderDailyReport whereOrderedUsersCount($value)
 * @method static Builder|OrderDailyReport whereOrdersCountBetween0003($value)
 * @method static Builder|OrderDailyReport whereOrdersCountBetween0309($value)
 * @method static Builder|OrderDailyReport whereOrdersCountBetween0912($value)
 * @method static Builder|OrderDailyReport whereOrdersCountBetween1215($value)
 * @method static Builder|OrderDailyReport whereOrdersCountBetween1518($value)
 * @method static Builder|OrderDailyReport whereOrdersCountBetween1821($value)
 * @method static Builder|OrderDailyReport whereOrdersCountBetween2100($value)
 * @method static Builder|OrderDailyReport whereOtherDevicesCount($value)
 * @method static Builder|OrderDailyReport whereRegionId($value)
 * @method static Builder|OrderDailyReport whereRegisteredUsersCount($value)
 * @method static Builder|OrderDailyReport whereTotalDeliveredFoodOrdersCount($value)
 * @method static Builder|OrderDailyReport whereTotalDeliveredGroceryOrdersCount($value)
 * @method static Builder|OrderDailyReport whereTotalDeliveredOrdersCount($value)
 * @method static Builder|OrderDailyReport whereTotalFoodOrdersCount($value)
 * @method static Builder|OrderDailyReport whereTotalGroceryOrdersCount($value)
 * @method static Builder|OrderDailyReport whereTotalMobileUsersCount($value)
 * @method static Builder|OrderDailyReport whereTotalOrdersCount($value)
 * @method static Builder|OrderDailyReport whereTotalWebUsersCount($value)
 * @method static Builder|OrderDailyReport whereUpdatedAt($value)
 * @mixin Eloquent
 */
class OrderDailyReport extends Model
{
    protected $casts = [
        'day' => 'date',
        'is_peak_this_month' => 'boolean',
        'is_nadir_this_month' => 'boolean',
        'is_peak_this_quarter' => 'boolean',
        'is_nadir_this_quarter' => 'boolean',
        'is_peak_this_year' => 'boolean',
        'is_nadir_this_year' => 'boolean',
    ];
}
