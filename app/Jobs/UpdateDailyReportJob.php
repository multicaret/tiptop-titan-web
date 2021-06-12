<?php

namespace App\Jobs;

use App\Integrations\Zoho\ZohoBooksProducts;
use App\Jobs\Middleware\RateLimited;
use App\Models\Branch;
use App\Models\Order;
use App\Models\OrderDailyReport;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class UpdateDailyReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $order;

    public $type;


    public $tries = 5;

    /**
     * Create a new job instance.
     *
     * @param  Order  $order
     * @param $type
     */
    public function __construct(Order $order, $type)
    {

        $this->order = $order;
        $this->type = $type;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $record = OrderDailyReport::whereDate('day', today()->toDateString())->first();
        $ordersQuery = Order::whereDate('created_at', today()->toDateString())->where(function ($query) {
            $query->where('customer_notes', 'not like', '%test%')
                  ->orWhere('customer_notes', null);
        });

        $todayDateString = $this->order->created_at->toDateString();
        $range1 = Carbon::parse($todayDateString.' '.'09:00')->toDateTimeString();
        $range2 = Carbon::parse($todayDateString.' '.'11:59')->toDateTimeString();
        $range3 = Carbon::parse($todayDateString.' '.'12:00')->toDateTimeString();
        $range4 = Carbon::parse($todayDateString.' '.'14:59')->toDateTimeString();
        $range5 = Carbon::parse($todayDateString.' '.'15:00')->toDateTimeString();
        $range6 = Carbon::parse($todayDateString.' '.'17:59')->toDateTimeString();
        $range7 = Carbon::parse($todayDateString.' '.'18:00')->toDateTimeString();
        $range8 = Carbon::parse($todayDateString.' '.'20:59')->toDateTimeString();
        $range9 = Carbon::parse($todayDateString.' '.'21:00')->toDateTimeString();
        $range10 = Carbon::parse($todayDateString.' '.'23:59')->toDateTimeString();
        $range11 = Carbon::parse($todayDateString.' '.'00:00')->toDateTimeString();
        $range12 = Carbon::parse($todayDateString.' '.'02:59')->toDateTimeString();
        $range13 = Carbon::parse($todayDateString.' '.'03:00')->toDateTimeString();
        $range14 = Carbon::parse($todayDateString.' '.'08:59')->toDateTimeString();

        $record->orders_count = (clone $ordersQuery)->count();
        $record->grocery_orders_count = (clone $ordersQuery)->where('type', Order::CHANNEL_GROCERY_OBJECT)->count();
        $record->food_orders_count = (clone $ordersQuery)->where('type', Order::CHANNEL_FOOD_OBJECT)->count();
        $record->orders_value = (clone $ordersQuery)->sum('grand_total');
        $record->grocery_orders_value = (clone $ordersQuery)->where('type',
            Order::CHANNEL_GROCERY_OBJECT)->sum('grand_total');
        $record->food_orders_value = (clone $ordersQuery)->where('type',
            Order::CHANNEL_FOOD_OBJECT)->sum('grand_total');
        $record->average_grocery_orders_value = (clone $ordersQuery)->where('type',
            Order::CHANNEL_GROCERY_OBJECT)->avg('grand_total');
        $record->average_food_orders_value = (clone $ordersQuery)->where('type',
            Order::CHANNEL_FOOD_OBJECT)->avg('grand_total');
        $record->average_orders_value = (clone $ordersQuery)->avg('grand_total');

        $record->orders_count_between_09_12 = (clone $ordersQuery)->where('created_at', '>=',
            $range1)->where('created_at', '<=', $range2);
        $record->orders_count_between_12_15 = (clone $ordersQuery)->where('created_at', '>=',
            $range3)->where('created_at', '<=', $range4);
        $record->orders_count_between_15_18 = (clone $ordersQuery)->where('created_at', '>=',
            $range5)->where('created_at', '<=', $range6);
        $record->orders_count_between_18_21 = (clone $ordersQuery)->where('created_at', '>=',
            $range7)->where('created_at', '<=', $range8);
        $record->orders_count_between_21_00 = (clone $ordersQuery)->where('created_at', '>=',
            $range9)->where('created_at', '<=', $range10);
        $record->orders_count_between_00_03 = (clone $ordersQuery)->where('created_at', '>=',
            $range11)->where('created_at', '<=', $range12);
        $record->orders_count_between_03_09 = (clone $ordersQuery)->where('created_at', '>=',
            $range13)->where('created_at', '<=', $range14);
        $record->ordered_users_count = (clone $ordersQuery)->whereHas('user', function ($query) {
            $query->where('users.created_at', today()->toDateString());
        })->count();
        $record->delivered_orders_count = (clone $ordersQuery)->where('status', Order::STATUS_DELIVERED)->count();
        $record->delivered_grocery_orders_count = (clone $ordersQuery)->where('type',
            Order::CHANNEL_GROCERY_OBJECT)->where('status', Order::STATUS_DELIVERED)->count();
        $record->delivered_food_orders_count = (clone $ordersQuery)->where('type',
            Order::CHANNEL_FOOD_OBJECT)->where('status', Order::STATUS_DELIVERED)->count();

        $record->delivered_food_orders_value = (clone $ordersQuery)->where('type',
            Order::CHANNEL_FOOD_OBJECT)->where('status', Order::STATUS_DELIVERED)->sum('grand_total');

        $record->delivered_grocery_orders_value = (clone $ordersQuery)->where('type',
            Order::CHANNEL_GROCERY_OBJECT)->where('status', Order::STATUS_DELIVERED)->sum('grand_total');

        $sum_delivery_time = 0;
        $count = 0;
        (clone $ordersQuery)->where('status', Order::STATUS_DELIVERED)->get()->map(function ($item) use (
            &$sum_delivery_time,
            &$count
        ) {
            $from = $item->created_at;
            $to = $item->activity()->where('type', 'updated_order')->where('differences->status',
                '20')->first();

            if ( ! empty($to)) {
                $sum_delivery_time = $sum_delivery_time + $to->created_at->diffInMinutes($from);
                $count++;
            }

        });
        $record->average_delivery_time = $count > 0 ? $sum_delivery_time / $count : 0;

        $record->save();
    }

    /**
     * Handle a job failure.
     *
     * @param  \Throwable  $exception
     * @return void
     */
    public function failed(\Exception $exception)
    {
        info('UpdateDailyRepostJob Error', [
            'exception' => $exception,
            'message' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
        ]);
    }

}