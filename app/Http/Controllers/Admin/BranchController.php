<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chain;
use App\Models\Region;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BranchController extends Controller
{

    function __construct()
    {
//        $this->middleware('permission:branch.permissions.index', ['only' => ['index', 'store']]);
//        $this->middleware('permission:branch.permissions.create', ['only' => ['create', 'store']]);
//        $this->middleware('permission:branch.permissions.edit', ['only' => ['edit', 'update']]);
//        $this->middleware('permission:branch.permissions.destroy', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|View
     */
    public function index(Request $request)
    {
        $typeName = Chain::getCorrectTypeName($request->type, false);
        $columns = [
            [
                'data' => 'id',
                'name' => 'id',
                'title' => trans('strings.id'),
                'width' => '1',
            ],
            [
                'data' => 'title',
                'name' => 'translations.title',
                'title' => trans('strings.title'),
                'width' => '40',
            ],
            [
                'data' => 'chain',
                'name' => 'chain',
                'title' => 'Chain',
                'searchable' => false,
                'bSortable' => false,
                'width' => '10',
            ],
            [
                'data' => 'region',
                'name' => 'region',
                'title' => 'Region',
                'searchable' => false,
                'bSortable' => false,
                'width' => '10',
            ],
            [
                'data' => 'city',
                'name' => 'city',
                'title' => 'City',
                'searchable' => false,
                'bSortable' => false,
                'width' => '10',
            ],
            [
                'data' => 'created_at',
                'name' => 'created_at',
                'title' => trans('strings.create_date'),
                'width' => '10',
            ],
        ];

        return view('admin.branches.index', compact('columns', 'typeName'));
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
        $typeName = Branch::getCorrectTypeName($request->type, false);
        $type = Branch::getCorrectType($request->type);

        $branch = new Branch();
        $regions = Region::whereCountryId(config('defaults.country.id'))->get();
        $chains = Chain::whereType($type)->get();
        $branch->chain = Chain::whereType($type)->first();

        return view('admin.branches.form', compact('branch', 'regions', 'chains', 'typeName', 'type'));
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
        $request->validate($this->validationRules());

        $branch = new Branch();
        $branch->creator_id = $branch->editor_id = auth()->id();
        $this->storeUpdateLogic($request, $branch);

        return redirect()
            ->route('admin.branches.index', ['type' => $request->type])
            ->with('message', [
                'type' => 'Success',
                'text' => __('strings.successfully_created'),
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Branch  $branch
     *
     * @param  Request  $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Branch $branch, Request $request)
    {
        $typeName = Branch::getCorrectTypeName($request->type, false);
        $type = Branch::getCorrectType($request->type);

        $regions = Region::whereCountryId(config('defaults.country.id'))->get();
        $branch->load(['region', 'city', 'chain']);
        $chains = Branch::whereType($type)->get();

        return view('admin.branches.form', compact('branch', 'regions', 'typeName', 'type', 'chains'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Branch  $branch
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Branch $branch)
    {
        $branch->editor_id = auth()->id();
        $this->storeUpdateLogic($request, $branch);

        return redirect()
            ->route('admin.branches.index', ['type' => $request->type])
            ->with('message', [
                'type' => 'Success',
                'text' => 'Edited successfully',
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Branch  $branch
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Branch $branch)
    {
        $branch->delete();

        return back()->with('message', [
            'type' => 'Success',
            'text' => 'Successfully Deleted',
        ]);
    }


    private function validationRules(): array
    {
        $defaultLocale = localization()->getDefaultLocale();

        return [
            "{$defaultLocale}.title" => 'required',
//            "old_price" => 'required|numeric|min:1',
        ];
    }


    private function storeUpdateLogic(Request $request, Branch $branch)
    {
        $region = json_decode($request->region);
        $city = json_decode($request->city);
        $chain = json_decode($request->chain);
        \DB::beginTransaction();
        $branch->chain_id = $chain->id;
        $branch->city_id = isset($city) ? $city->id : null;
        $branch->region_id = isset($region) ? $region->id : null;
        $branch->latitude = $request->input('latitude');
        $branch->longitude = $request->input('longitude');
        $branch->minimum_order = $request->input('minimum_order');
        $branch->under_minimum_order_delivery_fee = $request->input('under_minimum_order_delivery_fee');
        $branch->fixed_delivery_fee = $request->input('fixed_delivery_fee');
        $branch->primary_phone_number = $request->input('primary_phone_number');
        $branch->secondary_phone_number = $request->input('secondary_phone_number');
        $branch->whatsapp_phone_number = $request->input('whatsapp_phone_number');
        $branch->type = Branch::getCorrectType(1);
        $branch->status = $request->input('status');
        $branch->save();

        foreach (localization()->getSupportedLocales() as $key => $value) {
            if ($request->input($key.'.title')) {
                $branch->translateOrNew($key)->title = $request->input($key.'.title');
                $branch->translateOrNew($key)->description = $request->input($key.'.description');
            }
        }
        $branch->save();
        \DB::commit();
    }

}
