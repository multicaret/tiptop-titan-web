<?php

namespace App\Http\Livewire\Products;

use App\Models\Branch;
use App\Models\Product;
use App\Models\Taxonomy;
use Livewire\Component;
use Livewire\WithPagination;

class ProductsTable extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $branch;
    public $branchId;
    public $searchByCategoryForFood;
    public $searchByCategoryForGrocery;
    public $categories;

    public function render()
    {
        if ($this->branch->type == Branch::CHANNEL_FOOD_OBJECT) {
            $this->categories = $this->branch->menuCategories()->with('translations')->get();
        } else {
            $this->categories = Taxonomy::groceryCategories()->where('chain_id', $this->branch->chain->id)->get();
        }

        $products = $this->retrieveProducts();

        return view('livewire.products.table', compact('products'));
    }


    public function retrieveProducts()
    {
        $products = Product::with([
            'media',
            'categories',
        ])
                           ->whereBranchId($this->branchId);
        if ( ! is_null($this->searchByCategoryForFood) && $this->searchByCategoryForFood != 'all') {
            $products = $products->where('category_id', $this->searchByCategoryForFood);
        }

        $searchByCategoryForGrocery = $this->searchByCategoryForGrocery;
        if ( ! is_null($searchByCategoryForGrocery) && $searchByCategoryForGrocery != 'all') {
            $products = $products->whereHas('categories', function ($query) use ($searchByCategoryForGrocery) {
                $query->where('category_id', $searchByCategoryForGrocery);
            });
        }
        if ($this->branch->is_food) {
            $products = $products->latest('id')->get();
        } else {
            $products = $products->latest()
                                 ->paginate(30);
        }

        return $products;
    }

}
