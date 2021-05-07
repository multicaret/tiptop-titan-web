<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\CategoryMiniResource;
use App\Http\Resources\GroceryCategoryParentWithChildrenResource;
use App\Models\Taxonomy;
use Illuminate\Http\Request;

class CategoryController extends BaseApiController
{

    public function index(Request $request)
    {
        $validationRules = [
            'branch_id' => 'required',
        ];
        $validator = validator()->make($request->all(), $validationRules);
        if ($validator->fails()) {
            return $this->respondValidationFails($validator->errors());
        }

        $groceryParentCategories = Taxonomy::active()
                                           ->with('children.products')
                                           ->groceryCategories()
                                           ->parents()
                                           ->get();

        return GroceryCategoryParentWithChildrenResource::collection($groceryParentCategories);

        return $this->respond([
            'parents' => $categories,
        ]);
    }

    public function products($groceryCategory, Request $request)
    {
        $validationRules = [
            'branch_id' => 'required',
        ];
        $validator = validator()->make($request->all(), $validationRules);
        if ($validator->fails()) {
            return $this->respondValidationFails($validator->errors());
        }

        $parent = Taxonomy::find($groceryCategory);

        $groceryParentCategories = Taxonomy::active()->groceryCategories()->parents()->get();
        $categories = CategoryMiniResource::collection($groceryParentCategories);

        return $this->respond([
            'selectedParent' => new GroceryCategoryParentWithChildrenResource($parent),
            'parents' => $categories,
        ]);
    }

}
