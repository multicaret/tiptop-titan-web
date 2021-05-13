<?php

namespace App\Observers;

use App\Jobs\Tookan\CreateCaptain;
use App\Jobs\Tookan\ToggleCaptainStatus;
use App\Models\User;

class UserObserver
{
    public $afterCommit = true;

    /**
     * Handle the User "created" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function created(User $user)
    {
        $tookan_status = config('services.tookan.status');

        if ($user->role_name == User::ROLE_TIPTOP_DRIVER && $tookan_status) {
            CreateCaptain::dispatchSync($user);
        }
    }

    /**
     * Handle the User "updated" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function updated(User $user)
    {
        $tookan_status = config('services.tookan.status');

        if ($user->role_name == User::ROLE_TIPTOP_DRIVER && $tookan_status) {
            if ($user->wasChanged('status') || $user->wasChanged('deleted_at')) {
                ToggleCaptainStatus::dispatchSync($user);
            }
            else{
                CreateCaptain::dispatchSync($user);
            }

        }
    }

    /**
     * Handle the User "deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function deleted(User $user)
    {
        $tookan_status = config('services.tookan.status');

        if ( ! empty($user->tookan_id) && $tookan_status) {
            ToggleCaptainStatus::dispatchSync($user, true);
        }

    }

    /**
     * Handle the User "restored" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function restored(User $user)
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        //
    }
}
