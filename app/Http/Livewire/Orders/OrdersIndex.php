<?php

namespace App\Http\Livewire\Orders;

use App\Models\Order;
use Livewire\Component;

class OrdersIndex extends Component
{
    public $foodNewOrdersCount = 0;
    public $groceryNewOrdersCount = 0;

    public function render()
    {
        $this->foodNewOrdersCount = Order::foods()->new()->count();
        $this->groceryNewOrdersCount = Order::groceries()->new()->count();

        return view('livewire.orders.index');
    }
}
