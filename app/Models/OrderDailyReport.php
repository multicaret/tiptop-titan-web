<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\OrderDailyReport
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon $day
 * @property int $total_orders_count
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
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDailyReport newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDailyReport newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDailyReport query()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDailyReport whereAndroidDevicesCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDailyReport whereAverageDeliveryTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDailyReport whereAverageOrdersValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDailyReport whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDailyReport whereDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDailyReport whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDailyReport whereIosDevicesCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDailyReport whereIsNadirOfThisMonth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDailyReport whereIsNadirOfThisQuarter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDailyReport whereIsNadirOfThisYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDailyReport whereIsPeakOfThisMonth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDailyReport whereIsPeakOfThisQuarter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDailyReport whereIsPeakOfThisYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDailyReport whereOrderedUsersCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDailyReport whereOrdersCountBetween0003($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDailyReport whereOrdersCountBetween0309($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDailyReport whereOrdersCountBetween0912($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDailyReport whereOrdersCountBetween1215($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDailyReport whereOrdersCountBetween1518($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDailyReport whereOrdersCountBetween1821($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDailyReport whereOrdersCountBetween2100($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDailyReport whereOtherDevicesCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDailyReport whereRegisteredUsersCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDailyReport whereTotalDeliveredOrdersCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDailyReport whereTotalMobileUsersCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDailyReport whereTotalOrdersCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDailyReport whereTotalWebUsersCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDailyReport whereUpdatedAt($value)
 * @mixin \Eloquent
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
