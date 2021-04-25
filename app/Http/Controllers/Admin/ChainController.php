<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Region;
use App\Models\Chain;
use DB;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChainController extends Controller
{

    public function __construct()
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
     * @return Application|Factory|\Illuminate\Contracts\View\View|View
     */
    public function index(Request $request)
    {
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
                'title' => trans('strings.chain'),
                'width' => '40',
            ],
            [
                'data' => 'region',
                'name' => 'region',
                'title' => trans('strings.city'),
                'searchable' => false,
                'bSortable' => false,
                'width' => '10',
            ],
            [
                'data' => 'city',
                'name' => 'city',
                'title' => trans('strings.neighborhood'),
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

        return view('admin.chains.index', compact('columns'));
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
        $chain = new Chain();
        $regions = Region::whereCountryId(config('defaults.country.id'))->get();
        $typeName = Chain::getCorrectChannelName($request->type, false);
        $type = Chain::getCorrectChannel($request->type);

        return view('admin.chains.form', compact('chain', 'regions', 'typeName', 'type'));
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
     * @return Factory|View
     */
    public function edit(Chain $chain, Request $request)
    {
        $typeName = Chain::getCorrectChannelName($request->type, false);
        $type = Chain::getCorrectChannel($request->type);
        $regions = Region::whereCountryId(config('defaults.country.id'))->get();
        $chain->load(['region', 'city']);

        return view('admin.chains.form', compact('chain', 'regions', 'typeName', 'type'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  Chain  $chain
     *
     * @return RedirectResponse
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
     * @return RedirectResponse
     * @throws Exception
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
            "city" => 'required',
            "region" => 'required',
        ];
    }


    private function storeUpdateLogic(Request $request, Chain $chain)
    {
        $region = json_decode($request->region);
        $city = json_decode($request->city);
        DB::beginTransaction();
        $chain->city_id = isset($city) ? $city->id : null;
        $chain->region_id = isset($region) ? $region->id : null;
        $chain->primary_phone_number = $request->input('primary_phone_number');
        $chain->secondary_phone_number = $request->input('secondary_phone_number');
        $chain->whatsapp_phone_number = $request->input('whatsapp_phone_number');
//        $chain->primary_color = $request->input('primary_color');
//        $chain->secondary_color = $request->input('secondary_color');
        $chain->type = Chain::getCorrectChannel($request->type);
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
        DB::commit();
    }

}
