<?php

namespace App\Http\Livewire;

use App\Models\Order;
use Livewire\Component;

class OrdersTable extends Component
{
    public $orders;
    public $selectedOrder;
    public $orderProducts;

    /*use WithPagination;

    protected $paginationTheme = 'bootstrap';*/

    /*
     * Search criteria:
    */
    public $referenceCode;
    public $customerName;
    public $customerEmail;
    public $customerPhone;
    public $branchName;

    /*
     * Filter By Date.
     * */
    public $filterByDate;

    public function updatedFilterByDate($newValue)
    {
        $this->retrieveOrders();
    }

    /*public function mount()
    {
    }*/

    public function render()
    {
        $this->retrieveOrders();

        return view('livewire.orders-table');
    }

    private function retrieveOrders()
    {
        if (is_null($this->filterByDate)) {
            $this->filterByDate = now()->format(config('defaults.date.short_format'));
        }
        $orders = Order::orderBy('created_at', 'desc')
                       ->orderBy('status');

        if ( ! empty($this->referenceCode)) {
            $orders = $orders->where('reference_code', $this->referenceCode);
        } elseif ( ! empty($this->customerName)) {
            $customerName = $this->customerName;
            $orders = $orders->whereHas('user', function ($query) use ($customerName) {
                $query->where('first', 'like', '%'.$customerName.'%');
                $query->orWhere('last', 'like', '%'.$customerName.'%');
            });
        } elseif ( ! empty($this->customerEmail)) {
            $customerEmail = $this->customerEmail;
            $orders = $orders->whereHas('user', function ($query) use ($customerEmail) {
                $query->where('email', 'like', '%'.$customerEmail.'%');
            });
        } elseif ( ! empty($this->customerPhone)) {
            $customerPhone = $this->customerPhone;
            $orders = $orders->whereHas('user', function ($query) use ($customerPhone) {
                $query->where('phone_number', 'like', '%'.$customerPhone.'%');
            });
        } elseif ( ! empty($this->branchName)) {
            $branchName = $this->branchName;
            $orders = $orders->whereHas('branch', function ($query) use ($branchName) {
                $query->whereHas('translations', function ($queryTranslations) use ($branchName) {
                    $queryTranslations->where('title', 'like', '%'.$branchName.'%');
                });
            });
        }
        $orders = $orders->whereDate('created_at', $this->filterByDate);
        $orders = $orders->get();
        $this->orders = $orders;
    }

    public function resetFilters()
    {
        $this->referenceCode = null;
        $this->customerName = null;
        $this->customerEmail = null;
        $this->customerPhone = null;
        $this->branchName = null;
        $this->filterByDate = now()->format(config('defaults.date.short_format'));
    }

    public $showModal = false;

    public function show($id)
    {
        $this->showModal = true;
        $selectedOrder = $this->selectedOrder = Order::where('id', $id)->first();
        $this->orderProducts = $selectedOrder->cart->cartProducts;
    }
//        $this->emit('userStore');
}
