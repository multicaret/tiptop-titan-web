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
        $product = Product::where('branch_id', $this->branchId)
                          ->where('cloned_from_product_id', $this->originalProduct->id)
                          ->first();

        if (is_null($product)) {
            \DB::beginTransaction();
            $newProduct = $this->originalProduct->replicateWithTranslations();
            $newProduct->branch_id = $this->branchId;
            $newProduct->cloned_from_product_id = $this->originalProduct->id;
            $newProduct->push();
            $newProduct->categories()->sync($this->originalProduct->categories->pluck('id'));
            \DB::commit();

            $oldMediaItems = $this->originalProduct->getMedia('cover');
            foreach ($oldMediaItems as $oldMedia) {
                $oldMedia->copy($newProduct, 'cover', 's3');
            }
            $oldMediaItems = $this->originalProduct->getMedia('gallery');
            foreach ($oldMediaItems as $oldMedia) {
                $oldMedia->copy($newProduct, 'gallery', 's3');
            }

        } else {
            \DB::beginTransaction();
            $product->brand_id = $this->originalProduct->brand_id;
            $product->unit_id = $this->originalProduct->unit_id;
            $product->status = $this->originalProduct->status;
            $product->price = $this->originalProduct->price;
            $product->price_discount_finished_at = $this->originalProduct->price_discount_finished_at;
            $product->price_discount_began_at = $this->originalProduct->price_discount_began_at;
            $product->price_discount_by_percentage = $this->originalProduct->price_discount_by_percentage;
            $product->price_discount_amount = $this->originalProduct->price_discount_amount;
            $product->category_id = $this->originalProduct->category_id;
            $product->save();

            foreach (localization()->getSupportedLocales() as $key => $value) {
                $product->translateOrNew($key)->title = $this->originalProduct->translate($key)->title;
                $product->translateOrNew($key)->description = $this->originalProduct->translate($key)->description;
            }
            $product->save();
            \DB::commit();

            $product->categories()->sync($this->originalProduct->categories);
            $product->searchTags()->sync($this->originalProduct->searchTags);

            $oldMediaItems = $this->originalProduct->getMedia('cover');
            foreach ($oldMediaItems as $oldMedia) {
                $oldMedia->copy($product, 'cover', 's3');
            }
            $oldMediaItems = $this->originalProduct->getMedia('gallery');
            foreach ($oldMediaItems as $oldMedia) {
                $oldMedia->copy($product, 'gallery', 's3');
            }
        }

    }
}
