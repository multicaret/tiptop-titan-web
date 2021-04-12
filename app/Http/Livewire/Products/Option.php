<?php

namespace App\Http\Livewire\Products;

use App\Models\ProductOption as ProductOptionModel;
use App\Models\ProductOptionSelection as ProductOptionSelectionModel;
use App\Models\Taxonomy;
use Livewire\Component;

class Option extends Component
{
    public $option;
    public $titleEn;
    public $titleKu;
    public $titleAr;
    public bool $markedAsDeleted = false;
    public $ingredientCategories;

    public $selectedIngredients = [];

    public function mount()
    {
        $this->ingredientCategories = Taxonomy::ingredientCategories()->get();
        $this->titleEn = optional($this->option->translate('en'))->title;
        $this->titleKu = optional($this->option->translate('ku'))->title;
        $this->titleAr = optional($this->option->translate('ar'))->title;
    }

    protected $rules = [
        'option.is_based_on_ingredients' => 'required|numeric',
        'option.type' => 'required|numeric',
        'option.selection_type' => 'required|numeric',
        'option.min_number_of_selection' => 'nullable|numeric',
        'option.max_number_of_selection' => 'nullable|numeric',
        'titleEn' => 'string',
        'titleKu' => 'string',
        'titleAr' => 'string',
    ];


    public function updatedIsBasedOnIngredients($newValue)
    {
//        $this->validate();
        $this->option->is_based_on_ingredients = $newValue;
        if ($this->option->is_based_on_ingredients) {
            $this->option->type = ProductOptionModel::TYPE_INCLUDING;
        }
        $this->option->save();

        $this->emit('showToast', [
            'icon' => 'success',
            'message' => '"Is based on ingredients" has been changed',
        ]);
    }

    public function updatedOptionType($newValue)
    {
//        $this->validate();
        $this->option->type = $newValue;
        $this->option->save();

        $this->emit('showToast', [
            'icon' => 'success',
            'message' => 'Type has been changed',
        ]);
    }

    public function updatedOptionSelectionType($newValue)
    {
//        $this->validate();
        $this->option->selection_type = $newValue;
        $this->option->save();

        $this->emit('showToast', [
            'icon' => 'success',
            'message' => 'Selection type has been changed',
        ]);
    }

    public function updatedOptionMinNumberOfSelection($newValue)
    {
//        $this->validate();
        $this->option->min_number_of_selection = $newValue;
        $this->option->save();

        $this->emit('showToast', [
            'icon' => 'success',
            'message' => 'Min number of selection has been changed',
        ]);
    }

    public function updatedOptionMaxNumberOfSelection($newValue)
    {
//        $this->validate();
        $this->option->max_number_of_selection = $newValue;
        $this->option->save();

        $this->emit('showToast', [
            'icon' => 'success',
            'message' => 'Max number of selection has been changed',
        ]);
    }


    public function updatedTitleEn($newValue)
    {
//        $this->validate();
        $this->option->translateOrNew('en')->title = $newValue;
        $this->option->save();

        $this->emit('showToast', [
            'icon' => 'success',
            'message' => 'English title has been changed',
        ]);
    }

    public function updatedTitleAr($newValue)
    {
//        $this->validate();
        $this->option->translateOrNew('ar')->title = $newValue;
        $this->option->save();

        $this->emit('showToast', [
            'icon' => 'success',
            'message' => 'Arabic title has been changed',
        ]);
    }

    public function updatedTitleKu($newValue)
    {
//        $this->validate();
        $this->option->translateOrNew('ku')->title = $newValue;
        $this->option->save();

        $this->emit('showToast', [
            'icon' => 'success',
            'message' => 'Kurdish title has been changed',
        ]);
    }

    public function updatedSelectedIngredients($newValue)
    {
//        $this->validate();
        $this->option->translateOrNew('ku')->title = $newValue;
        $this->option->save();

        $this->emit('showToast', [
            'icon' => 'success',
            'message' => 'Kurdish title has been changed',
        ]);
    }


    public function render()
    {
        return view('livewire.products.option');
    }

    /*public function clone()
    {
        $this->emitUp('optionCloned', ['optionId' => optional($this->option)->id]);
    }*/


    public function delete()
    {
        $this->markedAsDeleted = true;
        $this->emitUp('optionDeleted', ['optionId' => optional($this->option)->id]);
    }


    protected $listeners = [
//        'optionCloned' => 'cloneOption'
        'selectionDeleted' => 'reloadSelections',
    ];


    public function reloadSelections($params)
    {
        $selection = $this->option->selections()->where('id', $params['selectionId']);
        if ( ! is_null($selection)) {
            $selection->delete();
        }
        $this->option->load('selections');
        $this->emit('showToast', [
            'icon' => 'success',
            'message' => 'Selection has been deleted',
        ]);
    }


    public function addNewSelection()
    {
        $selection = new ProductOptionSelectionModel();
        $selection->product_option_id = $this->option->id;
        $selection->product_id = $this->option->product_id;
        $selection->save();

        $this->option->load('selections');
    }
}