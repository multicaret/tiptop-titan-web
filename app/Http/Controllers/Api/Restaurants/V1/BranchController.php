<?php

namespace App\Http\Controllers\Api\Restaurants\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\BranchResource;
use App\Http\Resources\CategoryMiniResource;
use App\Http\Resources\FoodBranchResource;
use App\Http\Resources\FoodCategoryResource;
use App\Models\Branch;
use App\Models\Location;
use App\Models\Taxonomy;
use DB;
use Illuminate\Http\Request;

class BranchController extends BaseApiController
{

    public function edit(Request $request, $restaurant)
    {
        $restaurant = Branch::find($restaurant);

        if (is_null($restaurant)) {
            return $this->respondNotFound();
        }

        return $this->respond(
            [
                'restaurant' => new FoodBranchResource($restaurant),
            ]
        );
    }

    public function update(Request $request, $restaurant)
    {
        $rules = [
            'delivery_time' => 'required',
            'minimum_order' => 'required',
            'delivery_fee' => 'required',
//            'covered_area_diameter' => 'required',
//            'phone_number' => 'required',
        ];


        $validator = validator()->make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->respondValidationFails($validator->errors());
        }

        $restaurant = Branch::find($restaurant);

        if (is_null($restaurant)) {
            return $this->respondNotFound();
        }

        DB::beginTransaction();
        [
            $restaurant->restaurant_min_delivery_minutes,
            $restaurant->restaurant_max_delivery_minutes
        ] = explode('-', $request->delivery_time);
        $restaurant->restaurant_minimum_order = $request->minimum_order;
        $restaurant->restaurant_fixed_delivery_fee = $request->delivery_fee;
//        $restaurant->covered_area_diameter = $request->covered_area_diameter;
//        $restaurant->phone_number = $request->phone_number;
        $restaurant->save();

        DB::commit();

        return $this->respond([
            'restaurant' => new FoodBranchResource($restaurant)
        ]);
    }

    public function categories($restaurant)
    {
        $restaurant = Branch::find($restaurant);

        if (is_null($restaurant)) {
            return $this->respondNotFound();
        }

        return $this->respond([
            'categories' => CategoryMiniResource::collection($restaurant->menuCategories()->orderByDesc('order_column')->get())
        ]);
    }

    public function toggleActivity($restaurant)
    {
        $restaurant = Branch::find($restaurant);
        if (is_null($restaurant)) {
            return $this->respondNotFound();
        }
        $restaurant->is_open_now = ! $restaurant->is_open_now;
        $restaurant->save();

        return $this->respond(
            [
                'is_open_now' => $restaurant->is_open_now
            ],
        );
    }

}
