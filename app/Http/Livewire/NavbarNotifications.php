<?php

namespace App\Http\Livewire;

use Livewire\Component;

class NavbarNotifications extends Component
{
    public function render()
    {
        $user = auth()->user();
        $unreadUserNotifications = $user->unreadNotifications()->latest()->get();

        return view('livewire.notifications.navbar', compact('unreadUserNotifications'));
    }
}
