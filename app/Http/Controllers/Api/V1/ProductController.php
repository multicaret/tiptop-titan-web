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
        return new ProductResource(Product::find($id));
    }


    public function searchProducts(Request $request)
    {
        $searchQuery = $request->input('q');
        if (is_null($searchQuery)) {
            return $this->setStatusCode(400)->respond([
                'success' => true,
                'message' => __('Empty search has been provided'),
            ]);
        }

        $products = Product::whereHas('translations', function ($query) use ($searchQuery) {
            $query->where('title', 'like', "%".$searchQuery."%");
        })->orWhereHas('tags', function ($query) use ($searchQuery) {
            $query->whereHas('translations', function ($query) use ($searchQuery) {
                $query->where('title', 'like', "%".$searchQuery."%");
            });
        })->orWhereHas('masterCategory', function ($query) use ($searchQuery) {
            $query->whereHas('translations', function ($query) use ($searchQuery) {
                $query->where('title', 'like', "%".$searchQuery."%");
            });
        })->get();

        return ProductResource::collection($products);
    }

}
