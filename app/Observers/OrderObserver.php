<?php

namespace App\Observers;

use App\Integrations\TookanClient;
use App\Jobs\Tookan\CancelTask;
use App\Jobs\Tookan\CreateTask;
use App\Jobs\Zoho\ApplyPaymentCreditJob;
use App\Jobs\Zoho\CreateInvoiceJob;
use App\Jobs\Zoho\CreatePaymentJob;
use App\Jobs\UpdateDailyReportJob;
use App\Models\Order;
use App\Models\OrderDailyReport;
use App\Models\User;
use App\Notifications\OrderStatusUpdated;
use Carbon\Carbon;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Str;

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
        $order->reference_code = (int) (mt_rand(0, 99).substr(time(), 2));
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
        if (!Str::contains($order->customer_notes, ['test', 'Test']))
        {
            UpdateDailyReportJob::dispatch($order,'order_created')->delay(now()->addMinutes(3));
        }
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
            try {
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
            } catch (\Exception $e) {
                info('error @notify in OrderObserver@updated', [
                    'order' => $order,
                    'exception' => $e,
                ]);
            }
            //dispatching tookan jobs based on changed order's status
            $tookan_status = config('services.tookan.status');

            if ($order->status == Order::STATUS_PREPARING && $order->is_delivery_by_tiptop && empty($order->tookanInfo) && $tookan_status) {
                CreateTask::dispatchSync($order);
            } elseif ($order->status == Order::STATUS_CANCELLED && $order->is_delivery_by_tiptop && ! empty(optional($order->tookanInfo)->job_pickup_id) && $tookan_status) {
                CancelTask::dispatchSync($order);
            } elseif ($order->status == Order::STATUS_DELIVERED) {
                if (!Str::contains($order->customer_notes, ['test', 'Test']))
                {
                    UpdateDailyReportJob::dispatch($order,'order_status_updated');
                }
            }
        } elseif ($order->wasChanged('grand_total')) {
            //create job for updating task
            // UpdateTask::dispatchSync($order);
            if (!Str::contains($order->customer_notes, ['test', 'Test']))
            {
                UpdateDailyReportJob::dispatch($order,'order_grand_total_updated');
            }
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
