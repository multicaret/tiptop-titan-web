<?php

namespace App\Http\Livewire;

use App\Models\Order;
use Livewire\Component;

class OrdersIndex extends Component
{
    public $newOrdersCount = 0;

    public function render()
    {
        $this->newOrdersCount = Order::new()->count();

        return view('livewire.orders-index');
    }
}
