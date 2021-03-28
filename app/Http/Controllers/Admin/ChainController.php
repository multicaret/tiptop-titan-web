<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Region;
use App\Models\Chain;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChainController extends Controller
{

    function __construct()
    {
//        $this->middleware('permission:chain.permissions.index', ['only' => ['index', 'store']]);
//        $this->middleware('permission:chain.permissions.create', ['only' => ['create', 'store']]);
//        $this->middleware('permission:chain.permissions.edit', ['only' => ['edit', 'update']]);
//        $this->middleware('permission:chain.permissions.destroy', ['only' => ['destroy']]);
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

        return view('admin.chains.index', compact('columns', 'typeName'));
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
        $chain = new Chain();
        $regions = Region::whereCountryId(config('defaults.country.id'))->get();
        $typeName = Chain::getCorrectTypeName($request->type, false);
        $type = Chain::getCorrectType($request->type);

        return view('admin.chains.form', compact('chain', 'regions', 'typeName', 'type'));
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

        $chain = new Chain();
        $chain->creator_id = $chain->editor_id = auth()->id();
        $this->storeUpdateLogic($request, $chain);

        return redirect()
            ->route('admin.chains.index', ['type' => $request->type])
            ->with('message', [
                'type' => 'Success',
                'text' => __('strings.successfully_created'),
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Chain  $chain
     *
     * @param  Request  $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Chain $chain, Request $request)
    {
        $typeName = Chain::getCorrectTypeName($request->type, false);
        $type = Chain::getCorrectType($request->type);
        $regions = Region::whereCountryId(config('defaults.country.id'))->get();
        $chain->load(['region', 'city']);

        return view('admin.chains.form', compact('chain', 'regions', 'typeName', 'type'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Chain  $chain
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Chain $chain)
    {
        $chain->editor_id = auth()->id();
        $this->storeUpdateLogic($request, $chain);

        return redirect()
            ->route('admin.chains.index', ['type' => $request->type])
            ->with('message', [
                'type' => 'Success',
                'text' => 'Edited successfully',
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Chain  $chain
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Chain $chain)
    {
        $chain->delete();

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


    private function storeUpdateLogic(Request $request, Chain $chain)
    {
        $region = json_decode($request->region);
        $city = json_decode($request->city);
        \DB::beginTransaction();
        $chain->city_id = isset($city) ? $city->id : null;
        $chain->region_id = isset($region) ? $region->id : null;
        $chain->primary_phone_number = $request->input('primary_phone_number');
        $chain->secondary_phone_number = $request->input('secondary_phone_number');
        $chain->whatsapp_phone_number = $request->input('whatsapp_phone_number');
//        $chain->primary_color = $request->input('primary_color');
//        $chain->secondary_color = $request->input('secondary_color');
        $chain->type = Chain::getCorrectType($request->type);
        $chain->status = $request->input('status');
        $chain->save();

        foreach (localization()->getSupportedLocales() as $key => $value) {
            if ($request->input($key.'.title')) {
                $chain->translateOrNew($key)->title = $request->input($key.'.title');
                $chain->translateOrNew($key)->description = $request->input($key.'.description');
            }
        }
        $chain->save();
        $this->handleSubmittedSingleMedia('cover', $request, $chain);
        $this->handleSubmittedSingleMedia('logo', $request, $chain);
        $this->handleSubmittedMedia($request, 'gallery', $chain, 'gallery');
        \DB::commit();
    }

}