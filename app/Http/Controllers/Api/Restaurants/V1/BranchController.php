<?php

namespace App\Http\Controllers\Api\Restaurants\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\FoodBranchResource;
use App\Models\Branch;
use DB;
use Illuminate\Http\Request;

class BranchController extends BaseApiController
{
    public function show($restaurant)
    {
        $restaurant = Branch::find($restaurant);
        if (is_null($restaurant)) {
            return $this->respondNotFound('Restaurants not found');
        }

        return $this->respond(new FoodBranchResource($restaurant));
    }

    public function edit($restaurant)
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

    public function update($restaurant, Request $request)
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
        if (str_contains($request->delivery_time, '-')) {
            [
                $restaurant->restaurant_min_delivery_minutes,
                $restaurant->restaurant_max_delivery_minutes
            ] = explode('-', $request->delivery_time);
        }
        $restaurant->restaurant_minimum_order = $request->minimum_order;
        $restaurant->restaurant_fixed_delivery_fee = $request->delivery_fee;
//        $restaurant->covered_area_diameter = $request->covered_area_diameter;
//        $restaurant->phone_number = $request->phone_number;
        $restaurant->save();

        DB::commit();

        return $this->respond([
            'success' => true,
            'message' => 'Successfully Updated',
        ]);

        /*return $this->respond([
            'restaurant' => new FoodBranchResource($restaurant)
        ]);*/
    }

    public function toggleStatus($restaurant, Request $request)
    {
        $rules = [
            'status' => 'required',
        ];

        $validator = validator()->make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->respondValidationFails($validator->errors());
        }
        $restaurant = Branch::find($restaurant);
        if (is_null($restaurant)) {
            return $this->respondNotFound();
        }
        $restaurant->is_open_now = $request->input('status') ? Branch::STATUS_ACTIVE : Branch::STATUS_INACTIVE;
        $restaurant->save();

        return $this->respond([
            'success' => true,
            'message' => 'Successfully Updated',
        ]);
    }

}
