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
        if ( ! config('services.zoho.zoho_enabled')) {
            $this->info('Zoho is not enabled in this environment');
            return 0;
        }

        $products = Product::whereNotNull('branch_id')->where('type',Product::CHANNEL_GROCERY_OBJECT)->get();
        foreach ($products as $product) {
            SyncProductJob::dispatch($product);
        }

        $this->info('Jobs dispatched successfully');

        return 0;
    }
}
