<?php

namespace App\Console\Commands;

use App\Jobs\Zoho\ApplyPaymentCreditJob;
use App\Jobs\Zoho\CreateBranchAccountJob;
use App\Jobs\Zoho\CreateDeliveryItemJob;
use App\Jobs\Zoho\CreateInvoiceJob;
use App\Jobs\Zoho\CreatePaymentJob;
use App\Jobs\Zoho\CreateTipTopDeliveryItemJob;
use App\Jobs\Zoho\SyncBranchJob;
use App\Models\Branch;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Bus;

class ExportOrdersToZoho extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:export-to-zoho';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatches jobs to export orders to zoho books';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if ( ! config('services.zoho.zoho_enabled')) {
            $this->info('Zoho is not enabled in this environment');
            return 0;
        }

        $orders = Order::where('status',Order::STATUS_DELIVERED)->whereDate('created_at',Carbon::today())
                       ->whereNull('zoho_books_invoice_id')
                       ->where(function ($query){
                           $query->where('customer_notes', 'not like', '%test%')->orWhere('customer_notes',NULL);
                       })
                       ->where('created_at','<=',Carbon::now()->subMinutes(125)->toDateTimeString())
                       ->get();
        foreach ($orders as $order) {
            Bus::chain(
                [
                    new CreateInvoiceJob($order),
                    new CreatePaymentJob($order),
                    new ApplyPaymentCreditJob($order),
                ]
            )->dispatch();
        }

        $this->info('Jobs dispatched successfully');

        return true;
    }
}
