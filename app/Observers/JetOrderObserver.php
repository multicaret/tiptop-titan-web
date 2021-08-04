<?php

namespace App\Observers;

use App\Integrations\TookanClient;
use App\Jobs\Tookan\CancelTask;
use App\Jobs\Tookan\CreateJetTask;
use App\Jobs\Tookan\CreateTask;
use App\Jobs\Zoho\ApplyPaymentCreditJob;
use App\Jobs\Zoho\CreateInvoiceJob;
use App\Jobs\Zoho\CreatePaymentJob;
use App\Jobs\UpdateDailyReportJob;
use App\Models\JetOrder;
use App\Models\Order;
use App\Models\OrderDailyReport;
use App\Models\User;
use App\Notifications\OrderStatusUpdated;
use Carbon\Carbon;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Str;

class JetOrderObserver
{
    /**
     * Handle the Order "created" event.
     *
     * @param  \App\Models\JetOrder  $order
     * @return void
     */
    public function creating(JetOrder $order)
    {
        $order->reference_code = 'JET'.mt_rand(0, 99).substr(time(), 5);

        if (empty($order->agent_notes)) {
            $order->agent_notes = [];  // set empty json array
        }
    }

    /**
     * Handle the Order "created" event.
     *
     * @param  JetOrder  $order
     * @return void
     * @throws \ReflectionException
     */
    public function created(JetOrder $order)
    {
        $order->recordActivity('created');

        $tookan_status = config('services.tookan.status');


    }

    /**
     * Handle the Order "updated" event.
     *
     * @param  \App\Models\JetOrder  $order
     * @return void
     */
    public function updated(JetOrder $order)
    {
        if ($order->wasChanged('status')) {
            try {
//                foreach (User::active()->managers()->get() as $admin) {
//                    $admin->notify(new OrderStatusUpdated($order, $admin->role_name));
//                }
//                foreach ($order->branch->owners()->active()->get() as $manager) {
//                    $manager->notify(new OrderStatusUpdated($order, $manager->role_name));
//                }
//                foreach ($order->branch->managers()->active()->get() as $manager) {
//                    $manager->notify(new OrderStatusUpdated($order, $manager->role_name));
//                }
            } catch (\Exception $e) {
                info('error @notify in OrderObserver@updated', [
                    'order' => $order,
                    'exception' => $e,
                ]);
            }
            //dispatching tookan job
            $tookan_status = config('services.tookan.status');
            if ($order->status == JetOrder::STATUS_ASSIGNING_COURIER) {
                if ( ! Str::contains($order->client_notes,
                        ['test', 'Test']) && empty($order->tookanInfo) && $tookan_status) {
                    CreateJetTask::dispatchSync($order);

                }
            }
            else if ($order->status == JetOrder::STATUS_CANCELLED){
                if (! empty(optional($order->tookanInfo)->job_pickup_id)) {
                    CancelTask::dispatchSync($order);
                }
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
    public function deleted(JetOrder $order)
    {
        $order->recordActivity('deleted');
    }

    /**
     * Handle the Order "restored" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function restored(JetOrder $order)
    {
        //
    }

    /**
     * Handle the Order "force deleted" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function forceDeleted(JetOrder $order)
    {
        //
    }
}
