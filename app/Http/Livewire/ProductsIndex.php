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
    public $searchByCategoryForFood;
    public $searchByCategoryForGrocery;

    public function render()
    {
        $this->retrieveProducts();

        return view('livewire.products-index');
    }


    public function retrieveProducts()
    {
        $products = Product::whereBranchId($this->branchId);
        $searchByCategoryForGrocery = $this->searchByCategoryForGrocery;
        if ( ! is_null($this->searchByCategoryForFood) && $this->searchByCategoryForFood != 'all') {
            $products = $products->where('category_id', $this->searchByCategoryForFood);
        } elseif ( ! is_null($searchByCategoryForGrocery) && $searchByCategoryForGrocery != 'all') {
            $products = $products->whereHas('categories', function ($query) use ($searchByCategoryForGrocery) {
                $query->where('category_id', $searchByCategoryForGrocery);
            });
        }

        $this->products = $products->latest()->get();
    }

    public function updatedSearchByCategory()
    {
        $this->retrieveProducts();
    }


}
