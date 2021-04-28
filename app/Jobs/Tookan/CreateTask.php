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

        if ( ! $response) {
            info('Tookan Request Error', [
                'captain_id' => $this->user->id,
                'response' => $response ?? 'empty'
            ]);
        //    $this->fail();
        }

        if ( ! $response->successful()) {
            info('Tookan Request Error', [
                'captain_id' => $this->user->id,
                'response' => $response
            ]);
        //    $this->fail();

        }

        $responseData = $response->json();
        if (  $responseData['status'] == 100) {
            info('Create Tookan Task Request error message', [
                'captain_id' => $this->user->id,
                'response' => $response->json()
            ]);
            //    $this->fail();
        }
        if (  $responseData['status'] == 201) {
            info('Create Tookan Task Request missing parameter', [
                'captain_id' => $this->user->id,
                'response' => $response->json()
            ]);
            //    $this->fail();
        }
        if (isset($responseData['data']) && isset($response['pickup_job_id']) && isset($response['delivery_job_id']))
        {
            $this->order->tookanInfo()->create([
                'job_pickup_id'          => $response['pickup_job_id'],
                'job_delivery_id'        => $response['delivery_job_id'],
                'delivery_tracking_link' => isset($response['delivery_tracing_link']) ? $response['delivery_tracing_link'] : NULL,
                'pickup_tracking_link'   => isset($response['pickup_tracking_link']) ? $response['pickup_tracking_link'] : NULL,
                'job_hash'  => isset($response['job_hash']) ? $response['job_hash'] : NULL,
                'job_token' => isset($response['job_token']) ? $response['job_token'] : NULL,
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
