<?php

namespace App\Observers;

use App\Jobs\Zoho\SyncProductJob;
use App\Models\Product;

class ProductObserver
{

    /**
     * Handle the Order "created" event.
     *
     * @param  Product  $product
     * @return void
     */
    public function creating(Product $product)
    {
        if ($product->type == Product::CHANNEL_GROCERY_OBJECT) {
            $product->sku = 'TT'.(int) (mt_rand(0, 99).substr(time(), 5));
        }
    }
    /**
     * Handle the Product "created" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function created(Product $product)
    {
        if ($product->type == Product::CHANNEL_FOOD_OBJECT && !empty(optional($product->branch)->zoho_books_id)) {
            SyncProductJob::dispatch($product)->delay(now()->addMinutes(5));
        }
        if ($product->type == Product::CHANNEL_GROCERY_OBJECT && empty($product->branch_id) && !empty($product->chain_id)) {
            SyncProductJob::dispatch($product)->delay(now()->addMinutes(5));
        }


    }

    /**
     * Handle the Product "updated" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function updated(Product $product)
    {
        //
    }

    /**
     * Handle the Product "deleted" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function deleted(Product $product)
    {
        //
    }

    /**
     * Handle the Product "restored" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function restored(Product $product)
    {
        //
    }

    /**
     * Handle the Product "force deleted" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function forceDeleted(Product $product)
    {
        //
    }

    /**
     * Handle the Product "force deleted" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function saved(Product $product)
    {
        cache()->tags('products')->flush();
    }
}
