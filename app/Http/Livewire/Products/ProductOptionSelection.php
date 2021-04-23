<?php

namespace App\Http\Livewire\Products;

use App\Http\Controllers\Controller;
use Livewire\Component;

class ProductOptionSelection extends Component
{
    public $selection;
    public $titleEn;
    public $titleKu;
    public $titleAr;
    public bool $markedAsDeleted = false;

    protected $listeners = [
        'deleteSelection',
    ];

    public function render()
    {
        return view('livewire.products.option-selection');
    }

    public function mount()
    {
        $this->titleEn = optional($this->selection->translate('en'))->title;
        $this->titleKu = optional($this->selection->translate('ku'))->title;
        $this->titleAr = optional($this->selection->translate('ar'))->title;
    }

    protected $rules = [
        'selection.price' => 'numeric',
    ];

    public function updatedSelectionPrice($newValue)
    {
        $this->validate([
            'selection.price' => 'numeric',
        ]);
        $newValue = Controller::convertNumbersToArabic($newValue);
        if ($newValue < 0) {
            $newValue = 0;
        }
        $this->selection->price = $newValue;
        $this->selection->save();

        $this->emit('showToast', [
            'icon' => 'success',
            'message' => 'Price has been changed',
        ]);
    }


    public function updatedTitleEn($newValue)
    {
        $this->validate([
            'titleEn' => 'string',
        ]);
        $this->selection->translateOrNew('en')->title = $newValue;
        $this->selection->save();

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
        $this->selection->translateOrNew('ar')->title = $newValue;
        $this->selection->save();

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
        $this->selection->translateOrNew('ku')->title = $newValue;
        $this->selection->save();

        $this->emit('showToast', [
            'icon' => 'success',
            'message' => 'Kurdish title has been changed',
        ]);
    }

    public function deleteSelection()
    {
        $this->markedAsDeleted = true;
        $this->emitUp('selectionDeleted', ['selectionId' => optional($this->selection)->id]);
    }

    public function triggerConfirmDeleting()
    {
        $this->confirm('Are you sure?', [
            'toast' => false,
            'position' => 'center',
            'showConfirmButton' => true,
            'onConfirmed' => 'deleteSelection',
//            'onCancelled' => 'cancelled'
        ]);
    }
}
