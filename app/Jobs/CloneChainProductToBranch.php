<?php

namespace App\Jobs;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CloneChainProductToBranch implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $originalProduct;
    private $branchId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($originalProduct, $branchId)
    {
        $this->originalProduct = $originalProduct;
        $this->branchId = $branchId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $syncedBefore = Product::where('branch_id', $this->branchId)
                               ->where('cloned_from_product_id', $this->originalProduct->id)
                               ->exists();

        if ( ! $syncedBefore) {
//            try {
            \DB::beginTransaction();
            $newProduct = $this->originalProduct->replicateWithTranslations();
            $newProduct->branch_id = $this->branchId;
            $newProduct->cloned_from_product_id = $this->originalProduct->id;
            $newProduct->push();
            $newProduct->categories()->sync($this->originalProduct->categories->pluck('id'));

            $oldMediaItems = $this->originalProduct->getMedia('cover');
            foreach ($oldMediaItems as $oldMedia) {
                $oldMedia->copy($newProduct, 'cover', 's3');
            }
            $oldMediaItems = $this->originalProduct->getMedia('gallery');
            foreach ($oldMediaItems as $oldMedia) {
                $oldMedia->copy($newProduct, 'gallery', 's3');
            }
            \DB::commit();
            /*} catch (\Exception $e) {
                \DB::rollBack();
                \Log::error('Exception while handle@', [
                    'message' => $e->getMessage(),
                    'exception' => $e,
                ]);
            }*/
        }
    }
}
