<?php

namespace App\Integrations\Zoho;

use App\Models\Branch;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\Types\Parent_;

class ZohoBooksInvoices extends ZohoBooksClient
{
    public $order;

    public function __construct(Order $order)
    {
        parent::__construct();
        $this->order = $order;
    }

    public function createInvoice()
    {
        $invoiceData = $this->prepareInvoiceData();

        return $this->postRequest('invoices?organization_id='.$this->organization_id, $invoiceData);

    }

    public function prepareInvoiceData()
    {
        $customer_id = $this->getCustomerByZohoCrmId();
        if ( ! $customer_id) {

            return false;
        }

        $inline_items = [];
        foreach ($this->order->cart->cartProducts as $cartProduct) {
            $price = $cartProduct->product->discounted_price + $cartProduct->options_price;

            $inline_items[] = [
                'quantity' => $cartProduct->quantity,
                'item_id' => $cartProduct->product->zoho_books_id,
                //'discount_amount' => $branch_dish->discount_method_amount,
                'rate' => $price,
            ];
        }
        if ( ! empty($this->order->branch->zoho_books_tiptop_delivery_item_id) && ! empty($this->order->branch->zoho_books_delivery_item_id)) {
            $inline_items[] = [
                'quantity' => 1,
                'item_id' => $this->order->is_delivery_by_tiptop ? $this->order->branch->zoho_books_tiptop_delivery_item_id : $this->order->branch->zoho_books_delivery_item_id,
                'rate' => $this->order->delivery_fee,
            ];
        }

        $adjustment = 0;
        if ($this->order->grand_total_before_agent_manipulation > $this->order->grand_total)
        {
            $adjustment = -1 * $this->order->grand_total_before_agent_manipulation - $this->order->grand_total;
        }
        if ($this->order->grand_total_before_agent_manipulation > 0 && $this->order->grand_total_before_agent_manipulation < $this->order->grand_total)
        {
            $adjustment =  $this->order->grand_total - $this->order->grand_total_before_agent_manipulation;
        }

        return [
            'customer_id' => $customer_id,
            'invoice_number' => $this->order->reference_code,
            'date' => Carbon::now()->format('Y-m-d'),
            'discount' => $this->order->coupon_discount_amount,
            'discount_type' => 'entity_level',
            'send' => true,
            'line_items' => $inline_items,
            'adjustment'   => $adjustment,
            'custom_fields' => [
                [
                    'api_name' => 'cf_who_recieve_the_payment',
                    'value' => $this->order->is_delivery_by_tiptop ? 'TipTop' : 'Resturant'
                ],
                [
                    'api_name' => 'cf_coupon_code',
                    'value' => $this->order->coupon_discount_amount > 0 ? $this->order->coupon->name : ''
                ],
                [
                    'api_name' => 'cf_restaurant',
                    'value' => (string) $this->order->branch->zoho_books_id
                ],
            ],
        ];
    }

    public function getCustomerByZohoCrmId()
    {

        $customerRecord = $this->getRequest('contacts?organization_id='.$this->organization_id,
            ['zcrm_contact_id' => $this->order->user->zoho_crm_id]);

        if ( ! $customerRecord || ! isset($customerRecord['contacts'][0])) {
            info('create zoho books invoice error', [
                'user id' => $this->order->user->id,
                'error' => 'zoho_crm_id not found',
                'response' => $customerRecord->json()
            ]);

            return false;
        }

        return $customerRecord['contacts'][0]['contact_id'];

    }

    public function createPayment()
    {
        $paymentData = $this->preparePaymentData();

        return $this->postRequest('customerpayments?organization_id='.$this->organization_id, $paymentData);

    }

    public function preparePaymentData()
    {
        $customer_id = $this->getCustomerByZohoCrmId();
        if ( ! $customer_id) {
            info('create zoho books invoice error', [
                'user id' => $this->order->user->id,
                'error' => 'zoho_crm_id not found'
            ]);

            return false;
        }

        return [
            'customer_id' => $customer_id,
            'currency_code' => 'iqd',
            'amount' => $this->order->grand_total,
            'amount_applied' => $this->order->grand_total,
            'payment_mode' => 'cash',
            'reference_number' => $this->order->reference_code,
            'invoice_id' => $this->order->zoho_books_invoice_id,
            'date' => Carbon::now()->format('Y-m-d'),
            //todo: handle if online payment was made
            'account_id' => $this->order->is_delivery_by_tiptop ? $this->petty_cash_account_id : $this->order->branch->zoho_books_account_id,
            'custom_fields' => [
                [
                    'api_name' => 'cf_who_recieve_the_payment',
                    'value' => $this->order->is_delivery_by_tiptop ? 'TipTop' : 'Resturant'
                ],
                [
                    'api_name' => 'cf_courier_name',
                    'value' => ! empty($this->order->driver->name) ? $this->order->driver->name : ''
                ],
            ],
        ];
    }

    public function applyPaymentCredit()
    {
        $data = $this->prepareAppliedPaymentData();

        return $this->postRequest('invoices/'.$this->order->zoho_books_invoice_id.'/credits?organization_id='.$this->organization_id, $data);
    }

    public function prepareAppliedPaymentData()
    {
        return  [
            'invoice_payments' => [
                ['payment_id' => $this->order->zoho_books_payment_id, 'amount_applied' => $this->order->grand_total]
            ]
        ];
    }
}
