<?php

namespace App\Console\Commands;

use App\Jobs\Zoho\SyncProductJob;
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
        $products = Product::whereNotNull('branch_id')->take(4000)->get();
        foreach ($products as $product) {
            SyncProductJob::dispatch($product);
        }


        return 'Jobs dispatched successfully';
    }
}
