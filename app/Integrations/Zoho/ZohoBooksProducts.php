<?php

namespace App\Integrations\Zoho;

use App\Models\Product;

class ZohoBooksProducts extends ZohoBooksClient
{
    public $product;

    public function __construct(Product $product)
    {
        parent::__construct();
        $this->product = $product;
    }

    public function createProduct()
    {

    }

    public function prepareProductData()
    {

    }



    public function createDeliveryItem()
    {

    }

    public function prepareDeliveryItemData()
    {

    }
}
