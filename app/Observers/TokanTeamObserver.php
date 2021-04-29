<?php

namespace App\Observers;

use App\Jobs\Tookan\CreateTeam;
use App\Models\TokanTeam;

class TokanTeamObserver
{
    /**
     * Handle the TokanTeam "created" event.
     *
     * @param  \App\Models\TokanTeam  $tokanTeam
     * @return void
     */
    public function created(TokanTeam $tokanTeam)
    {
        $tookan_status = config('services.tookan.status');
        if ($tokanTeam->status == TokanTeam::STATUS_ACTIVE && $tookan_status) {
            CreateTeam::dispatchSync($tokanTeam);
        }
    }

    /**
     * Handle the TokanTeam "updated" event.
     *
     * @param  \App\Models\TokanTeam  $tokanTeam
     * @return void
     */
    public function updated(TokanTeam $tokanTeam)
    {
        $tookan_status = config('services.tookan.status');
        if ($tokanTeam->wasChanged('name') && $tookan_status)
        {
            CreateTeam::dispatchSync($tokanTeam);

        }
    }

    /**
     * Handle the TokanTeam "deleted" event.
     *
     * @param  \App\Models\TokanTeam  $tokanTeam
     * @return void
     */
    public function deleted(TokanTeam $tokanTeam)
    {
        //
    }

    /**
     * Handle the TokanTeam "restored" event.
     *
     * @param  \App\Models\TokanTeam  $tokanTeam
     * @return void
     */
    public function restored(TokanTeam $tokanTeam)
    {
        //
    }

    /**
     * Handle the TokanTeam "force deleted" event.
     *
     * @param  \App\Models\TokanTeam  $tokanTeam
     * @return void
     */
    public function forceDeleted(TokanTeam $tokanTeam)
    {
        //
    }
}
