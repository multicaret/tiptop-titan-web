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
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateZohoBooksOrderPayment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:make-payment';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

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

        $since = today()->subDays(30)->toDateString();

        $orders = Order::where('status',Order::STATUS_DELIVERED)
                       ->whereNotNull('zoho_books_invoice_id')
                       ->whereNull('zoho_books_payment_id')
                       ->where(function ($query){
                           $query->where('customer_notes', 'not like', '%test%')
                                 ->orWhere('customer_notes',NULL);
                       })->whereDate('created_at','>=', $since)->get();
        foreach ($orders as $order) {
            Bus::chain(
                [
                    new CreatePaymentJob($order),
                    new ApplyPaymentCreditJob($order),
                ]
            )->dispatch();
        }

        $this->info('Jobs dispatched successfully');

        return true;
    }
}
