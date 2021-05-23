<?php

namespace App\Http\Livewire;

use Livewire\Component;

class NavbarNotifications extends Component
{
    public function render()
    {
        $user = auth()->user();
        $unreadUserNotificationsCount = $user->unreadNotifications()->count();
        $unreadUserNotifications = $user->unreadNotifications()->latest()->take(10)->get();

        return view('livewire.notifications.navbar',
            compact('unreadUserNotifications', 'unreadUserNotificationsCount'));
    }
}
