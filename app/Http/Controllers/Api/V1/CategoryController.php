<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\GroceryCategoryParentResource;
use App\Models\Taxonomy;

class CategoryController extends BaseApiController
{

    public function products($groceryCategory)
    {
        $parent = Taxonomy::find($groceryCategory);

        return $this->respond([
            'parent' => new GroceryCategoryParentResource($parent),
        ]);
    }

}
