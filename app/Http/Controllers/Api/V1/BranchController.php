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

        $minCart = Branch::active()->foods()->get()->min('minimum_order');
        $maxCart = Branch::active()->foods()->get()->max('minimum_order');

        return $this->respond([
            'categories' => $categories,
            'minCart' => $minCart,
            'maxCart' => $maxCart
        ]);
    }

    public function index(Request $request)
    {
        $categories = $request->input('categories');

        $branches = Branch::active()->foods();

        if ($request->has('minimum_order') && ! is_null($minimumOrder = $request->input('minimum_order'))) {
            $branches = $branches->where(function ($query) use ($minimumOrder) {
                $query->where('minimum_order', '<=', $minimumOrder)
                      ->orWhere('restaurant_minimum_order', '<=', (int) $minimumOrder);
            });
        }

        if ($request->has('delivery_type') && ! empty($deliveryType = $request->input('delivery_type'))) {
            if ($deliveryType == 'tiptop') {
                $branches = $branches->where('has_tip_top_delivery', true);
            } elseif ($deliveryType == 'restaurant') {
                $branches = $branches->where('has_restaurant_delivery', true);
            }
        }


        if ($request->has('categories') && ($categories)) {
            $branches = $branches->whereHas('foodCategories', function ($query) use ($categories) {
                $query->whereIn('category_id', $categories);
            });
        }
        if ($request->has('min_rating') && ! is_null($minRating = $request->input('min_rating'))) {
            $branches = $branches->where('avg_rating', '>=', (float) $minRating);
        }

        switch ($request->input('sort')) {
            case 'restaurants_rating':
                $branches = $branches->orderByDesc('rating_count')
                                     ->orderByDesc('avg_rating');
                break;
            case 'by_distance':
                $branches = $this->sortBranchesByDistance($branches, $request);
                break;
            default:
                $branches = $branches->latest('published_at');
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
        $latitude = number_format((float) $request->input('latitude'), 5);
        $longitude = number_format((float) $request->input('longitude'), 5);

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
