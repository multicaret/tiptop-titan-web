<?php

namespace App\Integrations\Zoho;

use Asciisd\Zoho\Zoho;
use Illuminate\Support\Facades\Http;
use zcrmsdk\crm\setup\restclient\ZCRMRestClient;
use zcrmsdk\crm\utility\ZCRMConfigUtil;
use zcrmsdk\oauth\ZohoOAuth;

class ZohoBooksClient
{
    public $access_token;

    public $refresh_token;

    public $base_url;

    public $client_secret;

    public $client_id;

    public $organization_id;

    public $redirect_uri;

    public $petty_cash_account_id;

    public $restaurant_sales_account_id;

    public $market_sales_account_id;

    public $market_costs_account_id;

    public $purchase_account_id;

    public $items_inventory_account_id;

    public function __construct()
    {
        $this->base_url = config('services.zoho.base_url');
        $this->client_secret = config('services.zoho.client_secret');
        $this->client_id = config('services.zoho.client_id');
        $this->organization_id = config('services.zoho.organization_id');
        $this->redirect_uri = config('services.zoho.redirect_uri');
        $this->petty_cash_account_id = config('services.zoho.petty_cash_account');
        $this->restaurant_sales_account_id = config('services.zoho.restaurant_sales_account');
        $this->market_sales_account_id = config('services.zoho.market_sales_account');
        $this->market_costs_account_id = config('services.zoho.market_costs_account');
        $this->purchase_account_id = config('services.zoho.purchase_account_id');
        $this->items_inventory_account_id = config('services.zoho.items_inventory_account');
        $this->access_token = $this->getAccessToken();
    }

    protected function getAccessToken()
    {
        ZCRMRestClient::initialize(Zoho::zohoOptions());
        return ZCRMConfigUtil::getAccessToken();
    }

    public function getRequest($endpoint, $data = [])
    {


            $url = $this->base_url.$endpoint;
            $response = Http::withHeaders([
                'authorization' => 'Zoho-oauthtoken '.$this->access_token,
                'cache-control' => 'no-cache',
                'content-type' => 'application/x-www-form-urlencoded',
                'Connection' => 'keep-alive'
            ])->get($url, $data);


            return $response;
    }

    public function postRequest($endpoint, $data = [])
    {


            $url = $this->base_url.$endpoint;
            $json = ! empty($data) ? json_encode($data) : [];

            $response = Http::withHeaders([
                'authorization' => 'Zoho-oauthtoken '.$this->access_token,
                'cache-control' => 'no-cache',
                'content-type' => 'application/x-www-form-urlencoded',
                'Connection' => 'keep-alive'
            ])->asForm()->post($url, [
                'JSONString' => $json,
            ]);

            return $response;



    }
}
