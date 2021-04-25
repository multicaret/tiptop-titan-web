<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Region;
use DB;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CityController extends Controller
{


    public function __construct()
    {
        $this->middleware('permission:city.permissions.index', ['only' => ['index', 'store']]);
        $this->middleware('permission:city.permissions.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:city.permissions.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:city.permissions.destroy', ['only' => ['destroy']]);
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
                'data' => 'region.name',
                'name' => 'region.translations.name',
                'title' => trans('strings.region'),
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

        return view('admin.cities.index', compact('columns'));
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
        $city = new City();
        $regions = Region::all();

        return view('admin.cities.form', compact('city', 'regions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     *
     * @return RedirectResponse
     */
    public function store(Request $request)
    {

        /*$defaultLocale = localization()->getDefaultLocale();
        $validationData = [
            "{$defaultLocale}.name" => 'required',
        ];

        $request->validate($validationData);*/
        DB::beginTransaction();
        $city = new City();
        $city->english_name = $request->input('en.name');
        $city->region_id = $request->region_id;
        $city->country_id = config('defaults.country.id');
        $city->status = $request->input('status');

        // Filling translations
        foreach (localization()->getSupportedLocales() as $key => $value) {
            if ($request->input($key.'.name')) {
                $city->translateOrNew($key)->name = $request->input($key.'.name');
            }
        }

        $city->save();

        DB::commit();

        return redirect()
            ->route('admin.cities.index')
            ->with('message', [
                'type' => 'Success',
                'text' => 'Added successfully',
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  City  $city
     *
     * @param  Request  $request
     *
     * @return Factory|View
     */
    public function edit(City $city)
    {
        $regions = Region::all();

        return view('admin.cities.form', compact('city', 'regions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  City  $city
     *
     * @return RedirectResponse
     */
    public function update(Request $request, City $city)
    {
        /*$defaultLocale = localization()->getDefaultLocale();
        $validationRules = [
            "{$defaultLocale}.name" => 'required',
        ];

        $request->validate($validationRules);*/

        DB::beginTransaction();

        $city->english_name = $request->input('en.name');
        $city->region_id = $request->region_id;
        $city->country_id = config('defaults.country.id');
        $city->status = $request->input('status');

        // Filling translations
        foreach (localization()->getSupportedLocales() as $key => $value) {
            if ($request->input($key.'.name')) {
                $city->translateOrNew($key)->name = $request->input($key.'.name');
            }
        }
        $city->save();

        DB::commit();

        return redirect()
            ->route('admin.cities.index', ['type' => $request->type])
            ->with('message', [
                'type' => 'Success',
                'text' => 'Edited successfully',
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  City  $city
     *
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(City $city)
    {
        $city->delete();

        return back()->with('message', [
            'type' => 'Success',
            'text' => 'Successfully Deleted',
        ]);
    }
}
