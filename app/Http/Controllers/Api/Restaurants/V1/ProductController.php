<?php

namespace App\Http\Controllers\Api\Restaurants\V1;


use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\ProductResource;
use App\Models\Branch;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends BaseApiController
{

    public function update(Request $request, $restaurant, $product)
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
        if (is_null($restaurant) || is_null($product) || $product->branch_id != $restaurant->id ) {
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

        /*return $this->respond([
            'restaurant' => new FoodBranchResource($restaurant)
        ]);*/
    }


}
