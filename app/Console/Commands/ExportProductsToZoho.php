<?php

namespace App\Console\Commands;

use App\Jobs\Zoho\SyncProductJob;
use App\Models\Branch;
use App\Models\Product;
use Illuminate\Console\Command;

class ExportProductsToZoho extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:export-to-zoho';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatches jobs to export all tiptop products to zoho books';

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

        foreach (Product::whereNotNull('branch_id')->whereNull('zoho_books_id')->whereHas('branch',function ($query){
            $query->whereHas('translations', function ($query)  {
                $query->where('title', 'not like', '%test%');
            })->where('branches.status',Branch::STATUS_ACTIVE)->whereNotNull('branches.zoho_books_id');
        })->cursor() as $product) {
            SyncProductJob::dispatch($product);
        }

        $this->info('Jobs dispatched successfully');

        return 0;
    }
}
