<?php

namespace App\Integrations\Zoho;

use App\Models\Branch;
use phpDocumentor\Reflection\Types\Parent_;

class ZohoBooksBranches extends ZohoBooksClient
{
    public $branch;

    public function __construct(Branch $branch)
    {
        parent::__construct();
        $this->branch = $branch;
    }

    public function createBranch()
    {
        $branchData = $this->prepareBranchData();

        return $this->postRequest('contacts?organization_id='.$this->organization_id, $branchData);

    }

    public function prepareBranchData()
    {
        $branch_contact = explode(' ', $this->branch->contacts->first()->name);
        $first_name = null;
        $last_name = null;
        if (count($branch_contact) == 1) {
            $first_name = $branch_contact[0];
        }
        if (count($branch_contact) == 2) {
            $first_name = $branch_contact[0];
            $last_name = $branch_contact[1];
        }
        if (count($branch_contact) == 3) {
            $first_name = $branch_contact[0].' '.$branch_contact[1];
            $last_name = $branch_contact[2];
        }

        return [
            'contact_name' => $this->branch->title,
            'company_name' => $this->branch->title,
            'contact_type' => 'vendor',
            'cf_vendor_type' => ! $this->branch->type == Branch::CHANNEL_FOOD_OBJECT ? 'Restaurant' : 'Market',
            'phone' => $this->branch->primary_phone_number,
            'billing_address' => [
                "attention" => $branch_contact,
                "address" => $this->branch->full_address,
                "city" => $this->branch->city->translate('en')->name,
                "country" => $this->branch->city->country->translate('en')->name,
                "phone" => $this->branch->primary_phone_number
            ],
            'contact_persons' => [
                [
                    "salutation" => "Mr",
                    "first_name" => $first_name,
                    "last_name" => $last_name,
                    "phone" => $this->branch->primary_phone_number,
                    "is_primary_contact" => true,
                    "enable_portal" => true
                ]
            ]
        ];
    }

    public function createBranchAccount()
    {
        $branchAccountData = $this->prepareBranchAccountData();

        return $this->postRequest('chartofaccounts?organization_id='.$this->organization_id, $branchAccountData);

    }

    public function prepareBranchAccountData()
    {
        return [
            'account_name' => 'CA '.$this->branch->title,
            'account_type' => 'cash',
            'parent_account_id' => '2511463000001978489',
            'show_on_dashboard' => true,
            "can_show_in_ze" => true
        ];


    }


    public function createDeliveryItem()
    {
        $deliveryItemData = $this->prepareDeliveryItemData();

        return $this->postRequest('items?organization_id='.$this->organization_id, $deliveryItemData);


    }

    public function prepareDeliveryItemData()
    {
        $delivery_item_account_id = '2511463000002639051';

        return [
            'name' => 'delivery',
            'status' => 'Active',
            'unit' => 'item',
            'product_type' => 'service',
            'sku' => 'delivery-'.$this->branch->id,
            'account_id' => $delivery_item_account_id,
            'item_type' => 'sales',
            'cf_type' => $this->branch->zoho_books_id,
            'vendor_id' => $this->branch->zoho_books_id,
            'cf_type_1' => 'Delivery_Fee',
            'custom_fields' => [
                [
                    'api_name' => 'cf_type_1',
                    'value' => 'Delivery_Fee'
                ],
                [
                    'api_name' => 'cf_type',
                    'value' => $this->branch->zoho_books_id
                ]
            ],
        ];
    }

    public function createTiptopDeliveryItem()
    {
        $tiptopDeliveryItemData = $this->prepareTiptopDeliveryItemData();

        return $this->postRequest('items?organization_id='.$this->organization_id, $tiptopDeliveryItemData);


    }

    public function prepareTiptopDeliveryItemData()
    {
        $tiptop_delivery_item_account_id = '2511463000002639009';

        return [
            'name' => 'delivery',
            'status' => 'Active',
            'unit' => 'item',
            'product_type' => 'service',
            'sku' => 'tiptop-delivery-'.$this->branch->id,
            'account_id' => $tiptop_delivery_item_account_id,
            'item_type' => 'sales',
            'cf_type' => $this->branch->zoho_books_id,
            'vendor_id' => $this->branch->zoho_books_id,
            'cf_type_1' => 'Delivery_Fee',
            'custom_fields' => [
                [
                    'api_name' => 'cf_type_1',
                    'value' => 'Delivery_Fee'
                ],
                [
                    'api_name' => 'cf_type',
                    'value' => $this->branch->zoho_books_id
                ]
            ],
        ];
    }
}
