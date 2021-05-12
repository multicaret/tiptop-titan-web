<?php

namespace App\Jobs\Tookan;

use App\Integrations\Tookan\TookanClient;
use App\Jobs\Middleware\RateLimited;
use App\Models\TookanTeam;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateTeam /*implements ShouldQueue*/
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $team;

    /**
     * Create a new job instance.
     *
     * @param  TookanTeam  $team
     */
    public function __construct(TookanTeam $team)
    {
        $this->team = $team;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $client = new TookanClient();

        if (empty($team_id = $this->team->tookan_team_id)) {
            $response = $client->createTeam($this->team);
        } else {
            $response = $client->updateTeam($this->team, $team_id);

        }
        if ( ! $response){
            return;

        }
        if ( ! $response->successful()) {
         //   $this->fail();
            return;

        }
        $responseData = $response->json();
        if (  $responseData['status'] == 100) {
            info('Tookan Request missing parameter', [
                'captain_id' => $this->team->id,
                'response' => $response->json()
            ]);
            //    $this->fail();
            return;

        }
        if (  $responseData['status'] == 201) {
            info('Tookan Request missing parameter', [
                'captain_id' => $this->team->id,
                'response' => $response->json()
            ]);
            //    $this->fail();
            return;

        }
        if (isset($responseData['data']['team_id'])) {
            $this->team->tookan_team_id = $responseData['data']['team_id'];
            $this->team->saveQuietly();
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
