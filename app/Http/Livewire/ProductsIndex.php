<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class ProductsIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $products;
    public $branch;
    public $branchId;
    public $searchByCategory;

    public function render()
    {
        $this->retrieveProducts();

        return view('livewire.products-index');
    }


    public function retrieveProducts()
    {
        $products = Product::whereBranchId($this->branchId);
        if ( ! is_null($this->searchByCategory) && $this->searchByCategory != 'all') {
            $products = $products->where('category_id', $this->searchByCategory);
        }

        $this->products = $products->latest()->get();
    }

    public function updatedSearchByCategory()
    {
        $this->retrieveProducts();
    }


}
