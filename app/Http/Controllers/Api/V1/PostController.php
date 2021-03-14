<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\BlogResource;
use App\Http\Resources\FaqResource;
use App\Http\Resources\PostResource;
use App\Http\Resources\PrivacyResource;
use App\Http\Resources\TaxonomyResource;
use App\Models\Post;
use App\Models\PostTranslation;
use App\Models\Taxonomy;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class PostController extends BaseApiController
{

    public function index(Request $request)
    {
        $posts = Post::where('type', Post::getCorrectType($request->type));
        if ($request->has('order') && ! empty($request->get('order'))) {
            foreach ($request->get('order') as $orderColumn => $orderValue) {
                $posts->where($orderColumn, $orderValue);
            }
        }

        return PostResource::collection($posts->get());
    }

    public function getAllAvailableTypes()
    {
        return Post::getTypesArray();
    }

    public function create(Request $request)
    {
//        $typeName = Post::getCorrectTypeName($request->type, false);
//        $type = Post::getCorrectType($request->type);
//        $post = new Post();
        $categories = Taxonomy::postCategories()->get();

        return $this->respond([
            'categories' => TaxonomyResource::collection($categories),
        ]);
    }

    public function store(Request $request)
    {
        \DB::beginTransaction();
        $defaultLocale = localization()->getDefaultLocale();

//        $request->validate([
////            "{$defaultLocale}.title" => 'required',
////            "{$defaultLocale}.content" => 'required'
////        ]);

        $post = new Post();
        $post->creator_id = $post->editor_id = auth()->id();
        $post->type = Post::getCorrectType($request->type);
        $post->category_id = $request->category_id;
        $post->save();

        // Filling translations
        foreach ($request->translations as $locale => $translation) {
            if ( ! is_null($translation['title'])) {
                $postTranslation = new PostTranslation();
                $postTranslation->post_id = $post->id;
                $postTranslation->locale = $locale;
                $postTranslation->title = $translation['title'];
                $postTranslation->content = isset($translation['content']) ? $translation['content'] : null;
                $postTranslation->excerpt = isset($translation['excerpt']) ? $translation['excerpt'] : null;
                $postTranslation->notes = isset($translation['notes']) ? $translation['notes'] : null;
                $postTranslation->save();
            }
        }

        $post->save();

        $post->tags()->sync($request->tags);

        if ($request->hasFile('cover')) {
            $post->addMediaFromRequest('cover')
                 ->toMediaCollection('cover');
        }
        \DB::commit();

        return $this->respond([
            'success' => true,
            'message' => 'Successfully Stored',
        ]);
    }

    public function edit(Request $request, $post)
    {
//        $typeName = Post::getCorrectTypeName($request->type, false);
//        $type = Post::getCorrectType($request->type);
        $categories = Taxonomy::postCategories()->get();

        return $this->respond([
            'post' => new PostResource(Post::find($post)),
            'categories' => TaxonomyResource::collection($categories),
        ]);

    }

    public function update(Request $request, $post)
    {
        $defaultLocale = localization()->getDefaultLocale();

//        $request->validate([
//            "{$defaultLocale}.title" => 'required',
//            "{$defaultLocale}.content" => 'required'
//        ]);

        $post = Post::find($post);
        $post->category_id = $request->category_id;

        // Filling translations
        foreach ($request->translations as $locale => $translation) {
            if ( ! is_null($translation['title'])) {
                if (is_null($postTranslation = PostTranslation::where('locale', $locale)->where('post_id',
                    $post->id)->first())) {
                    $postTranslation = new PostTranslation();
                    $postTranslation->post_id = $post->id;
                    $postTranslation->locale = $locale;
                }
                $postTranslation->title = $translation['title'];
                $postTranslation->content = isset($translation['content']) ? $translation['content'] : null;
                $postTranslation->excerpt = isset($translation['excerpt']) ? $translation['excerpt'] : null;
                $postTranslation->notes = isset($translation['notes']) ? $translation['notes'] : null;
                $postTranslation->save();
            }
        }


        $post->save();

        $post->tags()->sync($request->tags);

        if ($request->hasFile('cover')) {
            $post->addMediaFromRequest('cover')
                 ->toMediaCollection('cover');
        }

        if ($request->has('unattached-media') && $unattachedMediaId = $request->input('unattached-media')) {
            Media::find($unattachedMediaId)->delete();
        }

        return $this->respond([
            'success' => true,
            'message' => 'Successfully Updated',
        ]);
    }

    public function destroy($post)
    {
        $post = Post::find($post);

        if (is_null(Post::find($post))) {
            return $this->respondNotFound("This Item does not exist");
        }

        if ($post->delete()) {
            return $this->respond([
                'success' => true,
                'message' => 'Successfully Archived',
            ]);
        }

        return $this->respond([
            'errors' => 'Unknown',
            'message' => 'Deletion failed',
        ]);
    }

    //____ FAQ ____//
    public function faqIndex()
    {

        $builder = Post::where('type', Post::TYPE_FAQ);

        return FaqResource::collection($builder->get());
    }

    public function faqShow($id)
    {
        $post = Post::where('type', Post::TYPE_FAQ)->find($id);
        if ( ! empty($post)) {
            return new FaqResource($post);
        }

        return $this->respondNotFound();
    }

    //____ BLOG ____//
    public function blogIndex()
    {

        $builder = Post::where('type', Post::TYPE_ARTICLE);

        return BlogResource::collection($builder->get());
    }

    public function blogShow($id)
    {
        $post = Post::where('type', Post::TYPE_ARTICLE)->find($id);
        if ( ! empty($post)) {
            return new BlogResource($post);
        }

        return $this->respondNotFound();
    }

    public function privacy()
    {
        return new PrivacyResource(Post::find(Post::PRIVACY_PAGE_ID));
    }
}
