<?php

namespace App\Http\Livewire;

use Livewire\Component;

class ProductRowEdit extends Component
{
    public $product;


    protected $rules = [
        'product.price' => 'required|numeric',
//        'product.price' => 'required|string|max:500',
    ];

    public function updatedProductPrice($newValue)
    {
        dd($newValue);
    }

    public function render()
    {
        return view('livewire.product-row-edit');
    }
}
