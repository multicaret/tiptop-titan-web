<?php

namespace App\Jobs\Tookan;

use App\Integrations\Tookan\TookanClient;
use App\Jobs\Middleware\RateLimited;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateTask implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $order;

    /**
     * Create a new job instance.
     *
     * @return void
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
        $response = (new TookanClient())->createTask($this->order);

        $responseData = $response->json();

        if ( ! $response) {
            info('Tookan Request Error', [
                'order_id' => $this->order->id,
                'response' => $response ?? 'empty'
            ]);
            //    $this->fail();
        }
        $responseData = $response->json();

        if ( ! $response->successful()) {
            info('Tookan Request Error', [
                'order_id' => $this->order->id,
                'response' => $responseData
            ]);
            //    $this->fail();

        }

        if ($responseData['status'] == 100) {
            info('Create Tookan Task Request error message', [
                'order_id' => $this->order->id,
                'response' => $responseData
            ]);
            //    $this->fail();
        }
        if ($responseData['status'] == 201) {
            info('Create Tookan Task Request missing parameter', [
                'captain_id' => $this->order->id,
                'response' => $responseData
            ]);
            //    $this->fail();
        }
        if (isset($response['data']) && isset($response['data']['pickup_job_id']) && isset($response['data']['delivery_job_id'])) {

            $this->order->tookanInfo()->create([
                'job_pickup_id' => $response['data']['pickup_job_id'],
                'job_delivery_id' => $response['data']['delivery_job_id'],
                'delivery_tracking_link' => isset($response['data']['delivery_tracing_link']) ? $response['data']['delivery_tracing_link'] : null,
                'pickup_tracking_link' => isset($response['data']['pickup_tracking_link']) ? $response['data']['pickup_tracking_link'] : null,
                'job_hash' => isset($response['data']['job_hash']) ? $response['data']['job_hash'] : null,
                'job_token' => isset($response['data']['job_token']) ? $response['data']['job_token'] : null,
                // 'type'      => //food or market,
            ]);
        } else {


        }

    }
    /**
     * Get the middleware the job should pass through.
     *
     * @return array
     */
    /*    public function middleware()
        {
            return [new RateLimited];
        }*/
}
