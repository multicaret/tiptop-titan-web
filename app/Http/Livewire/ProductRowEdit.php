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
        $this->validate();
        $this->product->translateOrNew('en')->title = $newValue;
        $this->product->save();

        $this->emit('productStored', [
            'icon' => 'success',
            'message' => 'English title has been changed',
        ]);
    }

    public function updatedTitleAr($newValue)
    {
        $this->validate();
        $this->product->translateOrNew('ar')->title = $newValue;
        $this->product->save();

        $this->emit('productStored', [
            'icon' => 'success',
            'message' => 'Arabic title has been changed',
        ]);
    }

    public function updatedTitleKu($newValue)
    {
        $this->validate();
        $this->product->translateOrNew('ku')->title = $newValue;
        $this->product->save();

        $this->emit('productStored', [
            'icon' => 'success',
            'message' => 'Arabic title has been changed',
        ]);
    }

    public function updatedProductPrice($newValue)
    {
        $this->validate();
        $this->product->price = Controller::convertNumbersToArabic($newValue);
        $this->product->save();

        $this->emit('productStored', [
            'icon' => 'success',
            'message' => 'Price has been changed',
        ]);
    }

    public function updatedProductOrderColumn($newValue)
    {
        $this->validate();
        $this->product->order_column = Controller::convertNumbersToArabic($newValue);
        $this->product->save();

        $this->emit('productStored', [
            'icon' => 'success',
            'message' => 'Order column has been changed',
        ]);
    }

    public function updatedProductPriceDiscountAmount($newValue)
    {
        $this->validate();
        $this->product->price_discount_amount = Controller::convertNumbersToArabic($newValue);
        $this->product->save();

        $this->validateDiscount();
    }

    public function updatedProductPriceDiscountByPercentage($newValue)
    {
        $this->validate();
        $this->product->price_discount_by_percentage = $newValue;
        $this->product->save();

        $this->validateDiscount();

    }

    public function render()
    {
        return view('livewire.product-row-edit');
    }

    private function validateDiscount(): void
    {
        $productStoredEventIcon = 'error';
        if ($this->product->price_discount_amount > 100 && $this->product->price_discount_by_percentage) {
            $this->product->price_discount_amount = 100;
            $this->product->save();
            $productStoredEventMessage = 'Product will be free in this case';
        } elseif ($this->product->price_discount_amount > $this->product->price && ! $this->product->price_discount_by_percentage) {
            $this->product->price_discount_amount = $this->product->price;
            $this->product->save();
            $productStoredEventMessage = 'Product will be free in this case';
        } else {
            $productStoredEventIcon = 'success';
            $productStoredEventMessage = 'Price discount amount has been changed';
        }

        $this->emit('productStored', [
            'icon' => $productStoredEventIcon,
            'message' => $productStoredEventMessage,
        ]);
    }
}
