<?php

namespace App\Http\Livewire\Products;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class ProductsIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $branch;
    public $branchId;
    public $searchByCategoryForFood;
    public $searchByCategoryForGrocery;

    public function render()
    {
        $products = $this->retrieveProducts();

        return view('livewire.products.products-index', compact('products'));
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
        $products = $products->latest()
                             ->paginate(10);

        return $products;
    }

}
