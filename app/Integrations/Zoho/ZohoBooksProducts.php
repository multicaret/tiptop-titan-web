<?php

namespace App\Integrations\Zoho;

use App\Models\Branch;
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

      //  info($productData);
        return $this->postRequest('items?organization_id='.$this->organization_id,$productData);


    }

    public function prepareProductData()
    {
        $categories = $this->product->categories;
        $categories = $this->product->type == Product::CHANNEL_GROCERY_OBJECT ? $categories->pluck('translations')->flatten(1)->where('locale','en')->pluck('title')->toArray() : '';
        $purchase_account_id = $this->product->type == Product::CHANNEL_GROCERY_OBJECT ? $this->market_costs_account_id : ' ';
        $item_account_type = $this->product->type == Product::CHANNEL_GROCERY_OBJECT ?  'inventory' : 'sales';
        $branch_zoho_books_id = $this->product->branch->zoho_books_id;
        $product_type = $this->product->type == Product::CHANNEL_GROCERY_OBJECT ? 'Market' : 'Restaurant';
        $items_inventory_account_id = $this->product->type == Product::CHANNEL_GROCERY_OBJECT ? $this->items_inventory_account_id : ' ';
        $account_id = $this->product->type == Product::CHANNEL_GROCERY_OBJECT ? $this->market_sales_account_id : $this->restaurant_sales_account_id;
        $product_title = $this->product->type == Product::CHANNEL_GROCERY_OBJECT ? $this->product->title .' '. $this->product->description : $this->product->title;

        return [
            'name' => $product_title,
            'status' => 'Active',
            'unit' => 'item',
            'product_type' => $this->product->type == Product::CHANNEL_GROCERY_OBJECT ? 'goods' : 'service',
            'sku' => $this->product->type == Product::CHANNEL_GROCERY_OBJECT ? Product::where('id',$this->product->cloned_from_product_id)->firstOrFail()->sku : $this->product->id,
            'purchase_account_id' => $purchase_account_id,
            'account_id' => $account_id,
            'inventory_account_id' => $items_inventory_account_id,
            'item_type' => $item_account_type,
            'vendor_id' => $branch_zoho_books_id,
            'custom_fields' => [
                [
                    'api_name' => 'cf_type_1',
                    'value' => $product_type
                ],
                [
                    'api_name' => 'cf_type',
                    'value' => $branch_zoho_books_id
                ],
                [
                    'api_name' => 'cf_categories_test',
                    'value' => $categories
                ]
            ],

        ];
    }


}
