<?php

namespace App\Http\Livewire;

use App\Models\Order;
use Livewire\Component;

class OrdersIndex extends Component
{
    public $foodNewOrdersCount = 0;
    public $groceryNewOrdersCount = 0;

    public function render()
    {
        $this->foodNewOrdersCount = Order::food()->new()->count();
        $this->groceryNewOrdersCount = Order::grocery()->new()->count();

        return view('livewire.orders-index');
    }
}
