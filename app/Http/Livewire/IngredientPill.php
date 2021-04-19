<?php

namespace App\Http\Livewire;

use App\Models\ProductOptionIngredient;
use Livewire\Component;

class IngredientPill extends Component
{
    public $ingredient;
    public $pivotId;
    public $isPriceShown;


    protected $rules = [
        'ingredient.price' => 'required|numeric',
    ];

    public function updatedIngredientPrice($newValue)
    {
        $model = ProductOptionIngredient::find($this->pivotId);
        $model->price = $newValue;
        $model->save();

        $this->emit('showToast', [
            'icon' => 'success',
            'message' => 'Price has been changed',
        ]);
    }

    public function render()
    {
        return view('livewire.ingredient-pill');
    }
}
