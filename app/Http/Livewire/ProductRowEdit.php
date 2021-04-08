<?php

namespace App\Http\Livewire;

use App\Http\Controllers\Controller;
use Livewire\Component;

class ProductRowEdit extends Component
{
    public $product;
    public $titleEn;
    public $titleKu;
    public $titleAr;

    public function mount()
    {
        $this->titleEn = optional($this->product->translate('en'))->title;
        $this->titleKu = optional($this->product->translate('ku'))->title;
        $this->titleAr = optional($this->product->translate('ar'))->title;
    }

    protected $rules = [
        'product.price' => 'required|numeric',
        'product.order_column' => 'required|numeric',
        'product.price_discount_amount' => 'numeric',
        'product.price_discount_by_percentage' => 'boolean',
        'titleEn' => 'string',
        'titleKu' => 'string',
        'titleAr' => 'string',
    ];


    public function updatedTitleEn($newValue)
    {
        $this->product->translateOrNew('en')->title = $newValue;
        $this->product->save();

        $this->emit('productStored', [
            'icon' => 'success',
            'message' => 'English title has been changed',
        ]);
    }

    public function updatedTitleAr($newValue)
    {
        $this->product->translateOrNew('ar')->title = $newValue;
        $this->product->save();

        $this->emit('productStored', [
            'icon' => 'success',
            'message' => 'Arabic title has been changed',
        ]);
    }

    public function updatedTitleKu($newValue)
    {
        $this->product->translateOrNew('ku')->title = $newValue;
        $this->product->save();

        $this->emit('productStored', [
            'icon' => 'success',
            'message' => 'Arabic title has been changed',
        ]);
    }

    public function updatedProductPrice($newValue)
    {
        $this->product->price = Controller::convertNumbersToArabic($newValue);
        $this->product->save();

        $this->emit('productStored', [
            'icon' => 'success',
            'message' => 'Price has been changed',
        ]);
    }

    public function updatedProductOrderColumn($newValue)
    {
        $this->product->order_column = $newValue;
        $this->product->save();

        $this->emit('productStored', [
            'icon' => 'success',
            'message' => 'Order column has been changed',
        ]);
    }

    public function updatedProductPriceDiscountAmount($newValue)
    {
        $this->product->price_discount_amount = $newValue;
        $this->product->save();

        if ($this->product->price_discount_amount > 100 && $this->product->price_discount_by_percentage) {
            $this->product->price_discount_amount = 100;
            $this->product->save();
            $this->emit('productStored', [
                'icon' => 'error',
                'message' => 'Product will be free in this case',
            ]);
        } else {
            $this->emit('productStored', [
                'icon' => 'success',
                'message' => 'Price discount amount has been changed',
            ]);
        }
    }

    public function updatedProductPriceDiscountByPercentage($newValue)
    {
        $this->product->price_discount_by_percentage = $newValue == 'true';
        $this->product->save();


        if ($this->product->price_discount_amount > 100 && $this->product->price_discount_by_percentage) {
            $this->product->price_discount_amount = 100;
            $this->product->save();
            $this->emit('productStored', [
                'icon' => 'error',
                'message' => 'Product will be free in this case',
            ]);
        } else {
            $this->emit('productStored', [
                'icon' => 'success',
                'message' => 'Price discount by percentage has been changed',
            ]);
        }

    }

    public function render()
    {
        return view('livewire.product-row-edit');
    }
}
