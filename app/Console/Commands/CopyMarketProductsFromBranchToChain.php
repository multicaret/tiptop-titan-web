<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\ProgressBar;

class CopyMarketProductsFromBranchToChain extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'copy-market-products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private ProgressBar $bar;

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
        $products = Product::with('media')
                           ->where('id', '>', 44776)
                           ->whereBranchId(DatumImporter::DEFAULT_BRANCH_ID)
                           ->active();
        $this->bar = $this->output->createProgressBar($products->count());
        $this->bar->start();
        foreach ($products->cursor() as $originalProduct) {
            \DB::beginTransaction();
            try {
                $newProduct = $originalProduct->replicateWithTranslations();
                $newProduct->branch_id = null;
                $newProduct->zoho_books_id = null;
                $newProduct->view_count = 1;
                $newProduct->search_count = 0;
                $newProduct->avg_rating = 0;
                $newProduct->rating_count = 0;
                $newProduct->cloned_from_product_id = $originalProduct->id;
                $newProduct->push();
                $newProduct->categories()->sync($originalProduct->categories->pluck('id'));

                $oldMediaItems = $originalProduct->getMedia('cover');
                foreach ($oldMediaItems as $oldMedia) {
                    $oldMedia->copy($newProduct, 'cover', 's3');
                }

                $oldMediaItems = $originalProduct->getMedia('gallery');
                foreach ($oldMediaItems as $oldMedia) {
                    $oldMedia->copy($newProduct, 'gallery', 's3');
                }
            } catch (\Exception $e) {
                \DB::rollback();
                $this->error("ProductId: $originalProduct->id , error: {$e->getMessage()}");
                info('error while cloning market products', [
                    'originalProduct' => $originalProduct,
                    'message' => $e->getMessage(),
                    'error' => $e,
                ]);
            }
            \DB::commit();
            $this->bar->advance();

        }
        $this->bar->finish();
        $this->newLine();
        $this->info('Done!');

        return 0;
    }
}
