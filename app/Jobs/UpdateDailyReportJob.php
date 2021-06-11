<?php

namespace App\Jobs;

use App\Integrations\Zoho\ZohoBooksProducts;
use App\Jobs\Middleware\RateLimited;
use App\Models\Branch;
use App\Models\Order;
use App\Models\OrderDailyReport;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Throwable;

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
        if (!in_array($this->type, ['order_created','order_status_updated','order_grand_total_updated'])){
            $this->fail();
        }
        if ($this->type == 'order_created'){
            if ( ! Str::contains($this->order->customer_notes, ['test', 'Test'])) {
                $todayDateString = $this->order->created_at->toDateString();
                $range1 = Carbon::parse($todayDateString.' '.'09:00');
                $range2 = Carbon::parse($todayDateString.' '.'11:59');
                $range3 = Carbon::parse($todayDateString.' '.'12:00');
                $range4 = Carbon::parse($todayDateString.' '.'14:59');
                $range5 = Carbon::parse($todayDateString.' '.'15:00');
                $range6 = Carbon::parse($todayDateString.' '.'17:59');
                $range7 = Carbon::parse($todayDateString.' '.'18:00');
                $range8 = Carbon::parse($todayDateString.' '.'20:59');
                $range9 = Carbon::parse($todayDateString.' '.'21:00');
                $range10 = Carbon::parse($todayDateString.' '.'23:59');
                $range11 = Carbon::parse($todayDateString.' '.'00:00');
                $range12 = Carbon::parse($todayDateString.' '.'02:59');
                $range13 = Carbon::parse($todayDateString.' '.'03:00');
                $range14 = Carbon::parse($todayDateString.' '.'08:59');

                if ($this->order->type == Order::CHANNEL_GROCERY_OBJECT) {
                    $record = OrderDailyReport::firstOrCreate(['day' => today()->toDateString()]);
                    $record->increment('grocery_orders_count');
                    $record->grocery_orders_value = Order::whereDate('created_at', today()->toDateString())->where(function ($query){
                        $query->where('customer_notes', 'not like', '%test%')
                              ->orWhere('customer_notes',NULL);
                    })->where('type',
                        Order::CHANNEL_GROCERY_OBJECT)->sum('grand_total');
                    $record->average_grocery_orders_value = Order::whereDate('created_at',
                        today()->toDateString())->where(function ($query){
                        $query->where('customer_notes', 'not like', '%test%')
                              ->orWhere('customer_notes',NULL);
                    })->where('type', Order::CHANNEL_GROCERY_OBJECT)->avg('grand_total');

                } else {
                    $record = OrderDailyReport::firstOrCreate(['day' => today()->toDateString()]);
                    $record->increment('food_orders_count');
                    $record->food_orders_value = Order::whereDate('created_at', today()->toDateString())->where('type',
                        Order::CHANNEL_FOOD_OBJECT)->sum('grand_total');
                    $record->average_food_orders_value = Order::whereDate('created_at',
                        today()->toDateString())->where(function ($query){
                        $query->where('customer_notes', 'not like', '%test%')
                              ->orWhere('customer_notes',NULL);
                    })->where('type', Order::CHANNEL_FOOD_OBJECT)->avg('grand_total');
                }
                $record->increment('orders_count');
                $record->orders_value = Order::whereDate('created_at', today()->toDateString())->where(function ($query){
                    $query->where('customer_notes', 'not like', '%test%')
                          ->orWhere('customer_notes',NULL);
                })->sum('grand_total');
                $record->average_orders_value = Order::whereDate('created_at', today()->toDateString())->where(function ($query){
                    $query->where('customer_notes', 'not like', '%test%')
                          ->orWhere('customer_notes',NULL);
                })->avg('grand_total');
                if ($this->order->created_at >= $range1 && $this->order->created_at <= $range2) {
                    $record->increment('orders_count_between_09_12');
                } elseif ($this->order->created_at >= $range3 && $this->order->created_at <= $range4) {
                    $record->increment('orders_count_between_12_15');
                } elseif ($this->order->created_at >= $range5 && $this->order->created_at <= $range6) {
                    $record->increment('orders_count_between_15_18');
                } elseif ($this->order->created_at >= $range7 && $this->order->created_at <= $range8) {
                    $record->increment('orders_count_between_18_21');
                } elseif ($this->order->created_at >= $range9 && $this->order->created_at <= $range10) {
                    $record->increment('orders_count_between_21_00');
                } elseif ($this->order->created_at >= $range11 && $this->order->created_at <= $range12) {
                    $record->increment('orders_count_between_00_03');
                } elseif ($this->order->created_at >= $range13 && $this->order->created_at <= $range14) {
                    $record->increment('orders_count_between_00_03');
                }
                if ($this->order->user->created_at->toDateString() == today()->toDateString() && $this->order->user->orders()->count() == 1)
                {
                    $record->increment('ordered_users_count');
                }
                if ($this->order->created_at->isFriday())
                    $record->is_weekend = true;
                else
                    $record->is_weekend = false;

                $record->country_id = 107;
                $record->region_id = 6;
                $record->save();
            }


        }
        elseif ($this->type == 'order_status_updated')
        {
            if ($this->order->status == Order::STATUS_DELIVERED){
                $record = OrderDailyReport::whereDate('day', today()->toDateString())->first();
                if (empty($record)) $this->fail();
                if ($this->order->type == Order::CHANNEL_GROCERY_OBJECT){
                    $record->increment('delivered_grocery_orders_count');
                    $record->delivered_grocery_orders_value = Order::whereDate('created_at',
                        today()->toDateString())->where('type', Order::CHANNEL_GROCERY_OBJECT)->where('status', Order::STATUS_DELIVERED)->where(function ($query){
                        $query->where('customer_notes', 'not like', '%test%')
                              ->orWhere('customer_notes',NULL);
                    })->sum('grand_total');
                    $record->average_grocery_delivered_orders_value = Order::whereDate('created_at',
                        today()->toDateString())->where('type', Order::CHANNEL_GROCERY_OBJECT)->where('status', Order::STATUS_DELIVERED)->where(function ($query){
                        $query->where('customer_notes', 'not like', '%test%')
                              ->orWhere('customer_notes',NULL);
                    })->avg('grand_total');
                }else{
                    $record->increment('delivered_food_orders_count');
                    $record->delivered_food_orders_value = Order::whereDate('created_at',
                        today()->toDateString())->where('type', Order::CHANNEL_FOOD_OBJECT)->where('status', Order::STATUS_DELIVERED)->where(function ($query){
                        $query->where('customer_notes', 'not like', '%test%')
                              ->orWhere('customer_notes',NULL);
                    })->sum('grand_total');
                    $record->average_food_delivered_orders_value = Order::whereDate('created_at',
                        today()->toDateString())->where('type', Order::CHANNEL_FOOD_OBJECT)->where('status', Order::STATUS_DELIVERED)->where(function ($query){
                        $query->where('customer_notes', 'not like', '%test%')
                              ->orWhere('customer_notes',NULL);
                    })->avg('grand_total');
                }
                $record->increment('delivered_orders_count');
                $record->delivered_orders_value = Order::whereDate('created_at',
                    today()->toDateString())->where('type', Order::CHANNEL_FOOD_OBJECT)->where('status', Order::STATUS_DELIVERED)->where(function ($query){
                    $query->where('customer_notes', 'not like', '%test%')
                          ->orWhere('customer_notes',NULL);
                })->sum('grand_total');
                $record->average_delivered_orders_value = Order::whereDate('created_at',
                    today()->toDateString())->where('status', Order::STATUS_DELIVERED)->where(function ($query){
                    $query->where('customer_notes', 'not like', '%test%')
                          ->orWhere('customer_notes',NULL);
                })->avg('grand_total');

                $sum_delivery_time = 0;
                $count = 0;
                Order::whereDate('created_at',
                    today()->toDateString())->where('status', Order::STATUS_DELIVERED)->get()->map(function ($item) use (&$sum_delivery_time,&$count){
                    $from = $item->created_at;
                    $to = $item->activity()->where('type','updated_order')->where('differences->status',
                        '20')->first();

                    if (!empty($to)){
                        $sum_delivery_time = $sum_delivery_time + $to->created_at->diffInMinutes($from);
                        $count++;
                    }

                });

                $record->average_delivery_time = $sum_delivery_time / $count;
                $record->save();
            }

        }
        /*elseif ($this->type == 'order_grand_total_updated'){
            $record = OrderDailyReport::whereDate('day', today()->toDateString())->first();
            if (empty($record)) $this->fail();
            if ($this->order->type == Order::CHANNEL_GROCERY_OBJECT){
                $record->delivered_grocery_orders_value = Order::whereDate('created_at',
                    today()->toDateString())->where('type', Order::CHANNEL_GROCERY_OBJECT)->where('status', Order::STATUS_DELIVERED)->sum('grand_total');
                $record->average_grocery_delivered_orders_value = Order::whereDate('created_at',
                    today()->toDateString())->where('type', Order::CHANNEL_GROCERY_OBJECT)->where('status', Order::STATUS_DELIVERED)->avg('grand_total');
            }else{
                $record->delivered_food_orders_value = Order::whereDate('created_at',
                    today()->toDateString())->where('type', Order::CHANNEL_FOOD_OBJECT)->where('status', Order::STATUS_DELIVERED)->sum('grand_total');
                $record->average_food_delivered_orders_value = Order::whereDate('created_at',
                    today()->toDateString())->where('type', Order::CHANNEL_FOOD_OBJECT)->where('status', Order::STATUS_DELIVERED)->avg('grand_total');
            }

            $record->save();
        }*/

    }
    /**
     * Handle a job failure.
     *
     * @param  \Throwable  $exception
     * @return void
     */
    public function failed(Throwable $exception)
    {
        info('UpdateDailyRepostJob Error', [
            'exception' => $exception,
            'message' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
        ]);
    }

}
