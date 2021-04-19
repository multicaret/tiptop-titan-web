<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Notifications extends Component
{
    public $userNotifications;

    public function render()
    {
        return view('livewire.notifications');
    }
}
