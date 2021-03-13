<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\ProductResource;
use App\Models\Product;

class ProductController extends BaseApiController
{

    public function show($id)
    {
        return new ProductResource(Product::find($id));
    }

}
