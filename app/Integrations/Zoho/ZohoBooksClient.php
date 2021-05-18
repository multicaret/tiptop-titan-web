<?php

namespace App\Integrations\Zoho;

use Asciisd\Zoho\Zoho;
use Illuminate\Support\Facades\Http;
use zcrmsdk\crm\setup\restclient\ZCRMRestClient;
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
        $this->market_costs_account_id = config('services.zoho.market_costs_account');;
        $this->access_token = $this->getAccessToken();

    }

    protected function getAccessToken()
    {
        ZCRMRestClient::initialize(Zoho::zohoOptions());
        $oAuthClient = ZohoOAuth::getClientInstance();

        return $oAuthClient->getAccessToken(config('services.zoho.current_user_email'));
    }

    public function getRequest($endpoint, $data = [])
    {

        try {

            $url = $this->base_url.$endpoint;
            $response = Http::withHeaders([
                'authorization' => 'Zoho-oauthtoken '.$this->token,
                'cache-control' => 'no-cache',
                'content-type' => 'application/x-www-form-urlencoded',
                'Connection' => 'keep-alive'
            ])->get($url, $data);

            if ( ! $response->successful()) {
                $response->throw();

                return false;
            } else {
                $fail = $response['code'];
                if ($fail) {
                    info($response['message']);
                    info('validation|service error');

                    return false;
                }

                return $response;
            }
        } catch (\Exception $ex) {

            return false;
        }

    }

    public function postRequest($endpoint, $data = [])
    {

        try {

            $url = $this->base_url.$endpoint;
            $json = ! empty($data) ? json_encode($data) : [];
            $response = Http::withHeaders([
                'authorization' => 'Zoho-oauthtoken '.$this->token,
                'cache-control' => 'no-cache',
                'content-type' => 'application/x-www-form-urlencoded',
                'Connection' => 'keep-alive'
            ])->asForm()->post($url, [
                'JSONString' => $json,
            ]);

            return $response;

        } catch (\Exception $ex) {
            info('Zoho client exception',[
                'error' => $ex->getMessage()
            ]);

            return false;
        }

    }
}
