<?php

namespace App\Http\Livewire;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;

class OrdersTable extends Component
{
    use WithPagination;

    public function render()
    {
        $orders = Order::latest()->paginate(3);

        return view('livewire.orders-table', compact('orders'));
    }
}
