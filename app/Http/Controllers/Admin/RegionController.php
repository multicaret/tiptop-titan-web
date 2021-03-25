<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RegionController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:region.permissions.index', ['only' => ['index', 'store']]);
        $this->middleware('permission:region.permissions.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:region.permissions.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:region.permissions.destroy', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     *
     * @return View
     */
    public function index(Request $request)
    {
        $columns = [
            [
                'data' => 'id',
                'name' => 'id',
                'title' => trans('strings.id'),
                'width' => '100',
            ],
            [
                'data' => 'english_name',
                'name' => 'english_name',
                'title' => trans('strings.name'),
            ],
            [
                'data' => 'status',
                'name' => 'status',
                'title' => 'Status',
            ],
            [
                'data' => 'created_at',
                'name' => 'created_at',
                'title' => trans('strings.create_date')
            ],
        ];

        return view('admin.regions.index', compact('columns'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  Request  $request
     *
     * @return View
     */
    public function create(Request $request)
    {
        $region = new Region();

        return view('admin.regions.form', compact('region'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        /*$defaultLocale = localization()->getDefaultLocale();
        $validationData = [
            "{$defaultLocale}.name" => 'required',
        ];

        $request->validate($validationData);*/
        \DB::beginTransaction();
        $region = new Region();
        $region->english_name = $request->input('en.name');
        $region->country_id = config('defaults.country.id');

        // Filling translations
        foreach (localization()->getSupportedLocales() as $key => $value) {
            if ($request->input($key.'.name')) {
                $region->translateOrNew($key)->name = $request->input($key.'.name');
            }
        }

        $region->save();

        \DB::commit();

        return redirect()
            ->route('admin.regions.index')
            ->with('message', [
                'type' => 'Success',
                'text' => 'Added successfully',
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Region  $region
     *
     * @param  Request  $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Region $region, Request $request)
    {
        return view('admin.regions.form', compact('region'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Region  $region
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Region $region)
    {
        /*$defaultLocale = localization()->getDefaultLocale();
        $validationRules = [
            "{$defaultLocale}.name" => 'required',
        ];

        $request->validate($validationRules);*/

        \DB::beginTransaction();

        $region->english_name = $request->input('en.name');
        $region->country_id = config('defaults.country.id');

        // Filling translations
        foreach (localization()->getSupportedLocales() as $key => $value) {
            if ($request->input($key.'.name')) {
                $region->translateOrNew($key)->name = $request->input($key.'.name');
            }
        }
        $region->save();

        \DB::commit();

        return redirect()
            ->route('admin.regions.index', ['type' => $request->type])
            ->with('message', [
                'type' => 'Success',
                'text' => 'Edited successfully',
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Region  $region
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Region $region)
    {
        $region->delete();

        return back()->with('message', [
            'type' => 'Success',
            'text' => 'Successfully Deleted',
        ]);
    }
}
