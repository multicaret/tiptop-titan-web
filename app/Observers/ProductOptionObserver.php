<?php

namespace App\Observers;

use App\Models\ProductOption;

class ProductOptionObserver
{
    /**
     * Handle the ProductOption "created" event.
     *
     * @param  \App\Models\ProductOption  $productOption
     * @return void
     */
    public function created(ProductOption $productOption)
    {
        //
    }

    /**
     * Handle the ProductOption "updated" event.
     *
     * @param  \App\Models\ProductOption  $productOption
     * @return void
     */
    public function updated(ProductOption $productOption)
    {
        cache()->tags('products', 'api-home')->flush();
    }

    /**
     * Handle the ProductOption "deleted" event.
     *
     * @param  \App\Models\ProductOption  $productOption
     * @return void
     */
    public function deleted(ProductOption $productOption)
    {
        //
    }

    /**
     * Handle the ProductOption "restored" event.
     *
     * @param  \App\Models\ProductOption  $productOption
     * @return void
     */
    public function restored(ProductOption $productOption)
    {
        //
    }

    /**
     * Handle the ProductOption "force deleted" event.
     *
     * @param  \App\Models\ProductOption  $productOption
     * @return void
     */
    public function forceDeleted(ProductOption $productOption)
    {
        //
    }
}
