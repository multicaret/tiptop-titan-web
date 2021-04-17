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
    public $searchByCategory;

    public function render()
    {
        $products = Product::whereBranchId($this->branchId)
                           ->latest()
                           ->paginate(10);

        return view('livewire.products-index', ['products' => $products]);
    }


    public function updatedSearchByCategory()
    {
        if ( ! is_null($this->searchByCategory) && $this->searchByCategory != 'all') {
            $products = Product::whereBranchId($this->branchId)
                               ->where('category_id', $this->searchByCategory)
                               ->latest()
                               ->get();
        } else {
            $products = Product::whereBranchId($this->branchId)
                               ->latest()
                               ->get();
        }

        return view('livewire.products-index', ['products' => $products]);
    }


}
