<?php

namespace App\Http\Livewire\Orders;

use App\Models\JetOrder;
use App\Models\Order;
use Livewire\Component;

class JetOrdersTable extends Component
{
    public $orders;
    public $selectedOrder;

    /*use WithPagination;

    protected $paginationTheme = 'bootstrap';*/

    /*
     * Search criteria:
    */
    public $referenceCode;
    public $customerName;
    public $customerPhone;
    public $branchName;
    public $branchId;
    public $searchByStatus;


    protected $queryString = ['branchId'];

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

        return view('livewire.jet-orders.table');
    }

    private function retrieveOrders()
    {
        if (is_null($this->filterByDate) && is_null($this->branchId)) {
            $this->filterByDate = now()->format(config('defaults.date.short_format'));
        }
        $orders = JetOrder::with('branch')
                       ->orderBy('created_at', 'desc')
                       ->orderBy('status');

        $shouldSearchByDate = false;
        if ( ! empty($this->referenceCode)) {
            $orders = $orders->where('reference_code', 'like', "%$this->referenceCode%");
        } elseif ( ! empty($this->customerName)) {
            $customerName = $this->customerName;
            $orders = $orders->where('destination_full_name', 'like', '%'.$customerName.'%');
        } elseif ( ! empty($this->customerPhone)) {
            $customerPhone = $this->customerPhone;
            $orders = $orders->where('destination_phone', 'like', '%'.$customerPhone.'%');

        } elseif ( ! empty($this->branchName)) {
            $branchName = $this->branchName;
            $orders = $orders->whereHas('branch', function ($query) use ($branchName) {
                $query->whereHas('translations', function ($queryTranslations) use ($branchName) {
                    $queryTranslations->where('title', 'like', '%'.$branchName.'%');
                });
            });
        } elseif ( ! is_null($this->branchId)) {
            $orders = $orders->whereBranchId($this->branchId);
        } elseif ( ! is_null($this->searchByStatus)) {
            $orders = $orders->whereStatus($this->searchByStatus)->latest()->take(200);
        } else {
            $shouldSearchByDate = true;
        }

        if ($shouldSearchByDate) {
            $orders = $orders->whereDate('created_at', $this->filterByDate);
        }
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

//    public $showModal = false;

    public function selectOrder($id)
    {
//        dd("selectOrder");
//        $this->showModal = true;
//        $this->selectedOrder = Order::where('id', $id)->first();
        // emit to order show to show modal
        $this->emitTo('orders.jet-order-show', 'orderSelected', [
            'id' => $id
        ]);
    }
}
