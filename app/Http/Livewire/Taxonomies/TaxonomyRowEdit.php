<?php

namespace App\Http\Livewire\Taxonomies;

use App\Http\Controllers\Controller;
use Livewire\Component;

class TaxonomyRowEdit extends Component
{
    public $taxonomy;
    public $titleEn;
    public $titleKu;
    public $titleAr;

    public function mount()
    {
        $this->titleEn = optional($this->taxonomy->translate('en'))->title;
        $this->titleKu = optional($this->taxonomy->translate('ku'))->title;
        $this->titleAr = optional($this->taxonomy->translate('ar'))->title;
    }

//    protected $rules = [];

    public function updatedTitleEn($newValue)
    {
        $this->validate([
            'titleEn' => 'string',
        ]);
        $this->taxonomy->translateOrNew('en')->title = $newValue;
        $this->taxonomy->save();

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
        $this->taxonomy->translateOrNew('ar')->title = $newValue;
        $this->taxonomy->save();

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
        $this->taxonomy->translateOrNew('ku')->title = $newValue;
        $this->taxonomy->save();

        $this->emit('showToast', [
            'icon' => 'success',
            'message' => 'Kurdish title has been changed',
        ]);
    }

    public function updatedTaxonomyOrderColumn($newValue)
    {
        $this->validate([
            'taxonomy.order_column' => 'required|numeric',
        ]);
        $this->taxonomy->order_column = Controller::convertNumbersToArabic($newValue);
        $this->taxonomy->save();

        $this->emit('showToast', [
            'icon' => 'success',
            'message' => 'Order column has been changed',
        ]);
    }


    public function render()
    {
        return view('livewire.taxonomies.row-edit');
    }
}
