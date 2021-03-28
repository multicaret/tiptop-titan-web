<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Taxonomy;
use App\Models\TaxonomyTranslation;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaxonomyController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:taxonomy.permissions.index', ['only' => ['index', 'store']]);
        $this->middleware('permission:taxonomy.permissions.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:taxonomy.permissions.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:taxonomy.permissions.destroy', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     *
     * @return \Illuminate\Http\Response
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
        if ($correctType == \App\Models\Taxonomy::TYPE_UNIT) {
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
        if ($correctType == \App\Models\Taxonomy::TYPE_FOOD_CATEGORY) {
            $columns = array_merge($columns, [
                [
                    'data' => 'branches',
                    'name' => 'branches',
                    'title' => trans('strings.branches'),
                    'orderable' => false,
                    'searchable' => false
                ]
            ]);
        }

        if (in_array($correctType,
                [Taxonomy::TYPE_GROCERY_CATEGORY, Taxonomy::TYPE_FOOD_CATEGORY])) {
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
                    'data' => 'branch.title',
                    'name' => 'branch.title',
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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|View
     */
    public function create(Request $request)
    {
        [
            $typeName, $correctType, $roots, $fontAwesomeIcons, $branches, $ingredientCategories
        ] = $this->loadData($request);

        $taxonomy = new Taxonomy();
        $taxonomy->type = $correctType;

        return view('admin.taxonomies.form',
            compact('taxonomy', 'roots', 'correctType', 'typeName', 'fontAwesomeIcons', 'branches',
                'ingredientCategories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
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
        [
            $typeName, $correctType, $roots, $fontAwesomeIcons, $branches, $ingredientCategories
        ] = $this->loadData($request);

        return view('admin.taxonomies.form',
            compact('taxonomy', 'roots', 'correctType', 'typeName', 'fontAwesomeIcons', 'branches',
                'ingredientCategories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Taxonomy  $taxonomy
     *
     * @return \Illuminate\Http\RedirectResponse
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
        if ( ! is_null($request->branch_id)) {
            $taxonomy->branch_id = $request->branch_id;
        }
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
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
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
    private function loadData(Request $request): array
    {
        $typeName = Taxonomy::getCorrectTypeName($request->type, false);
        $correctType = Taxonomy::getCorrectType($request->type);

        $roots = collect([]);
        $hasParent = in_array($correctType, Taxonomy::typesHaving('parent'));
        if ($hasParent) {
            $roots = Taxonomy::roots()
                             ->groceryCategories()
                             ->get();
        }

        $fontAwesomeIcons = $this->getFontAwesomeIcons();
        $branches = \App\Models\Branch::whereType(\App\Models\Branch::TYPE_GROCERY_BRANCH)->get();
        $ingredientCategories = Taxonomy::ingredientCategories()->get();

        return [$typeName, $correctType, $roots, $fontAwesomeIcons, $branches, $ingredientCategories];
    }
}
