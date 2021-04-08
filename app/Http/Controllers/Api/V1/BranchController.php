<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends BaseApiController
{
    public function show($restaurant, Request $request)
    {
        $restaurant = Branch::find($restaurant);

        return $this->respond(new FoodBranchResource($restaurant));
    }

}
