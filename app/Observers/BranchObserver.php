<?php

namespace App\Observers;

use App\Jobs\Zoho\CreateBranchAccountJob;
use App\Jobs\Zoho\CreateDeliveryItemJob;
use App\Jobs\Zoho\CreateTipTopDeliveryItemJob;
use App\Jobs\Zoho\SyncBranchJob;
use App\Models\Branch;
use Illuminate\Support\Facades\Bus;

class BranchObserver
{
    // Dear Noor, we had to make this as false for our "updated" activity
    // however, we added a delay of 1 minute
    public $afterCommit = false;

    /**
     * Handle the Branch "created" event.
     *
     * @param  \App\Models\Branch  $branch
     * @return void
     */
    public function created(Branch $branch)
    {
        Bus::chain(
            [
                new SyncBranchJob($branch),
                new CreateDeliveryItemJob($branch),
                new CreateTipTopDeliveryItemJob($branch),
                new CreateBranchAccountJob($branch),
            ]
        )
           ->dispatch()
           ->delay(now()->addMinute());
    }

    /**
     * Handle the Branch "updated" event.
     *
     * @param  \App\Models\Branch  $branch
     * @return void
     */
    public function updated(Branch $branch)
    {
        $branch->recordActivity('updated');
    }

    /**
     * Handle the Branch "deleted" event.
     *
     * @param  \App\Models\Branch  $branch
     * @return void
     */
    public function deleted(Branch $branch)
    {
        $branch->recordActivity('deleted');
    }

    /**
     * Handle the Branch "restored" event.
     *
     * @param  \App\Models\Branch  $branch
     * @return void
     */
    public function restored(Branch $branch)
    {
        //
    }

    /**
     * Handle the Branch "force deleted" event.
     *
     * @param  \App\Models\Branch  $branch
     * @return void
     */
    public function forceDeleted(Branch $branch)
    {
        //
    }
}
