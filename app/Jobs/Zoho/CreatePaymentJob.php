<?php

namespace App\Jobs\Zoho;

use App\Integrations\Zoho\ZohoBooksBranches;
use App\Integrations\Zoho\ZohoBooksInvoices;
use App\Jobs\Middleware\RateLimited;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class CreatePaymentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $order;

    /**
     * Create a new job instance.
     *
     * @param  Order  $order
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        if ($timestamp = Cache::get('zoho-api-limit')) {
            $this->release(
                $timestamp - time()
            );
        }
        $response = (new ZohoBooksInvoices($this->order))->createInvoice();

        if ( ! $response) {
            $this->fail();
        }
        //handling too many request response
        if ($response->status() == 429) {
            $secondsRemaining = $response->header('Retry-After');

            if (empty($secondsRemaining)) {
                $secondsRemaining = 65;
            }

            Cache::put(
                'zoho-api-limit',
                now()->addSeconds($secondsRemaining)->timestamp,
                $secondsRemaining
            );

            $this->release(
                $secondsRemaining
            );
        }

        //handling zoho outage (if happens)
        if ($response->serverError()) {
            if ( ! Cache::get('zoho-failure')) {
                Cache::put('zoho-failure', 1, 60);
            } else {
                Cache::increment('zoho-failure');
            }

            return $this->release(600);
        }

        if ($response->failed()) {
            $this->fail();
        }

        Cache::forget('zoho-failure');


        if (isset($response['invoice']) && isset($response['invoice']['invoice_id'])) {
            $zoho_books_invoice_id = $response['invoice']['invoice_id'];
            $this->order->zoho_books_invoice_id = $zoho_books_invoice_id;
            $this->order->save();
        } else {
            info('zoho create delivery item response error', [
                'response' => $response->json()
            ]);
            $this->fail();
        }


    }

    public function retryUntil()
    {
        return now()->addHours(1);
    }

    public function middleware()
    {
        return [new RateLimited];
    }
}
