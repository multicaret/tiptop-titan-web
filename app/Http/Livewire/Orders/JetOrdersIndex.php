<?php

namespace App\Http\Livewire\Orders;

use App\Models\JetOrder;
use App\Models\Order;
use Livewire\Component;

class JetOrdersIndex extends Component
{
    public $tiptopOrdersCount = 0;


    public function render()
    {
        $this->tiptopOrdersCount = Order::where('status',Order::STATUS_NEW)->whereDate('created_at',today())->count();

        return view('livewire.jet-orders.index');
    }
}
