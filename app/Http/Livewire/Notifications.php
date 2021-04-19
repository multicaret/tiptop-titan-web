<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Notifications extends Component
{
    public $unreadNotificationsCount;

    public function render()
    {
        $user = auth()->user();
        $this->unreadNotificationsCount = $user->unreadNotifications()->count();
        $userNotifications = $user->notifications()->get();

        return view('livewire.notifications', compact('userNotifications'));
    }
}
