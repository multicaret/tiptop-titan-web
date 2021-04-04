<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\GroceryCategoryParentIndexResource;
use App\Http\Resources\GroceryCategoryParentResource;
use App\Models\Taxonomy;

class CategoryController extends BaseApiController
{

    public function products($groceryCategory)
    {
        $parent = Taxonomy::find($groceryCategory);

        $groceryParentCategories = Taxonomy::active()->groceryCategories()->parents()->get();
        $categories = GroceryCategoryParentIndexResource::collection($groceryParentCategories);

        return $this->respond([
            'selectedParent' => new GroceryCategoryParentResource($parent),
            'parents' => $categories,
        ]);
    }

}
