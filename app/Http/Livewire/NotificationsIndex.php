<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;

class NotificationsIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        $user = auth()->user();
        $notifications = $user->notifications()->latest()->paginate(7);

        return view('livewire.notifications.index', compact('notifications'));
    }
}
