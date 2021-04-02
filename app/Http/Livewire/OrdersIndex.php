<?php

namespace App\Http\Livewire;

use Livewire\Component;

class OrdersIndex extends Component
{
    public $pendingOrdersCount = 0;

    public function render()
    {
        return view('livewire.orders-index');
    }
}
