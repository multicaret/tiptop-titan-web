<?php

namespace App\Http\Livewire\Orders;

use App\Models\JetOrder;
use App\Models\Order;
use Livewire\Component;

class OrdersIndex extends Component
{
    public $foodNewOrdersCount = 0;
    public $groceryNewOrdersCount = 0;
    public $jetOrdersCount = 0;

    public function render()
    {
        $this->foodNewOrdersCount = Order::foods()->new()->count();
        $this->groceryNewOrdersCount = Order::groceries()->new()->count();
        $this->jetOrdersCount = JetOrder::where('status',JetOrder::STATUS_ASSIGNING_COURIER)->whereDate('created_at',today())->count();

        return view('livewire.orders.index');
    }
}
