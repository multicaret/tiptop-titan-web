<?php

namespace App\Http\Livewire;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;

class OrdersTable extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    /*
     *
     * Search by:
Order ID
Customer Name
Customer Email
Customer Phone
Branch
    */

    /*
     * Filter By Date.
     * */

    public function render()
    {
        $orders = Order::orderBy('created_at', 'desc')
                       ->orderBy('status')
                       ->paginate(10);

        return view('livewire.orders-table', compact('orders'));
    }
}
