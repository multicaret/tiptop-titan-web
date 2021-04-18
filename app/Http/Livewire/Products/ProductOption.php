<?php

namespace App\Http\Livewire\Products;

use App\Models\ProductOption as ProductOptionModel;
use App\Models\ProductOptionSelection as ProductOptionSelectionModel;
use App\Models\Taxonomy;
use Livewire\Component;

class ProductOption extends Component
{
    public $option;
    public $titleEn;
    public $titleKu;
    public $titleAr;
    public bool $markedAsDeleted = false;

    public function mount()
    {
        $this->titleEn = optional($this->option->translate('en'))->title;
        $this->titleKu = optional($this->option->translate('ku'))->title;
        $this->titleAr = optional($this->option->translate('ar'))->title;
    }

    protected $rules = [
        'option.is_required' => 'required|boolean',
        'option.is_based_on_ingredients' => 'required|numeric',
        'option.input_type' => 'required|numeric',
        'option.type' => 'required|numeric',
        'option.selection_type' => 'required|numeric',
        'option.min_number_of_selection' => 'nullable|numeric',
        'option.max_number_of_selection' => 'nullable|numeric',
    ];


    public function updatedOptionsBasedOnIngredients($newValue)
    {
        $this->validate([
            'option.is_based_on_ingredients' => 'required|numeric',
        ]);
        $this->option->is_based_on_ingredients = $newValue;
        if ($this->option->is_based_on_ingredients) {
            $this->option->type = ProductOptionModel::TYPE_INCLUDING;
            $this->updatedSearch('');
        }
        $this->option->save();

        $this->emit('showToast', [
            'icon' => 'success',
            'message' => '"Is based on ingredients" has been changed',
        ]);
    }

    public function updatedOptionIsRequired($newValue)
    {
        $this->validate([
            'option.is_required' => 'required|boolean',
        ]);
        $this->option->is_required = $newValue;
        $this->option->save();

        $this->emit('showToast', [
            'icon' => 'success',
            'message' => '"Is Required" has been changed',
        ]);
    }

    public function updatedOptionType($newValue)
    {
        $this->validate([
            'option.type' => 'required|numeric',
        ]);
        $this->option->type = $newValue;
        if ($this->option->type == ProductOptionModel::TYPE_EXCLUDING) {
            $this->option->max_number_of_selection = 0;
        }
        $this->option->save();

        $this->emit('showToast', [
            'icon' => 'success',
            'message' => 'Type has been changed',
        ]);
    }

    public function updatedOptionInputType($newValue)
    {
        $this->validate([
            'option.input_type' => 'required|numeric',
        ]);
        $this->option->type = $newValue;
        if ($this->option->type == ProductOptionModel::SELECTION_TYPE_SINGLE_VALUE) {
            $this->option->min_number_of_selection = 1;
            $this->option->max_number_of_selection = 1;
        }
        $this->option->save();

        $this->emit('showToast', [
            'icon' => 'success',
            'message' => 'Type has been changed',
        ]);
    }

    public function updatedOptionSelectionType($newValue)
    {
        $this->validate([
            'option.selection_type' => 'required|numeric',
        ]);
        $this->option->selection_type = $newValue;
        $this->option->save();

        $this->emit('showToast', [
            'icon' => 'success',
            'message' => 'Selection type has been changed',
        ]);
    }

    public function updatedOptionMinNumberOfSelection($newValue)
    {
        $this->validate([
            'option.min_number_of_selection' => 'nullable|numeric',
        ]);
        $this->option->min_number_of_selection = $newValue;
        $this->option->save();

        $this->emit('showToast', [
            'icon' => 'success',
            'message' => 'Min number of selection has been changed',
        ]);
    }

    public function updatedOptionMaxNumberOfSelection($newValue)
    {
        $this->validate([
            'option.max_number_of_selection' => 'nullable|numeric',
        ]);
        $this->option->max_number_of_selection = $newValue;
        $this->option->save();

        $this->emit('showToast', [
            'icon' => 'success',
            'message' => 'Max number of selection has been changed',
        ]);
    }


    public function updatedTitleEn($newValue)
    {
        $this->validate([
            'titleEn' => 'string',
        ]);
        $this->option->translateOrNew('en')->title = $newValue;
        $this->option->save();

        $this->emit('showToast', [
            'icon' => 'success',
            'message' => 'English title has been changed',
        ]);
    }

    public function updatedTitleAr($newValue)
    {
        $this->validate([
            'titleAr' => 'string',
        ]);
        $this->option->translateOrNew('ar')->title = $newValue;
        $this->option->save();

        $this->emit('showToast', [
            'icon' => 'success',
            'message' => 'Arabic title has been changed',
        ]);
    }

    public function updatedTitleKu($newValue)
    {
        $this->validate([
            'titleKu' => 'string',
        ]);
        $this->option->translateOrNew('ku')->title = $newValue;
        $this->option->save();

        $this->emit('showToast', [
            'icon' => 'success',
            'message' => 'Kurdish title has been changed',
        ]);
    }


    public function render()
    {
        return view('livewire.products.single-option');
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


    public $ingredients;

    public $search;

    // Working on Ingredients
    public function updatedSearch($newValue)
    {
        $ingredients = Taxonomy::ingredients();
        if ( ! is_null($newValue)) {
            $ingredients = $ingredients->wherehas('translations', function ($query) use ($newValue) {
                $query->where('title', 'like', '%'.$newValue.'%');
            })->whereNotIn('id', $this->option->ingredients->pluck('id'))
                                       ->get();
        } else {
            $ingredients = [];
        }
        $this->ingredients = $ingredients;
    }

    public function selectIngredient($id, $title)
    {
        $this->option->ingredients()->attach($id);
        $this->updatedSearch($this->search);
    }

    public function removeIngredient($id)
    {
        $this->option->ingredients()->detach($id);
        $this->updatedSearch($this->search);
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
