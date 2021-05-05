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

class CreateCaptain /*implements ShouldQueue*/
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;

    /**
     * Create a new job instance.
     *
     * @param  User  $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $client = new TookanClient();

        if (empty($tookan_id = $this->user->tookan_id)) {
            $response = $client->createCaptain($this->user);
        } else {
            $response = $client->updateCaptain($this->user);
        }
        if ( ! $response) {
            info('Tookan Request Error', [
                'captain_id' => $this->user->id,
                'response' => $response ?? 'empty'
            ]);
        //    $this->fail();
        }
        $responseData = $response->json();

        if ( ! $response->successful()) {
            info('Tookan Request Error', [
                'captain_id' => $this->user->id,
                'response' => $responseData
            ]);
          //  $this->fail();

        }
        if ($responseData['status'] == 100) {
            info('Tookan Request missing parameter', [
                'captain_id' => $this->user->id,
                'response' => $responseData
            ]);
            //    $this->fail();
        }
        if ($responseData['status'] == 201) {
            info('Tookan Request missing parameter', [
                'captain_id' => $this->user->id,
                'response' => $responseData
            ]);
            //    $this->fail();

        }
        if (isset($response['data']) && isset($response['data']['fleet_id'])) {
            $this->user->tookan_id = $response['data']['fleet_id'];
            $this->user->saveQuietly();
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
