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

    public function interact($productID, Request $request)
    {
        $product = Product::find($productID);
        $user = auth()->user();
        switch ($request->action) {
            case 'favorite':
                if ( ! $user->hasFavorited($product)) {
                    $user->favorite($product);

                    return $this->respondWithMessage(__('api.product_added_to_wishlist_successfully'));
                }
                break;
            case 'unfavorite':
                if ($user->hasFavorited($product)) {
                    $user->unfavorite($product);

                    return $this->respondWithMessage(__('api.product_removed_from_wishlist_successfully'));
                }
                break;
        }

        return $this->respondValidationFails(__('api.interaction_failed_please_check_parameters'));
    }

}
