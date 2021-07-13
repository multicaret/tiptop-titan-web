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
    public $newOrders = 0;
    public $canceledOrders = 0;
    public $onTheWayOrders = 0;
    public $waitingForCourierOrders = 0;

    public function render()
    {
        $this->foodNewOrdersCount = Order::foods()->new()->count();
        $this->groceryNewOrdersCount = Order::groceries()->new()->count();
        $this->jetOrdersCount = JetOrder::where('status',JetOrder::STATUS_ASSIGNING_COURIER)->whereDate('created_at',today())->count();
        $this->newOrders = Order::whereDate('created_at',today())->where('status',Order::STATUS_NEW)->count();
        $this->canceledOrders = Order::whereDate('created_at',today())->where('status',Order::STATUS_CANCELLED)->count();
        $this->onTheWayOrders = Order::whereDate('created_at',today())->where('status',Order::STATUS_ON_THE_WAY)->count();
        $this->waitingForCourierOrders = Order::whereDate('created_at',today())->where('status',Order::STATUS_WAITING_COURIER)->count();
        return view('livewire.orders.index');
    }
}
