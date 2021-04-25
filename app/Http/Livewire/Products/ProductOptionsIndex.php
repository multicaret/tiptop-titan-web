<?php

namespace App\Http\Livewire\Products;

use App\Models\Product;
use App\Models\ProductOption as ProductOptionModel;
use Livewire\Component;

class ProductOptionsIndex extends Component
{
    public Product $product;

    public function render()
    {
        return view('livewire.products.options-index');
    }

    public function addNewOption()
    {
        $option = new ProductOptionModel();
        $option->product_id = $this->product->id;
        $option->save();

        $this->product->load('options');
    }


    protected $listeners = [
//        'optionCloned' => 'cloneOption'
        'optionDeleted' => 'reloadOptions',
    ];

    /*  public function cloneOption($params)
      {
          $oldModel = $this->product->options()->where('id', $params['optionId'])->first();
          $newModel = new \App\Models\ProductOption();
          $newModel->product_id = $oldModel->product_id;
          $oldModel->save();
          $this->product->load('options');
          $this->emit('showToast', [
              'icon' => 'success',
              'message' => 'Option has been cloned successfully',
          ]);
      }*/

    public function reloadOptions($params)
    {

        if ( ! is_null($params['optionId'])) {
            ProductOptionModel::find($params['optionId'])->delete();
            /*if ( ! is_null($option)) {
                $option;
            }*/
            $this->product->load('options');
            $this->emit('showToast', [
                'icon' => 'success',
                'message' => 'Option has been deleted',
            ]);
        }
    }
}
