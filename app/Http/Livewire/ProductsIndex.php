<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class ProductsIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $branchId;

    public function render()
    {
        $products = Product::whereBranchId($this->branchId)
                           ->latest()
                           ->paginate(10);

        return view('livewire.products-index', ['products' => $products]);
    }
}
