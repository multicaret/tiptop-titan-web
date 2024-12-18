<?php

namespace App\Console\Commands;

use App\Jobs\Zoho\CreateBranchAccountJob;
use App\Jobs\Zoho\CreateDeliveryItemJob;
use App\Jobs\Zoho\CreateTipTopDeliveryItemJob;
use App\Jobs\Zoho\SyncBranchJob;
use App\Models\Branch;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Bus;

class ExportBranchesToZoho extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'branches:export-to-zoho';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatches jobs to export all tiptop branches to zoho books';

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

        $branches = Branch::where('status',Branch::STATUS_ACTIVE)->whereNull('zoho_books_id')->whereHas('translations', function ($query)  {
            $query->where('title', 'not like', '%test%');
        })->get();
        foreach ($branches as $branch) {
            Bus::chain(
                [
                    new SyncBranchJob($branch),
                    new CreateDeliveryItemJob($branch),
                    new CreateTipTopDeliveryItemJob($branch),
                    new CreateBranchAccountJob($branch),

                ]
            )->dispatch();
        }

        $this->info('Jobs dispatched successfully');

        return true;
    }
}
