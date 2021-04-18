<?php

namespace App\Http\Livewire\Products;

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
    ];

    public function updatedTitleEn($newValue)
    {
        $this->validate([
            'titleEn' => 'string',
        ]);
        $this->product->translateOrNew('en')->title = $newValue;
        $this->product->save();

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
        $this->product->translateOrNew('ar')->title = $newValue;
        $this->product->save();

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
        $this->product->translateOrNew('ku')->title = $newValue;
        $this->product->save();

        $this->emit('showToast', [
            'icon' => 'success',
            'message' => 'Kurdish title has been changed',
        ]);
    }

    public function updatedProductPrice($newValue)
    {
        $this->validate([
            'product.price' => 'required|numeric',
        ]);
        $this->product->price = Controller::convertNumbersToArabic($newValue);
        $this->product->save();

        $this->emit('showToast', [
            'icon' => 'success',
            'message' => 'Price has been changed',
        ]);
    }

    public function updatedProductOrderColumn($newValue)
    {
        $this->validate([
            'product.order_column' => 'required|numeric',
        ]);
        $this->product->order_column = Controller::convertNumbersToArabic($newValue);
        $this->product->save();

        $this->emit('showToast', [
            'icon' => 'success',
            'message' => 'Order column has been changed',
        ]);
    }

    public function updatedProductPriceDiscountAmount($newValue)
    {
        $this->validate([
            'product.price_discount_amount' => 'numeric',
        ]);
        $this->product->price_discount_amount = Controller::convertNumbersToArabic($newValue);
        $this->product->save();

        $this->validateDiscount();
    }

    public function updatedProductPriceDiscountByPercentage($newValue)
    {
        $this->validate([
            'product.price_discount_by_percentage' => 'boolean',
        ]);
        $this->product->price_discount_by_percentage = $newValue;
        $this->product->save();

        $this->validateDiscount();

    }

    public function render()
    {
        return view('livewire.products.row-edit');
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

        $this->emit('showToast', [
            'icon' => $productStoredEventIcon,
            'message' => $productStoredEventMessage,
        ]);
    }
}
