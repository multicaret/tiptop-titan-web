<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;

class GenerateSkuForMarketChainItems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:generate-sku';

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
        foreach (Product::where('type',Product::CHANNEL_GROCERY_OBJECT)->whereNull('branch_id')->whereNotNull('chain_id')->cursor() as $product) {
            $sku = $product->generateSkuString();
            $product->sku = $sku;
            $product->save();
        }
        return 0;
    }
}
