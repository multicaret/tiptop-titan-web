<?php

namespace App\Jobs\Zoho;

use App\Integrations\Zoho\ZohoBooksBranches;
use App\Jobs\Middleware\RateLimited;
use App\Models\Branch;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class CreateTipTopDeliveryItemJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $branch;

    public $tries = 0;

    public $maxExceptions = 1;

    /**
     * Create a new job instance.
     *
     * @param  Branch  $branch
     */
    public function __construct(Branch $branch)
    {
        $this->branch = $branch;
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
        $response = (new ZohoBooksBranches($this->branch))->createTiptopDeliveryItem();

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


        if (isset($response['item']) && isset($response['item']['item_id'])) {
            $zoho_books_account_id = $response['item']['item_id'];
            $this->branch->zoho_books_tiptop_delivery_item_id = $zoho_books_account_id;
            $this->branch->save();
        } else {
            info('zoho create tiptop delivery item response error', [
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
