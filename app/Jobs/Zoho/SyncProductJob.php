<?php

namespace App\Jobs\Zoho;

use App\Integrations\Zoho\ZohoBooksProducts;
use App\Jobs\Middleware\RateLimited;
use App\Models\Branch;
use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class SyncProductJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $product;

    public $tries = 0;

    public $maxExceptions = 1;

    /**
     * Create a new job instance.
     *
     * @param  Product  $product
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
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
        $productResponse = (new ZohoBooksProducts($this->product))->createProduct();

        if ( ! $productResponse) {
            $this->fail();
        }
        //handling too many request response
        if ($productResponse->status() == 429) {
            $secondsRemaining = $productResponse->header('Retry-After');

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
        if ($productResponse->serverError()) {
            if ( ! Cache::get('zoho-failure')) {
                Cache::put('zoho-failure', 1, 60);
            } else {
                Cache::increment('zoho-failure');
            }

            return $this->release(600);
        }

        if ($productResponse->failed()) {
            $this->fail();
        }

        Cache::forget('zoho-failure');


        if (isset($productResponse['item']) && isset($productResponse['item']['item_id'])) {
            $zoho_books_account_id = $productResponse['item']['item_id'];
            $this->product->zoho_books_id = $zoho_books_account_id;
            $this->product->save();
        } else {
            info('zoho product response error', [
                'response' => $productResponse->json()
            ]);
            $this->fail();
        }


    }

    public function retryUntil()
    {
        return now()->addHours(10);
    }

    public function middleware()
    {
        return [new RateLimited];
    }
}
