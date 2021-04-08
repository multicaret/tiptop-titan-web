<?php

namespace App\Http\Livewire;

use App\Models\Product;
use App\Models\ProductOption as ProductOptionModel;
use Livewire\Component;

class ProductOptionsIndex extends Component
{
    public Product $product;

    public function render()
    {
        return view('livewire.product-options-index');
    }

    public function addNewOption()
    {
        $option = new ProductOptionModel();
        $option->product_id = $this->product->id;
        $option->save();

        $this->product->load('options');
    }


    protected $listeners = ['optionDeleted' => 'reloadOptions'];

    public function reloadOptions($params)
    {
        $option = $this->product->options()->where('id', $params['optionId']);
        if ( ! is_null($option)) {
            $option->delete();
        }
        $this->product->load('options');
        $this->emit('showToast', [
            'icon' => 'success',
            'message' => 'Option has been deleted',
        ]);
    }
}
