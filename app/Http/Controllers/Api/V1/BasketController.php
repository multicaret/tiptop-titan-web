<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;

class BasketController extends BaseApiController
{
    public function addRemoveProduct(Request $request)
    {
        var_dump($request->all());

//        return new ProductReso urce(Product::find($id));
    }

}
