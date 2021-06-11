<?php

namespace App\Observers;

use App\Models\OrderDailyReport;
use Illuminate\Support\Facades\Schema;

class OrderDailyReportObserver
{
    /**
     * Handle the Order "created" event.
     *
     * @param  OrderDailyReport  $orderDailyReport
     * @return void
     */
    public function creating(OrderDailyReport $orderDailyReport)
    {
        foreach (Schema::getColumnListing('order_daily_reports') as $col) {
            if (in_array($col, ['id', 'region_id', 'country_id', 'is_weekend', 'created_at','updated_at', 'day'])) {
                continue;
            }

            $orderDailyReport->{$col} = $orderDailyReport->{$col} ?? 0;
        }
        $orderDailyReport->region_id = $orderDailyReport->region_id ?? 6;
        $orderDailyReport->country_id = $orderDailyReport->country_id ?? 107;
        $orderDailyReport->is_weekend = $orderDailyReport->is_weekend ?? today()->isFriday();

    }

    /**
     * Handle the OrderDailyReport "created" event.
     *
     * @param  \App\Models\OrderDailyReport  $orderDailyReport
     * @return void
     */
    public function created(OrderDailyReport $orderDailyReport)
    {
        //
    }

    /**
     * Handle the OrderDailyReport "updated" event.
     *
     * @param  \App\Models\OrderDailyReport  $orderDailyReport
     * @return void
     */
    public function updated(OrderDailyReport $orderDailyReport)
    {
        //
    }

    /**
     * Handle the OrderDailyReport "deleted" event.
     *
     * @param  \App\Models\OrderDailyReport  $orderDailyReport
     * @return void
     */
    public function deleted(OrderDailyReport $orderDailyReport)
    {
        //
    }

    /**
     * Handle the OrderDailyReport "restored" event.
     *
     * @param  \App\Models\OrderDailyReport  $orderDailyReport
     * @return void
     */
    public function restored(OrderDailyReport $orderDailyReport)
    {
        //
    }

    /**
     * Handle the OrderDailyReport "force deleted" event.
     *
     * @param  \App\Models\OrderDailyReport  $orderDailyReport
     * @return void
     */
    public function forceDeleted(OrderDailyReport $orderDailyReport)
    {
        //
    }
}
