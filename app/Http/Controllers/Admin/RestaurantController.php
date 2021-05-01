<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Location;
use App\Models\Region;
use App\Models\Chain;
use App\Models\Taxonomy;
use DB;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RestaurantController extends Controller
{

    public function __construct()
    {

        $branchType = \request('type');
        $this->middleware('permission:'.$branchType.'.permissions.index', ['only' => ['index', 'store']]);
        $this->middleware('permission:'.$branchType.'.permissions.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:'.$branchType.'.permissions.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:'.$branchType.'.permissions.destroy', ['only' => ['destroy']]);
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
        $type = Chain::CHANNEL_FOOD_OBJECT;
        $chains = [];
        $contacts = [];
        $branch = new Branch();
        $foodCategories = Taxonomy::foodCategories()->get();

        return view('admin.restaurants.form',
            compact('chains', 'chain', 'branch', 'contacts', 'regions', 'typeName', 'type', 'foodCategories'));
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
        $branch = new Branch();
        $branch->creator_id = $branch->editor_id = auth()->id();
        $this->storeUpdateLogic($request, $chain, $branch);

        return redirect()
            ->route('admin.chains.index', ['type' => Chain::getCorrectChannelName(Chain::CHANNEL_FOOD_OBJECT)])
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


    private function storeUpdateLogic(Request $request, Chain $chain, Branch $branch)
    {
        $region = json_decode($request->region);
        $city = json_decode($request->city);
        DB::beginTransaction();
        $chain->city_id = isset($city) ? $city->id : null;
        $chain->region_id = isset($region) ? $region->id : null;
        $chain->primary_phone_number = $request->input('primary_phone_number');
        $chain->secondary_phone_number = $request->input('secondary_phone_number');
        $chain->whatsapp_phone_number = $request->input('whatsapp_phone_number');
        $chain->type = Chain::CHANNEL_FOOD_OBJECT;
        $chain->status = $request->input('status');
        $chain->save();

        $this->handleSubmittedSingleMedia('cover', $request, $chain);
        $this->handleSubmittedSingleMedia('logo', $request, $chain);
        $this->handleSubmittedMedia($request, 'gallery', $chain, 'gallery');


        $branch->chain_id = $chain->id;
        $branch->city_id = isset($city) ? $city->id : null;
        $branch->region_id = isset($region) ? $region->id : null;
        $branch->latitude = $request->input('latitude');
        $branch->longitude = $request->input('longitude');
        $branch->has_tip_top_delivery = $request->input('has_tip_top_delivery') ? 1 : 0;
        $branch->minimum_order = $request->input('minimum_order');
        $branch->restaurant_minimum_order = $request->input('restaurant_minimum_order');
        $branch->has_restaurant_delivery = $request->input('has_restaurant_delivery') ? 1 : 0;
        $branch->under_minimum_order_delivery_fee = $request->input('under_minimum_order_delivery_fee');
        $branch->restaurant_under_minimum_order_delivery_fee = $request->input('restaurant_under_minimum_order_delivery_fee');
        $branch->fixed_delivery_fee = $request->input('fixed_delivery_fee');
        $branch->restaurant_fixed_delivery_fee = $request->input('restaurant_fixed_delivery_fee');
        $branch->primary_phone_number = $request->input('primary_phone_number');
        $branch->type = Chain::CHANNEL_FOOD_OBJECT;
        $branch->status = $request->input('status');
        $branch->save();

        foreach (localization()->getSupportedLocales() as $key => $value) {
            if ($request->input($key.'.title')) {
                $chain->translateOrNew($key)->title = $branch->translateOrNew($key)->title = $request->input($key.'.title');
                $chain->translateOrNew($key)->description = $branch->translateOrNew($key)->description = $request->input($key.'.description');
            }
        }
        $chain->save();
        $branch->foodCategories()->sync($request->input('food_categories'));
        $requestContactDetails = json_decode($request->contactDetails);
        $contactToDelete = $branch->locations()->get()->pluck('id')->toArray();
        $contactToDelete = array_combine($contactToDelete, $contactToDelete);
        foreach ($requestContactDetails as $requestContactDetail) {
            if (isset($requestContactDetail->id) && ! is_null($location = Location::whereId($requestContactDetail->id)->first())) {
                $location->name = $requestContactDetail->name;
                $location->phones = $requestContactDetail->phone;
                $location->emails = $requestContactDetail->email;
                $location->type = Location::TYPE_CONTACT;
                unset($contactToDelete[$location->id]);
            } else {
                $location = new Location();
                $location->creator_id = $location->editor_id = auth()->id();
                $location->contactable_id = $branch->id;
                $location->contactable_type = Branch::class;
                $location->type = Location::TYPE_CONTACT;
                $location->name = $requestContactDetail->name;
                $location->emails = $requestContactDetail->email;
                $location->phones = $requestContactDetail->phone;
            }
            $location->save();
        }
        Location::whereIn('id', $contactToDelete)->delete();

        $branch->save();
        DB::commit();
    }

}
