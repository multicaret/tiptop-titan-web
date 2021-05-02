<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Taxonomy;
use App\Models\TaxonomyTranslation;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class TaxonomyController extends Controller
{

    public function __construct()
    {
        $type = request('type');
        $this->middleware('permission:'.$type.'.permissions.index', ['only' => ['index', 'store']]);
        $this->middleware('permission:'.$type.'.permissions.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:'.$type.'.permissions.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:'.$type.'.permissions.destroy', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $typeName = Taxonomy::getCorrectTypeName($request->type, false);
        $correctType = Taxonomy::getCorrectType($request->type);
        $hasParent = in_array($correctType, Taxonomy::typesHaving('parent'));

        $columns = [
            [
                'data' => 'id',
                'name' => 'id',
                'title' => 'ID',
                'width' => '100',
            ],
        ];

        $columns = array_merge($columns, [
            [
                'data' => 'title',
                'name' => 'translations.title',
                'title' => 'Title',
                'orderable' => false,
                'targets' => '_all',
            ],
        ]);

        if ($hasParent) {
            $columns = array_merge($columns, [
                [
                    'data' => 'parent',
                    'name' => 'parent',
                    'title' => 'Parent',
                    'targets' => '_all'
                ]
            ]);
        }
        if ($correctType == Taxonomy::TYPE_UNIT) {
            $columns = array_merge($columns, [
                [
                    'data' => 'step',
                    'name' => 'step',
                    'title' => trans('strings.step'),
                    'orderable' => false,
                    'searchable' => false
                ]
            ]);
        }
        if ($correctType == Taxonomy::TYPE_INGREDIENT) {
            $columns = array_merge($columns, [
                [
                    'data' => 'ingredientCategory',
                    'name' => 'ingredientCategory',
                    'title' => 'Ingredient Category',
                    'orderable' => false,
                    'searchable' => false
                ]
            ]);
        }
        /*if ($correctType === Taxonomy::TYPE_GROCERY_CATEGORY) {
            $columns = array_merge($columns, [
                [
                    'data' => 'branches',
                    'name' => 'branches',
                    'title' => trans('strings.branches'),
                    'orderable' => false,
                    'searchable' => false
                ]
            ]);
        }*/

        if (in_array($correctType,
            [Taxonomy::TYPE_GROCERY_CATEGORY])) {
            $columns = array_merge($columns, [
                [
                    'data' => 'chain',
                    'name' => 'chain.title',
                    'title' => trans('strings.chain'),
                    'orderable' => false,
                    'searchable' => false
                ]
            ]);
        }

        if ($correctType == Taxonomy::TYPE_MENU_CATEGORY) {
            $columns = array_merge($columns, [
                [
                    'data' => 'branch_title',
                    'name' => 'branch_title',
                    'title' => trans('strings.branch'),
                    'orderable' => false,
                    'searchable' => false
                ]
            ]);
        }

        return view('admin.taxonomies.index', compact('typeName', 'columns'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  Request  $request
     *
     * @return Application|Factory|\Illuminate\Contracts\View\View|View
     */
    public function create(Request $request)
    {
        $data = $this->essentialData($request);
        $taxonomy = new Taxonomy();
        $taxonomy->type = $data['correctType'];
        $data['taxonomy'] = $taxonomy->load('searchableTags');
        $data['searchableTags'] = $taxonomy->type === Taxonomy::TYPE_FOOD_CATEGORY ? Taxonomy::searchTags()->get() : [];

        return view('admin.taxonomies.form', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $correctType = Taxonomy::getCorrectType($request->type);
        $defaultLocale = localization()->getDefaultLocale();

        $validationRules = [
            "{$defaultLocale}.title" => 'required',
        ];

        $request->validate($validationRules);

        $previousOrderValue = Taxonomy::orderBy('order_column', 'ASC')->first();
        $order = is_null($previousOrderValue) ? 1 : $previousOrderValue->order_column + 1;

        $taxonomy = new Taxonomy();
        $taxonomy->creator_id = $taxonomy->editor_id = auth()->id();
        $taxonomy->type = $correctType;
        $taxonomy->ingredient_category_id = $request->ingredient_category_id;
        if ( ! is_null($request->status)) {
            $taxonomy->status = $request->status;
        }
        if ( ! is_null($request->chain_id)) {
            $taxonomy->chain_id = $request->chain_id;
        }
        if ( ! is_null($request->step)) {
            $taxonomy->step = $request->step;
        }
        $taxonomy->branch_id = $request->branch_id;
        $taxonomy->order_column = $order;
        $taxonomy->save();

        $taxonomy->branches()->sync($request->input('branches'));
        $this->handleSubmittedSingleMedia('cover', $request, $taxonomy);

        // Filling translations
        foreach (localization()->getSupportedLocales() as $key => $value) {
            if ($request->input($key.'.title')) {
                /*$taxonomy->translateOrNew($key)->title = $request->input($key . '.title');
                $taxonomy->translateOrNew($key)->description = $request->input($key . '.description');
                $taxonomy->save();*/
                $taxonomyTranslation = new TaxonomyTranslation();
                $taxonomyTranslation->taxonomy_id = $taxonomy->id;
                $taxonomyTranslation->locale = $key;
                $taxonomyTranslation->title = $request->input($key.'.title');
                $taxonomyTranslation->description = $request->input($key.'.description');
                $taxonomyTranslation->save();
            }
        }

        // Setting up, parent!
        $parentId = $request->parent_id;
        if ( ! is_null($parentId)) {
            $parent = Taxonomy::find($parentId);
            $taxonomy->makeChildOf($parent);
        }
        cache()->tags('taxonomies')->flush();


        return redirect()
            ->route('admin.taxonomies.index', ['type' => $request->type])
            ->with('message', [
                'type' => 'Success',
                'text' => 'Successfully Created'
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Request  $request
     * @param  Taxonomy  $taxonomy
     *
     * @return View
     */
    public function edit(Taxonomy $taxonomy, Request $request)
    {
        $data = $this->essentialData($request);
        $taxonomy->type = $data['correctType'];
        $data['taxonomy'] = $taxonomy->load('searchableTags');
        $data['searchableTags'] = $taxonomy->type === Taxonomy::TYPE_FOOD_CATEGORY ? Taxonomy::searchTags()->get() : [];

        return view('admin.taxonomies.form',
            $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  Taxonomy  $taxonomy
     *
     * @return RedirectResponse
     */
    public function update(Request $request, Taxonomy $taxonomy)
    {
        $correctType = Taxonomy::getCorrectType($request->type);
        $defaultLocale = localization()->getDefaultLocale();
        $validationRules = [
            "{$defaultLocale}.title" => 'required',
        ];

        $request->validate($validationRules);

        $taxonomy->editor_id = auth()->id();
        $taxonomy->ingredient_category_id = $request->ingredient_category_id;
        if ($request->has('icon')) {
            $taxonomy->icon = $request->icon;
        }
        if ( ! is_null($request->status)) {
            $taxonomy->status = $request->status;
        }
        if ( ! is_null($request->step)) {
            $taxonomy->step = $request->step;
        }
        $taxonomy->branch_id = $request->branch_id;

        if ( ! is_null($request->chain_id)) {
            $taxonomy->chain_id = $request->chain_id;
        }
        $taxonomy->save();

        // Filling translations
        foreach (localization()->getSupportedLocales() as $key => $value) {
            if ($request->input($key.'.title')) {
                if (is_null($taxonomyTranslation = TaxonomyTranslation::where('locale', $key)->where('taxonomy_id',
                    $taxonomy->id)->first())) {
                    $taxonomyTranslation = new TaxonomyTranslation();
                    $taxonomyTranslation->taxonomy_id = $taxonomy->id;
                    $taxonomyTranslation->locale = $key;
                }
                $taxonomyTranslation->title = $request->input($key.'.title');
                $description = $request->input($key.'.description');
                if ($description) {
                    $taxonomyTranslation->description = $description;
                }
                $taxonomyTranslation->save();
            }
        }

        // Setting up, parent!
        if ( ! is_null($parentId = $request->parent_id)) {
            $parent = Taxonomy::find($parentId);
            $taxonomy->makeChildOf($parent);
        }

        $taxonomy->branches()->sync($request->input('branches'));
        $taxonomy->searchableTags()->sync($request->input('search_tags'));

        $this->handleSubmittedSingleMedia('cover', $request, $taxonomy);

        cache()->tags('taxonomies')->flush();

        return redirect()
            ->route('admin.taxonomies.index', ['type' => $request->type])
            ->with('message', [
                'type' => 'Success',
                'text' => 'Successfully Updated',
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Taxonomy  $taxonomy
     *
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(Taxonomy $taxonomy)
    {
        if ($taxonomy->hasChildren()) {
            return back()->with('message', [
                'type' => 'Error',
                'text' => 'Delete Children First',
            ]);
        }

        // Todo: test this
        /*if ( ! is_null($taxonomy->thumbnail) &&
            $taxonomy->thumbnail != config('defaults.images.taxonomy_cover') &&
            file_exists(storage_path($taxonomy->thumbnail))) {
            unlink(storage_path('app/public/'.$taxonomy->thumbnail));
        }*/

        if ($taxonomy->delete()) {
            return back()->with('message', [
                'type' => 'Success',
                'text' => 'Successfully Deleted'
            ]);
        }

        return back()->with('message', [
            'type' => 'Error',
            'text' => 'There was an error'
        ]);
    }

    private function getFontAwesomeIcons()
    {
        return collect(config('font-awesome-icons.all'))->map(function ($icon) {

            return [
                'id' => $icon['code'] = $icon['code'].' '.$icon['class'],
                'title' => $icon['title'] = sprintf('<i class="%s fa-2x fa-fw"></i>&nbsp;%s', $icon['class'],
                    $icon['title'])
            ];
        })->pluck('title', 'id');
    }

    /**
     * @param  Request  $request
     * @return array
     */
    private function essentialData(Request $request): array
    {
        $typeName = Taxonomy::getCorrectTypeName($request->type, false);
        $correctType = Taxonomy::getCorrectType($request->type);

        $hasBranch = in_array($correctType, Taxonomy::typesHaving('branch'));

        $menuCategoryData['hasBranch'] = $hasBranch && request()->has('branch_id');
        $branchExists = ! is_null(Branch::foods()->find(request()->input('branch_id')));
        if ($menuCategoryData['hasBranch']) {
            if ($branchExists) {
                $menuCategoryData['branchId'] = request()->input('branch_id');
            } else {
                abort(404);
            }
        }

        $roots = collect([]);
        $hasParent = in_array($correctType, Taxonomy::typesHaving('parent'));
        if ($hasParent) {
            $roots = Taxonomy::roots();
            if ($correctType == Taxonomy::TYPE_GROCERY_CATEGORY) {
                $roots = $roots->groceryCategories();
            } elseif ($correctType == Taxonomy::TYPE_FOOD_CATEGORY) {
                $roots = $roots->foodCategories();
            }

            $roots = $roots->get();
        }

        $fontAwesomeIcons = $this->getFontAwesomeIcons();

        if ($hasBranch) {
            $branches = Branch::whereType(Branch::CHANNEL_FOOD_OBJECT)
                              ->active()
                              ->get()
                              ->mapWithKeys(function ($item) {
                                  return [$item['id'] => $item['chain']['title'].' - '.$item['title'].' ('.$item['region']['english_name'].', '.$item['city']['english_name'].')'];
                              });
        } else {
            $branches = [];
        }
        $ingredientCategories = Taxonomy::ingredientCategories()->active()->get();

        return compact('typeName', 'correctType', 'roots', 'fontAwesomeIcons', 'branches', 'ingredientCategories',
            'menuCategoryData');
    }
}
