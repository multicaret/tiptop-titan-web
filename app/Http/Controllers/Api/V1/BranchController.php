<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\BranchResource;
use App\Http\Resources\FoodBranchResource;
use App\Http\Resources\FoodCategoryResource;
use App\Models\Branch;
use App\Models\Location;
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

    public function index(Request $request)
    {
        $deliveryType = $request->input('delivery_type');
        $minimumOrder = $request->input('minimum_order');
        $categories = $request->input('categories');
        $minRating = $request->input('min_rating');

        $branches = Branch::getModel();

        if ($request->has('delivery_type')) {
            if ($deliveryType == 'tiptop') {
                $branches->where('has_tip_top_delivery', true);
            } elseif ($deliveryType == 'restaurant') {
                $branches->where('has_restaurant_delivery', true);
            }
        }

        if ($request->has('minimum_order')) {
            $branches->where('minimum_order', '=<', (int) $minimumOrder);
            $branches->Where('restaurant_minimum_order', '=<', (int) $minimumOrder, 'or');
        }

        if ($request->has('categories') && ($categories)) {
            $branches = $branches->whereHas('foodCategories', function ($query) use ($categories) {
                $query->whereIn('category_id', $categories);
            });
        }
        if ($request->has('minRating')) {
            $branches->where('avg_rating', '>=', (float) $minRating);
        }

        switch ($request->input('sort')) {
            case 'restaurants_rating':
                $branches = $branches->foods()->orderByDesc('avg_rating');
                break;
            case 'by_distance':
                $branches = $this->sortBranchesByDistance($branches, $request);
                break;
            default:
                $branches = $branches->foods()->latest('published_at');
        }

        $branches = $branches->get();

        return $this->respond(BranchResource::collection($branches));
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder  $branches
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    private function sortBranchesByDistance(\Illuminate\Database\Eloquent\Builder $branches, $request)
    {
        $user = auth('sanctum')->user();
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');

        if ( ! is_null($user) && ! is_null($selectedAddress = $request->input('selected_address_id'))) {
            $selectedAddress = Location::find($selectedAddress);
            if (is_null($selectedAddress)) {
                return $this->respondNotFound('Address not found!');
            }
            $latitude = $selectedAddress->latitude;
            $longitude = $selectedAddress->longitude;
        }

        $branches->selectRaw('branches.*, DISTANCE_BETWEEN(latitude,longitude,?,?) as distance',
            [$latitude, $longitude])
                 ->orderBy('distance');

        // Sorting
        return $branches;
    }

}
