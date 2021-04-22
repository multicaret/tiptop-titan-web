<?php

namespace App\Http\Controllers\Api\Restaurants\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\BlogResource;
use App\Http\Resources\FaqResource;
use App\Http\Resources\PostResource;
use App\Http\Resources\StaticPageResource;
use App\Http\Resources\TaxonomyResource;
use App\Models\Post;
use App\Models\PostTranslation;
use App\Models\Taxonomy;
use DB;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class PostController extends BaseApiController
{
    public function privacy(): \Illuminate\Http\JsonResponse
    {
        return $this->respond(new StaticPageResource(Post::find(Post::PRIVACY_PAGE_ID)));
    }

    public function aboutUs(): \Illuminate\Http\JsonResponse
    {
        return $this->respond(new StaticPageResource(Post::find(Post::ABOUT_PAGE_ID)));
    }
}
