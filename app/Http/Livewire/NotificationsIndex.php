<?php

namespace App\Http\Livewire;

use Livewire\Component;

class NotificationsIndex extends Component
{
    public function render()
    {
        $user = auth()->user();
        $notifications = $user->notifications()->latest()->get();

        return view('livewire.notifications.index', compact('notifications'));
    }
}
