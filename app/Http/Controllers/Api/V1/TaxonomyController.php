<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\FaqResource;
use App\Http\Resources\TaxonomyResource;
use App\Models\Taxonomy;
use App\Models\TaxonomyTranslation;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TaxonomyController extends BaseApiController
{

    /**
     * @param  Request  $request
     *
     * @return AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $builder = Taxonomy::where('type', Taxonomy::getCorrectType($request->input('type')));

        if (request()->has('order') && ! empty(request()->get('order'))) {
            foreach (request()->get('order') as $orderColumn => $orderValue) {
                $builder->where($orderColumn, $orderValue);
            }
        }

        return TaxonomyResource::collection($builder->get());
    }

    public function create(Request $request)
    {
        $taxonomyType = Taxonomy::getCorrectTypeName($request->input('type'), false);
        $type = Taxonomy::getCorrectType($taxonomyType);
        $roots = Taxonomy::roots();

        if ($type === Taxonomy::TYPE_POST_CATEGORY) {
            $roots = $roots->postCategories();
        } elseif ($type === Taxonomy::TYPE_POST_TAG) {
            $roots = $roots->postTags();
        }
        $roots = $roots->get();

        return $this->respond([
            'roots' => TaxonomyResource::collection($roots),
            'icons' => $this->getFontAwesomeIcons(),
        ]);
    }

    public function getAllAvailableTypes()
    {
        return Taxonomy::getTypesArray();
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        if ($request->parentId && is_null(Taxonomy::find($request->parentId))) {
            return $this->respondValidationFails('Taxonomy with given parentId was not found');
        }
        $taxonomy = new Taxonomy();
        $taxonomy->creator_id = $taxonomy->editor_id = auth()->id();
        $taxonomy->type = Taxonomy::getCorrectType(strtolower($request->type));
        $taxonomy->parent_id = $request->parentId;
        if ($request->has('icon')) {
            $taxonomy->icon = $request->icon;
        }
        $taxonomy->save();

        // Filling translations
        foreach ($request->translations as $locale => $translation) {
            if ( ! is_null($translation['title'])) {
                $taxonomyTranslation = new TaxonomyTranslation();
                $taxonomyTranslation->taxonomy_id = $taxonomy->id;
                $taxonomyTranslation->locale = $locale;
                $taxonomyTranslation->title = $translation['title'];
                $taxonomyTranslation->description = $translation['description'];
                $taxonomyTranslation->save();
            }
        }

        $taxonomy->save();

//        $taxonomy->addMediaFromUrl($request->thumbnail);
        DB::commit();

        return $this->respond([
            'success' => true,
            'message' => 'Successfully Stored',
        ]);
    }

    public function show($taxonomy)
    {
        return new TaxonomyResource(Taxonomy::find($taxonomy));
    }

    public function indexFaq(Request $request)
    {
        $faqs = Taxonomy::where('type', Taxonomy::TYPE_FAQ_CLIENTS);

        return FaqResource::collection($faqs->get());
    }

    public function showFaq($id)
    {
        return new FaqResource(Taxonomy::find($id));
    }

    public function edit(Request $request, $taxonomy)
    {
        $taxonomy = Taxonomy::find($taxonomy);
        $taxonomyType = Taxonomy::getCorrectTypeName($request->input('type'), false);
        $type = Taxonomy::getCorrectType($taxonomyType);
        $roots = Taxonomy::roots()->whereNotIn('id', [$taxonomy]);


        if ($type === Taxonomy::TYPE_POST_CATEGORY) {
            $roots = $roots->postCategories();
        } elseif ($type === Taxonomy::TYPE_POST_TAG) {
            $roots = $roots->postTags();
        }
        $roots = $roots->get();
        $fontAwesomeIcons = $this->getFontAwesomeIcons();

        return $this->respond([
            'taxonomy' => new TaxonomyResource($taxonomy),
            'roots' => TaxonomyResource::collection($roots),
            'icons' => $fontAwesomeIcons,
        ]);
    }

    public function update(Request $request, $taxonomy)
    {
        $taxonomy = Taxonomy::find($taxonomy);

        $defaultLocale = localization()->getDefaultLocale();
        $validationRules = [
            "translations.{$defaultLocale}.title" => 'required',
        ];

        $validator = validator()->make($request->all(), $validationRules);
        if ($validator->fails()) {
            return $this->respondValidationFails($validator->errors());
        }

        $taxonomy->editor_id = auth()->id();
        if ($request->has('icon')) {
            $taxonomy->icon = $request->icon;
        }
        $taxonomy->parent_id = $request->parentId;
        $taxonomy->save();

        // Filling translations
        foreach ($request->translations as $locale => $translation) {
            if ( ! is_null($translation['title'])) {
                if (is_null($taxonomyTranslation = TaxonomyTranslation::where('locale', $locale)->where('taxonomy_id',
                    $taxonomy->id)->first())) {
                    $taxonomyTranslation = new TaxonomyTranslation();
                    $taxonomyTranslation->taxonomy_id = $taxonomy->id;
                    $taxonomyTranslation->locale = $locale;
                }
                $taxonomyTranslation->title = $translation['title'];
                $taxonomyTranslation->description = isset($translation['description']) ? $translation['description'] : null;
                $taxonomyTranslation->save();
            }
        }


        return $this->respond([
            'success' => true,
            'message' => 'Successfully Updated',
        ]);
    }

    public function destroy($taxonomy)
    {
        $taxonomy = Taxonomy::find($taxonomy);

        if (is_null(Taxonomy::find($taxonomy))) {
            return $this->respondNotFound('This Item does not exist');
        }

        if ($taxonomy->hasChildren()) {
            return $this->respond([
                'info' => 'Delete Children First',
            ]);
        }

        if ($taxonomy->delete()) {
            return $this->respond([
                'success' => true,
                'message' => 'Successfully Archived',
            ]);
        }
    }

    private function getFontAwesomeIcons()
    {
        return collect(config('font-awesome-icons.all'))->map(function ($icon) {
            return [
                'id' => $icon['code'] = $icon['code'].' '.$icon['class'],
                'title' => $icon['title'] = sprintf('<i class="%s fa-2x fa-fw"></i>&nbsp;%s', $icon['class'],
                    $icon['title'])
            ];
        });
    }
}
