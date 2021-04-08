<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Livewire\Component;

class ProductOptions extends Component
{
    public Product $selectedProduct;
    public bool $showModal = false;

    public function show($id)
    {
        $this->showModal = true;
        $this->selectedProduct = Product::find($id);
    }

    public function render()
    {
        return view('livewire.product-options');
    }
}
