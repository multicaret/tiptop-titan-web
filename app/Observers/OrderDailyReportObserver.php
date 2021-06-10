<?php

namespace App\Observers;

use App\Models\OrderDailyReport;

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
        $orderDailyReport->region_id = $orderDailyReport->region_id ?? 6;
        $orderDailyReport->country_id = $orderDailyReport->country_id ?? 107;
        $orderDailyReport->is_weekend = $orderDailyReport->is_weekend ?? today()->isFriday();
        $orderDailyReport->orders_count = $orderDailyReport->orders_count ?? 0;
        $orderDailyReport->grocery_orders_count = $orderDailyReport->grocery_orders_count ?? 0;
        $orderDailyReport->food_orders_count = $orderDailyReport->food_orders_count ?? 0;
        $orderDailyReport->delivered_orders_count = $orderDailyReport->delivered_orders_count ?? 0;
        $orderDailyReport->delivered_grocery_orders_count = $orderDailyReport->delivered_grocery_orders_count ?? 0;
        $orderDailyReport->delivered_food_orders_count = $orderDailyReport->delivered_food_orders_count ?? 0;
        $orderDailyReport->average_delivery_time = $orderDailyReport->average_delivery_time ?? 0;
        $orderDailyReport->orders_value = $orderDailyReport->orders_value ?? 0;
        $orderDailyReport->grocery_orders_value = $orderDailyReport->grocery_orders_value ?? 0;
        $orderDailyReport->food_orders_value = $orderDailyReport->food_orders_value ?? 0;
        $orderDailyReport->delivered_orders_value = $orderDailyReport->delivered_orders_value ?? 0;
        $orderDailyReport->delivered_grocery_orders_value = $orderDailyReport->delivered_grocery_orders_value ?? 0;
        $orderDailyReport->delivered_food_orders_value = $orderDailyReport->delivered_food_orders_value ?? 0;
        $orderDailyReport->average_orders_value = $orderDailyReport->average_orders_value ?? 0;
        $orderDailyReport->average_grocery_orders_value = $orderDailyReport->average_grocery_orders_value ?? 0;
        $orderDailyReport->average_food_orders_value = $orderDailyReport->average_food_orders_value ?? 0;
        $orderDailyReport->average_delivered_orders_value = $orderDailyReport->average_delivered_orders_value ?? 0;
        $orderDailyReport->average_grocery_delivered_orders_value = $orderDailyReport->average_grocery_delivered_orders_value ?? 0;
        $orderDailyReport->average_food_delivered_orders_value = $orderDailyReport->average_food_delivered_orders_value ?? 0;
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
