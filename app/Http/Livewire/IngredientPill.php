<?php

namespace App\Http\Livewire;

use App\Http\Controllers\Controller;
use App\Models\ProductOptionIngredient;
use Livewire\Component;

class IngredientPill extends Component
{
    public $ingredient;
    public $pivotId;
    public $isPriceShown;
    public $price;


    protected $rules = [
        'price' => 'required|numeric',
    ];

    public function updatedPrice($newValue)
    {
        $model = ProductOptionIngredient::find($this->pivotId);
        $model->price = Controller::convertNumbersToArabic($newValue);
        $model->save();
        $this->price = $model->price;

        $this->emit('showToast', [
            'icon' => 'success',
            'message' => 'Price has been changed',
        ]);
    }

    public function removeIngredient()
    {
        $this->emitUp('ingredientPillDeleted', ['ingredientPillId' => $this->ingredient->id]);
    }

    public function render()
    {
        return view('livewire.ingredient-pill');
    }
}
