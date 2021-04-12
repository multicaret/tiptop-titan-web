<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\FoodBranchResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\SearchResource;
use App\Models\Branch;
use App\Models\Product;
use App\Models\Search;
use Illuminate\Http\Request;

class SearchController extends BaseApiController
{
    public function index(Request $request)
    {
        $validationRules = [
            'channel' => 'required',
        ];
        $isGroceryChannel = $request->input('channel') == config('app.app-channels.grocery');
        if ($isGroceryChannel) {
            $validationRules['chain_id'] = 'required';
            $validationRules['branch_id'] = 'required';
        }

        $validator = validator()->make($request->all(), $validationRules);
        if ($validator->fails()) {
            return $this->respondValidationFails($validator->errors());
        }

        $search = Search::whereLocale(localization()->getCurrentLocale());
        if ($isGroceryChannel) {
            $search = $search->whereChainId($request->input('chain_id'))
                             ->whereBranchId($request->input('branch_id'))
                             ->where('type', Search::CHANNEL_GROCERY_OBJECT);
        } else {
            $search = $search->where('type', Search::CHANNEL_FOOD_OBJECT);
        }

        $search = $search->orderByDesc('count')
                         ->latest()
                         ->take(5)
                         ->get();

        return $this->respond([
            'terms' => SearchResource::collection($search),
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

        $results = Product::whereHas('translations', function ($query) use ($searchQuery) {
            $query->where('title', 'like', '%'.$searchQuery.'%');
        })
                          ->orWhereHas('searchTags', function ($query) use ($searchQuery) {
                              $query->whereHas('translations', function ($query) use ($searchQuery) {
                                  $query->where('title', 'like', '%'.$searchQuery.'%');
                              });
                          })
                          ->orWhereHas('masterCategory', function ($query) use ($searchQuery) {
                              $query->whereHas('translations', function ($query) use ($searchQuery) {
                                  $query->where('title', 'like', '%'.$searchQuery.'%');
                              });
                          })
                          ->get();

        if ($results->count() == 0) {
            return $this->respondWithMessage('No results for your search');
        }

        $chainId = $request->input('chain_id');
        $branchId = $request->input('branch_id');
        // Storing the search term.
        if (is_null($search = Search::whereLocale(localization()->getCurrentLocale())
                                    ->whereType(Search::CHANNEL_GROCERY_OBJECT)
                                    ->whereChainId($chainId)
                                    ->whereBranchId($branchId)
                                    ->whereTerm($searchQuery)
                                    ->first())) {
            $search = new Search();
            $search->locale = localization()->getCurrentLocale();
            $search->type = Search::CHANNEL_GROCERY_OBJECT;
            $search->chain_id = $chainId;
            $search->branch_id = $branchId;
            $search->term = $searchQuery;
            $search->save();
        } else {
            $search->increment('count');
            $search->save();
        }

        return $this->respond(ProductResource::collection($results));
    }


    public function searchBranches(Request $request)
    {
        $validationRules = [
            'q' => 'required|min:2|max:255',
        ];

        $validator = validator()->make($request->all(), $validationRules);
        if ($validator->fails()) {
            return $this->respondValidationFails($validator->errors());
        }

        $searchQuery = $request->input('q');

        $results = Branch::foods()->whereHas('translations', function ($query) use ($searchQuery) {
            $query->where('title', 'like', '%'.$searchQuery.'%');
        })
                         ->orWhereHas('foodCategories', function ($query) use ($searchQuery) {
                             $query->whereHas('translations', function ($query) use ($searchQuery) {
                                 $query->where('title', 'like', '%'.$searchQuery.'%');
                             });
                         })
                         ->orWhereHas('products', function ($query) use ($searchQuery) {
                             $query->whereHas('translations', function ($translationQuery) use ($searchQuery) {
                                 $translationQuery->where('title', 'like', '%'.$searchQuery.'%');
                             });
                         })
                         ->get();

        if ($results->count() == 0) {
            return $this->respondWithMessage('No results for your search');
        }

        // Storing the search term.
        if (is_null($search = Search::whereLocale(localization()->getCurrentLocale())
                                    ->whereType(Search::CHANNEL_FOOD_OBJECT)
                                    ->whereTerm($searchQuery)
                                    ->first())) {
            $search = new Search();
            $search->locale = localization()->getCurrentLocale();
            $search->type = Search::CHANNEL_FOOD_OBJECT;
            $search->term = $searchQuery;
            $search->save();
        } else {
            $search->increment('count');
            $search->save();
        }

        return $this->respond(FoodBranchResource::collection($results));
    }

}
