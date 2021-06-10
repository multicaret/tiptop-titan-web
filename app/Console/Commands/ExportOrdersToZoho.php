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

class ExportOrdersToZoho extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:export-to-zoho {--type=}';

    protected $type = 'recent';

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

    public function initialize(InputInterface $input, OutputInterface $output)
    {
        if ( ! is_null($this->option('type')) && in_array($this->option('type'), ['all','recent'])) {
            $this->type = $this->option('type');
        }
        else{
            $this->type = 'recent';

        }
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

        $from = now()->subHours(5)->toDateTimeString();
        $to   = now()->subMinutes(125)->toDateTimeString();

        $lastTwoDays = today()->subDays(2)->toDateString();

        $orders = Order::where('status',Order::STATUS_DELIVERED)
                       ->whereNull('zoho_books_invoice_id')
                       ->where(function ($query){
                           $query->where('customer_notes', 'not like', '%test%')
                                 ->orWhere('customer_notes',NULL);
                       })->when($this->type == 'recent', function ($query) use ($from,$to){
                        $query->where('created_at','<=',$to)->where('created_at','>=',$from);
                      })->when($this->type == 'all', function ($query) use($lastTwoDays){
                        $query->whereDate('created_at','>=', $lastTwoDays);
                      })
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
