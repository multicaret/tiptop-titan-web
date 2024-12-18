<?php

namespace App\Jobs\Tookan;

use App\Integrations\Tookan\TookanClient;
use App\Jobs\Middleware\RateLimited;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ToggleCaptainStatus /*implements ShouldQueue*/
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;
    public $deleteOperation;

    /**
     * Create a new job instance.
     *
     * @param  User  $user
     * @param  bool  $deleteOperation
     */
    public function __construct(User $user,$deleteOperation = false)
    {
        $this->user = $user;
        $this->deleteOperation = $deleteOperation;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $client = new TookanClient();
        $response = $client->blockCaptain($this->user,$this->deleteOperation);

        if ( ! $response) {
            info('Tookan Request Error', [
                'captain_id' => $this->user->id,
                'response' => $response ?? 'empty'
            ]);
        //    $this->fail();
            return;

        }

        if ( ! $response->successful()) {
            info('Tookan Request Error', [
                'captain_id' => $this->user->id,
                'response' => $response
            ]);
       //     $this->fail();
            return;

        }

        $responseData = $response->json();
        if (  $responseData['status'] == 100) {
            info('Tookan Request missing parameter', [
                'captain_id' => $this->user->id,
                'response' => $response->json()
            ]);
        //    $this->fail();
            return;

        }
        if (  $responseData['status'] == 201) {
            info('Tookan Request missing parameter', [
                'captain_id' => $this->user->id,
                'response' => $response->json()
            ]);
        //    $this->fail();
            return;

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
