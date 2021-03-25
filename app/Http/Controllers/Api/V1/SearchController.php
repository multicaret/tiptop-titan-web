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
                       ->orderByDesc('count')
                       ->latest()
                       ->take(5)
                       ->get();

        return $this->respond([
            'terms' => SearchResource::collection($terms),
        ]);
    }

    public function searchProducts(Request $request)
    {
        $validationRules = [
            'chain_id' => 'required',
            'branch_id' => 'required',
            'q' => 'required|min:2|max:255',
        ];

        $validator = validator()->make($request->all(), $validationRules);
        if ($validator->fails()) {
            return $this->respondValidationFails($validator->errors());
        }

        $searchQuery = $request->input('q');

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

        if ($products->count() == 0) {
            return $this->respondNotFound('No results for your search');
        }

        $chainId = $request->input('chain_id');
        $branchId = $request->input('branch_id');
        // Storing the search term.
        if (is_null($search = Search::whereLocale(localization()->getCurrentLocale())
                                    ->whereChainId($chainId)
                                    ->whereBranchId($branchId)
                                    ->whereTerm($searchQuery)
                                    ->first())) {
            $search = new Search();
            $search->chain_id = $chainId;
            $search->branch_id = $branchId;
            $search->term = $searchQuery;
            $search->save();
        } else {
            $search->increment('count');
        }

        return $this->respond(ProductResource::collection($products));
    }

}
