<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\FoodBranchResource;
use App\Http\Resources\FoodCategoryResource;
use App\Models\Branch;
use App\Models\Taxonomy;
use Illuminate\Http\Request;

class BranchController extends BaseApiController
{
    public function show($restaurant, Request $request)
    {
        $restaurant = Branch::find($restaurant);
        if (is_null($restaurant)) {
            return $this->respondNotFound('Restaurants not found');
        }

        return $this->respond(new FoodBranchResource($restaurant));
    }


    public function filterCreate(Request $request)
    {
        $categories = cache()->rememberForever('all_food_categories', function () {
            $categories = Taxonomy::active()->foodCategories()->get();

            return FoodCategoryResource::collection($categories);
        });

        $minBasket = Branch::foods()->get()->min('minimum_order');
        $maxBasket = Branch::foods()->get()->max('minimum_order');

        return $this->respond([
            'categories' => $categories,
            'minBasket' => $minBasket,
            'maxBasket' => $maxBasket
        ]);
    }

}
