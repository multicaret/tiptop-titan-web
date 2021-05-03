<?php

namespace App\Models;

use App\Http\Controllers\Controller;
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
 * @property bool $is_weekend
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
 * @method static Builder|OrderDailyReport whereIsWeekend($value)
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
 * @noinspection PhpFullyQualifiedNameUsageInspection
 * @noinspection PhpUnnecessaryFullyQualifiedNameInspection
 */
class OrderDailyReport extends Model
{
    protected $casts = [
        'day' => 'date',
        'is_weekend' => 'boolean',
        'is_peak_this_month' => 'boolean',
        'is_nadir_this_month' => 'boolean',
        'is_peak_this_quarter' => 'boolean',
        'is_nadir_this_quarter' => 'boolean',
        'is_peak_this_year' => 'boolean',
        'is_nadir_this_year' => 'boolean',
    ];

    public function adjustWeekend()
    {
        if ($this->day->dayOfWeek === \Carbon\Carbon::FRIDAY && ! $this->is_weekend) {
            $this->is_weekend = true;
            $this->save();
        }
    }

    public static function retrievValues($isSum, $channel, $collection)
    {
        $collectionType = $isSum ? "sum" : "avg";
        $totalOrderCountColumn = 'total_orders_count';
        if ($channel == 'grocery') {
            $totalOrderCountColumn = 'total_grocery_orders_count';
        } elseif ($channel == 'food') {
            $totalOrderCountColumn = 'total_food_orders_count';
        }
        $totalDeliveredOrdersCount = 'total_delivered_orders_count';
        if ($channel == 'grocery') {
            $totalDeliveredOrdersCount = 'total_delivered_grocery_orders_count';
        } elseif ($channel == 'food') {
            $totalDeliveredOrdersCount = 'total_delivered_food_orders_count';
        }

        return [
            'total_orders_count' => [
                round($collection->$collectionType($totalOrderCountColumn)),
            ],
            'total_delivered_orders_count' => [
                $isSum ? $collection->$collectionType($totalDeliveredOrdersCount) : Controller::percentageInRespectToTwoNumbers($collection->avg($totalDeliveredOrdersCount),
                        $collection->$collectionType($totalOrderCountColumn)).('%'),
                $isSum ? null : $collection->sum($totalDeliveredOrdersCount),
            ],
            'total_delivered_orders_count-2' => [
                $isSum ? $collection->$collectionType($totalDeliveredOrdersCount) : Controller::percentageInRespectToTwoNumbers($collection->avg($totalDeliveredOrdersCount),
                        $collection->$collectionType($totalOrderCountColumn)).('%'),
                $isSum ? null : $collection->sum($totalDeliveredOrdersCount),
            ],
            'average_delivery_time' => [
                round($collection->avg('average_delivery_time')).'<sub class="text-muted" style="font-size: 10px">minute</sub>',
            ],
            'average_orders_value' => [
                Currency::formatHtml($collection->$collectionType('average_orders_value')),
            ],
            'orders_count_between_09_12' => [
                round($collection->$collectionType('orders_count_between_09_12')),
            ],
            'orders_count_between_12_15' => [
                round($collection->$collectionType('orders_count_between_12_15')),
            ],
            'orders_count_between_15_18' => [
                round($collection->$collectionType('orders_count_between_15_18')),
            ],
            'orders_count_between_18_21' => [
                round($collection->$collectionType('orders_count_between_18_21')),
            ],
            'orders_count_between_21_00' => [
                round($collection->$collectionType('orders_count_between_21_00'))
            ],
            'orders_count_between_00_03' => [
                round($collection->$collectionType('orders_count_between_00_03'))
            ],
            'orders_count_between_03_09' => [
                round($collection->$collectionType('orders_count_between_03_09'))
            ],
            'registered_users_count' => [
                round($collection->$collectionType('registered_users_count'), 2),
            ],
            'ordered_users_count' => [
                $isSum ? $collection->$collectionType('ordered_users_count') : Controller::percentageInRespectToTwoNumbers($collection->$collectionType('ordered_users_count'),
                    $collection->$collectionType('registered_users_count')).('%'),
                $isSum ? null : $collection->sum('ordered_users_count'),
            ],
            'ios_devices_count' => [
                $isSum ? $collection->$collectionType('ios_devices_count') : Controller::percentageInRespectToTwoNumbers($collection->$collectionType('ios_devices_count'),
                    $collection->$collectionType('total_mobile_users_count')).('%'),
                $isSum ? null : $collection->sum('ios_devices_count'),
            ],
            'android_devices_count' => [
                $isSum ? $collection->$collectionType('android_devices_count') : Controller::percentageInRespectToTwoNumbers($collection->$collectionType('android_devices_count'),
                    $collection->$collectionType('total_mobile_users_count')).('%'),
                $isSum ? null : $collection->sum('android_devices_count'),
            ],
            'total_mobile_users_count' => [
                $isSum ? $collection->$collectionType('total_mobile_users_count') : Controller::percentageInRespectToTwoNumbers($collection->$collectionType('total_mobile_users_count'),
                    $collection->$collectionType('registered_users_count')).('%'),
                $isSum ? null : $collection->sum('total_mobile_users_count'),
            ],
            'total_web_users_count' => [
                $isSum ? $collection->$collectionType('total_web_users_count') : Controller::percentageInRespectToTwoNumbers($collection->$collectionType('total_web_users_count'),
                    $collection->$collectionType('registered_users_count')).('%'),
                $isSum ? null : $collection->sum('total_web_users_count')
            ],
        ];
    }
}
