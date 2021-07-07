<?php

namespace App\Console\Commands;

use App\Mail\NewRecord;
use App\Models\OrderDailyReport;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class CheckForPeakOrdersNumber extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:check-is-peak';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $todayReportRecord = OrderDailyReport::whereDate('day', today())->firstOrFail();

        $max = OrderDailyReport::whereDate('day', '<', today())->max('orders_count');

        if ($todayReportRecord->orders_count > $max) {
            Mail::to(['nour@trytiptop.app','management@trytiptop.app','mehmet@trytiptop.app'])->send(new NewRecord($todayReportRecord,
                OrderDailyReport::whereDate('day', '<', today())->latest('orders_count')->first()));
        }

        return 0;
    }
}
