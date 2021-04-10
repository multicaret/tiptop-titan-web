<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\BranchResource;
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

        $minCart = Branch::foods()->get()->min('minimum_order');
        $maxCart = Branch::foods()->get()->max('minimum_order');

        return $this->respond([
            'categories' => $categories,
            'minCart' => $minCart,
            'maxCart' => $maxCart
        ]);
    }

    public function filter(Request $request)
    {
        $deliveryType = $request->input('delivery_type');
        $minimumOrder = $request->input('minimum_order');
        $categoryId = $request->input('category_id');
        $rating = $request->input('rating');

        $branches = Branch::getModel();

        if ($deliveryType == 'tiptop') {
            $branches->where(['has_tip_top_delivery' == true, 'has_restaurant_delivery', false]);
        } else {
            $branches->where(['has_tip_top_delivery' == false, 'has_restaurant_delivery', true]);
        }

        if ($request->has('minimum_order')) {
            $branches->where('minimum_order', $minimumOrder);
        }

        if ($request->has('category_id') && ($categoryId)) {
            $branches = $branches->whereHas('foodCategories', function ($query) use ($categoryId) {
                $query->where('category_id', $categoryId);
            });
        }

        $branches->where('avg_rating', 'like', '%'.$rating.'%');

        $branches = $branches->foods()->get();

        return $this->respond(BranchResource::collection($branches));
    }

}
