<?php

namespace App\Http\Controllers\Api\Restaurants\V1;


use App\Http\Controllers\Api\BaseApiController;
use App\Models\Branch;
use App\Models\Product;
use DB;
use Illuminate\Http\Request;

class ProductController extends BaseApiController
{

    public function update($restaurant, $product, Request $request)
    {
        $rules = [
            'price' => 'required',
        ];

        $validator = validator()->make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->respondValidationFails($validator->errors());
        }

        $restaurant = Branch::find($restaurant);
        $product = Product::find($product);
        if (is_null($restaurant) || is_null($product) || $product->branch_id != $restaurant->id) {
            return $this->respondNotFound();
        }

        DB::beginTransaction();
        $product->price = $request->price;
        $product->save();
        DB::commit();

        return $this->respond([
            'success' => true,
            'message' => 'Successfully Updated',
        ]);
    }


    public function toggleStatus($product, Request $request)
    {
        $rules = [
            'status' => 'required',
        ];

        $validator = validator()->make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->respondValidationFails($validator->errors());
        }
        $product = Product::find($product);
        if (is_null($product)) {
            return $this->respondNotFound();
        }
        $product->status = $request->input('status') ? Product::STATUS_ACTIVE : Product::STATUS_INACTIVE;
        $product->save();

        return $this->respond([
            'success' => true,
            'message' => 'Successfully Updated',
        ]);
    }

}
