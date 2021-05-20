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
        $productData = $this->prepareProductData();

        return $this->postRequest('items?organization_id='.$this->organization_id,$productData);


    }

    public function prepareProductData()
    {
        $categories = $this->product->categories;
        $categories->load('translations');
        $categories = $this->product->type == Product::CHANNEL_GROCERY_OBJECT ? $categories->pluck('translations')->flatten(1)->where('locale','en')->pluck('title')->toArray() : '';
        $purchaseAccount_id = $this->product->type == Product::CHANNEL_GROCERY_OBJECT ? '2511463000000034003' : ' ';
        $itemAccountType = $this->product->type == Product::CHANNEL_GROCERY_OBJECT ?  'sales_and_purchases' : 'sales';
        $branchZohoBooksId = $this->product->branch->zoho_books_id;
        $productType = $this->product->type == Product::CHANNEL_GROCERY_OBJECT ? 'Market' : 'Restaurant';
        $items_inventory_account_id = $this->product->type == Product::CHANNEL_GROCERY_OBJECT ? '2511463000000034001' : ' ';
        $account_id = $this->product->type == Product::CHANNEL_GROCERY_OBJECT ? '2511463000001867005' : '2511463000001867001';
        return [
            'name' => $this->product->title,
            'status' => 'Active',
            'unit' => 'item',
            'product_type' => $this->product->type == Product::CHANNEL_GROCERY_OBJECT ? 'goods' : 'service',
            'sku' => $this->product->id,
            'purchase_account_id' => $purchaseAccount_id,
            'account_id' => $account_id,
            'inventory_account_id' => $items_inventory_account_id,
            'item_type' => $itemAccountType,
            'vendor_id' => $branchZohoBooksId,
            'custom_fields' => [
                [
                    'api_name' => 'cf_type_1',
                    'value' => $productType
                ],
                [
                    'api_name' => 'cf_type',
                    'value' => $branchZohoBooksId
                ],
                [
                    'api_name' => 'cf_categories_test',
                    'value' => $categories
                ]
            ],

        ];
    }



    public function createDeliveryItem()
    {

    }

    public function prepareDeliveryItemData()
    {

    }
}
