<?php

namespace App\Jobs\Tookan;

use App\Integrations\Tookan\TookanClient;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CancelTask implements ShouldQueue
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
        $client = new TookanClient();

        $response = $client->cancelTask($this->order);

        if ( ! $response) {
            info('Tookan Request Error', [
                'captain_id' => $this->order->id,
                'response' => $response ?? 'empty'
            ]);
         //   $this->fail();
        }




    }
}
