<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends BaseApiController
{

    public function show($id)
    {
        $product = Product::find($id);
        if ( ! is_null($product)) {
            return new ProductResource($product);
        }

        return $this->respondNotFound();
    }

}
