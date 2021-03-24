<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\ProductResource;
use App\Http\Resources\SearchResource;
use App\Models\Product;
use App\Models\Search;
use Illuminate\Http\Request;

class SearchController extends BaseApiController
{
    public function index(Request $request)
    {
        $validationRules = [
            'chain_id' => 'required',
            'branch_id' => 'required',
        ];

        $validator = validator()->make($request->all(), $validationRules);
        if ($validator->fails()) {
            return $this->respondValidationFails($validator->errors());
        }

        $chainId = $request->input('chain_id');
        $branchId = $request->input('branch_id');

        $terms = Search::whereChainId($chainId)
                       ->whereBranchId($branchId)
                       ->whereLocale(localization()->getCurrentLocale())
                       ->orderBy('count')
                       ->take(5)
                       ->get();

        return $this->respond([
            'terms' => SearchResource::collection($terms),
        ]);
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
        })
                           ->orWhereHas('tags', function ($query) use ($searchQuery) {
                               $query->whereHas('translations', function ($query) use ($searchQuery) {
                                   $query->where('title', 'like', "%".$searchQuery."%");
                               });
                           })
                           ->orWhereHas('masterCategory', function ($query) use ($searchQuery) {
                               $query->whereHas('translations', function ($query) use ($searchQuery) {
                                   $query->where('title', 'like', "%".$searchQuery."%");
                               });
                           })
                           ->get();

        if ($products->count()) {
            return $this->respond(ProductResource::collection($products));
        }

        return $this->respondNotFound('No results for your search');
    }

}
