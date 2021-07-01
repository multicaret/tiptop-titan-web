<?php

namespace App\Observers;

use App\Models\Branch;

class BranchObserver
{
    /**
     * Handle the Branch "created" event.
     *
     * @param  \App\Models\Branch  $branch
     * @return void
     */
    public function created(Branch $branch)
    {
        $branch->recordActivity('created');
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
