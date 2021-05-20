<?php

namespace App\Observers;

use App\Integrations\TookanClient;
use App\Jobs\Tookan\CancelTask;
use App\Jobs\Tookan\CreateTask;
use App\Models\Order;
use App\Models\User;
use App\Notifications\OrderStatusUpdated;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function creating(Order $order)
    {
        $order->reference_code = mt_rand(100, 100000);
    }

    /**
     * Handle the Order "created" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function created(Order $order)
    {
        $order->recordActivity('created');
    }

    /**
     * Handle the Order "updated" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function updated(Order $order)
    {
        if ($order->wasChanged('status')) {
            foreach (User::active()->managers()->get() as $admin) {
                $admin->notify(new OrderStatusUpdated($order, $admin->role_name));
            }
            foreach ($order->branch->owners()->active()->get() as $manager) {
                $manager->notify(new OrderStatusUpdated($order, $manager->role_name));
            }
            foreach ($order->branch->managers()->active()->get() as $manager) {
                $manager->notify(new OrderStatusUpdated($order, $manager->role_name));
            }
            $order->user->notify(new OrderStatusUpdated($order, $order->user->role_name));

            //dispatching tookan jobs based on changed order's status
            $tookan_status = config('services.tookan.status');

            if ($order->status == Order::STATUS_PREPARING && $order->is_delivery_by_tiptop && $tookan_status) {
                CreateTask::dispatchSync($order);
            } elseif ($order->status == Order::STATUS_CANCELLED && $order->is_delivery_by_tiptop && $tookan_status) {
                CancelTask::dispatchSync($order);
            }
            /*        else if ($order->status == Order::STATUS_DELIVERED && $order->is_delivery_by_tiptop && $tookan_status)
                      {
                          MarkTaskAsDelivered::dispatch($order);
                      }*/
        }
        $order->recordActivity('updated');
    }

    /**
     * Handle the Order "deleted" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function deleted(Order $order)
    {
        $order->recordActivity('deleted');
    }

    /**
     * Handle the Order "restored" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function restored(Order $order)
    {
        //
    }

    /**
     * Handle the Order "force deleted" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function forceDeleted(Order $order)
    {
        //
    }
}
