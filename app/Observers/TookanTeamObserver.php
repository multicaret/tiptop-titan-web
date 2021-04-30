<?php

namespace App\Observers;

use App\Jobs\Tookan\CreateTeam;
use App\Models\TookanTeam;

class TookanTeamObserver
{
    /**
     * Handle the TookanTeam "created" event.
     *
     * @param  \App\Models\TookanTeam  $tookanTeam
     * @return void
     */
    public function created(TookanTeam $tookanTeam)
    {
        $tookan_status = config('services.tookan.status');
        if ($tookanTeam->status == TookanTeam::STATUS_ACTIVE && $tookan_status) {
            CreateTeam::dispatchSync($tookanTeam);
        }
    }

    /**
     * Handle the TookanTeam "updated" event.
     *
     * @param  \App\Models\TookanTeam  $tookanTeam
     * @return void
     */
    public function updated(TookanTeam $tookanTeam)
    {
        $tookan_status = config('services.tookan.status');
        if ($tookanTeam->wasChanged('name') && $tookan_status)
        {
            CreateTeam::dispatchSync($tookanTeam);

        }
    }

    /**
     * Handle the TookanTeam "deleted" event.
     *
     * @param  \App\Models\TookanTeam  $tookanTeam
     * @return void
     */
    public function deleted(TookanTeam $tookanTeam)
    {
        //
    }

    /**
     * Handle the TookanTeam "restored" event.
     *
     * @param  \App\Models\TookanTeam  $tookanTeam
     * @return void
     */
    public function restored(TookanTeam $tookanTeam)
    {
        //
    }

    /**
     * Handle the TookanTeam "force deleted" event.
     *
     * @param  \App\Models\TookanTeam  $tookanTeam
     * @return void
     */
    public function forceDeleted(TookanTeam $tookanTeam)
    {
        //
    }
}
