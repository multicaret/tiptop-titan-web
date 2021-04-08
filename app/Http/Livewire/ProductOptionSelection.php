<?php

namespace App\Http\Livewire;

use App\Models\ProductOptionSelection as ProductSelectionSelectionModel;
use Livewire\Component;

class ProductOptionSelection extends Component
{
    public ProductSelectionSelectionModel $selection;
    public $titleEn;
    public $titleKu;
    public $titleAr;
    public bool $markedAsDeleted = false;

    public function mount()
    {
        $this->titleEn = selectional($this->selection->translate('en'))->title;
        $this->titleKu = selectional($this->selection->translate('ku'))->title;
        $this->titleAr = selectional($this->selection->translate('ar'))->title;
    }

    protected $rules = [
        'selection.type' => 'required|numeric',
//        'selection.selection_type' => 'required|numeric',
//        'selection.min_number_of_selection' => 'nullable|numeric',
//        'selection.max_number_of_selection' => 'nullable|numeric',
        'titleEn' => 'string',
        'titleKu' => 'string',
        'titleAr' => 'string',
    ];


    public function updatedExtraPrice($newValue)
    {
//        $this->validate();
        $this->selection->type = $newValue;
        $this->selection->save();

        $this->emit('showToast', [
            'icon' => 'success',
            'message' => 'Type has been changed',
        ]);
    }


    public function updatedTitleEn($newValue)
    {
//        $this->validate();
        $this->selection->translateOrNew('en')->title = $newValue;
        $this->selection->save();

        $this->emit('showToast', [
            'icon' => 'success',
            'message' => 'English title has been changed',
        ]);
    }

    public function updatedTitleAr($newValue)
    {
//        $this->validate();
        $this->selection->translateOrNew('ar')->title = $newValue;
        $this->selection->save();

        $this->emit('showToast', [
            'icon' => 'success',
            'message' => 'Arabic title has been changed',
        ]);
    }

    public function updatedTitleKu($newValue)
    {
//        $this->validate();
        $this->selection->translateOrNew('ku')->title = $newValue;
        $this->selection->save();

        $this->emit('showToast', [
            'icon' => 'success',
            'message' => 'Kurdish title has been changed',
        ]);
    }

    public function delete()
    {
        $this->markedAsDeleted = true;
        $this->emitUp('selectionDeleted', ['selectionId' => selectional($this->selection)->id]);
    }
}
