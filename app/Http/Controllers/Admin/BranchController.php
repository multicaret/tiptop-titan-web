<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chain;
use App\Models\Location;
use App\Models\Region;
use App\Models\Branch;
use App\Models\Taxonomy;
use App\Models\WorkingHour;
use DB;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BranchController extends Controller
{

    public function __construct()
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
     * @return Application|Factory|\Illuminate\Contracts\View\View|View
     */
    public function index(Request $request)
    {
        $columns = [
            [
                'data' => 'id',
                'name' => 'id',
                'title' => trans('strings.id'),
                'width' => '20',
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
                'title' => 'City',
                'searchable' => false,
                'bSortable' => false,
                'width' => '10',
            ],
            [
                'data' => 'city',
                'name' => 'city',
                'title' => 'Neighborhood',
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

        return view('admin.branches.index', compact('columns'));
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
        $typeName = Branch::getCorrectChannelName($request->type, false);
        $type = Branch::getCorrectChannel($request->type);
        $contacts = [];

        $branch = new Branch();
        $regions = Region::whereCountryId(config('defaults.country.id'))->get();
        $chains = Chain::whereType($type)->get();
        $branch->chain = Chain::whereType($type)->first();
        $foodCategories = Taxonomy::foodCategories()->get();
        $workingHours = $branch->getWorkingHours();

        return view('admin.branches.form',
            compact('branch', 'regions', 'chains', 'typeName', 'type', 'contacts', 'foodCategories', 'workingHours'));
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
        $request->validate($this->validationRules($request));

        $branch = new Branch();
        $branch->creator_id = $branch->editor_id = auth()->id();
        $this->storeUpdateLogic($request, $branch);

        return redirect()
            ->route('admin.branches.edit', ['type' => $request->type, $branch->uuid])
            ->with('message', [
                'type' => 'Success',
                'text' => 'Successfully created',
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Branch  $branch
     *
     * @param  Request  $request
     *
     * @return Factory|View
     */
    public function edit(Branch $branch, Request $request)
    {
        $typeName = Branch::getCorrectChannelName($request->type, false);
        $type = Branch::getCorrectChannel($request->type);
        $contacts = $branch->locations()->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'email' => $item->emails,
                'phone' => $item->phones
            ];
        });
        $regions = Region::whereCountryId(config('defaults.country.id'))->get();
        $branch->load(['region', 'city', 'chain']);
        $chains = Chain::whereType($type)->get();
        $foodCategories = Taxonomy::foodCategories()->get();
        $workingHours = $branch->getWorkingHours();

        return view('admin.branches.form',
            compact('branch', 'regions', 'typeName', 'type', 'chains', 'contacts', 'foodCategories', 'workingHours'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  Branch  $branch
     *
     * @return RedirectResponse
     */
    public function update(Request $request, Branch $branch)
    {
        $request->validate($this->validationRules($request));
        $branch->editor_id = auth()->id();
        $this->storeUpdateLogic($request, $branch);

        return redirect()->back()
//            ->route('admin.branches.index', ['type' => $request->type])
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
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(Branch $branch)
    {
        $branch->delete();

        return back()->with('message', [
            'type' => 'Success',
            'text' => 'Successfully Deleted',
        ]);
    }


    private function validationRules($request): array
    {
        $defaultLocale = localization()->getDefaultLocale();
        $toValidateInFood = [];
        if ($request->type == Branch::getCorrectChannelName(Branch::CHANNEL_FOOD_OBJECT, 0)) {
            $toValidateInFood = [
                'restaurant_minimum_order' => 'required',
                'restaurant_under_minimum_order_delivery_fee' => 'required',
                'restaurant_fixed_delivery_fee' => 'required',
                'has_tip_top_delivery' => 'required_without:has_restaurant_delivery',
                'has_restaurant_delivery' => 'required_without:has_tip_top_delivery',
            ];
        }

        $generalValidateItems = [
            "{$defaultLocale}.title" => 'required',
            'minimum_order' => 'required',
            'under_minimum_order_delivery_fee' => 'required',
            'fixed_delivery_fee' => 'required',
        ];


        return array_merge($toValidateInFood, $generalValidateItems);
    }


    private function storeUpdateLogic(Request $request, Branch $branch)
    {
        $region = json_decode($request->region);
        $city = json_decode($request->city);
        $chain = json_decode($request->chain);
        DB::beginTransaction();
        $branch->chain_id = $chain->id;
        $branch->city_id = isset($city) ? $city->id : null;
        $branch->region_id = isset($region) ? $region->id : null;
        $branch->latitude = $request->input('latitude');
        $branch->longitude = $request->input('longitude');
        $branch->has_tip_top_delivery = $request->input('has_tip_top_delivery') ? 1 : 0;
        $branch->minimum_order = $request->input('minimum_order');
        $branch->free_delivery_threshold = $request->input('free_delivery_threshold');
        if ($request->has('restaurant_minimum_order')) {
            $branch->restaurant_minimum_order = $request->input('restaurant_minimum_order');
        }
        if ($request->has('restaurant_under_minimum_order_delivery_fee')) {
            $branch->restaurant_under_minimum_order_delivery_fee = $request->input('restaurant_under_minimum_order_delivery_fee');
        }
        if ($request->has('restaurant_fixed_delivery_fee')) {
            $branch->restaurant_fixed_delivery_fee = $request->input('restaurant_fixed_delivery_fee');
        }
        if ($request->has('restaurant_free_delivery_threshold')) {
            $branch->restaurant_fixed_delivery_fee = $request->input('restaurant_free_delivery_threshold');
        }
        $branch->has_restaurant_delivery = $request->input('has_restaurant_delivery') ? 1 : 0;
        $branch->under_minimum_order_delivery_fee = $request->input('under_minimum_order_delivery_fee');
        $branch->fixed_delivery_fee = $request->input('fixed_delivery_fee');
        $branch->primary_phone_number = $request->input('primary_phone_number');
//        $branch->secondary_phone_number = $request->input('secondary_phone_number');
//        $branch->whatsapp_phone_number = $request->input('whatsapp_phone_number');
        $branch->type = Branch::getCorrectChannel($request->type);
        $branch->status = $request->input('status');

        if ($request->input('status') == Branch::STATUS_ACTIVE) {
            $branch->published_at = $request->input('published_at');
        }
        $branch->being_featured_at = $request->input('being_featured_at');
        $branch->being_unfeatured_at = $request->input('being_unfeatured_at');

        $branch->save();

        foreach (localization()->getSupportedLocales() as $key => $value) {
            if ($request->input($key.'.title')) {
                $branch->translateOrNew($key)->title = $request->input($key.'.title');
                $branch->translateOrNew($key)->description = $request->input($key.'.description');
            }
        }
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


        if ( ! is_null($request->days) && is_array($days = json_decode($request->days)) && count($days)) {
            foreach ($days as $dayNumber => $day) {
                if (is_null(
                    $workingHour = WorkingHour::where('workable_id', $branch->id)
                                              ->where('workable_type', Branch::class)
                                              ->where('day', $dayNumber + 1)
                                              ->first()
                )) {
                    $workingHour = new WorkingHour();
                    $workingHour->workable_id = $branch->id;
                    $workingHour->workable_type = Branch::class;
                }
                $workingHour->day = $dayNumber + 1;
                if ($day->is_day_off) {
                    $workingHour->is_day_off = true;
                    $workingHour->opens_at = 0;
                    $workingHour->closes_at = 0;
                } else {
                    $workingHour->opens_at = $day->opens_at;
                    $workingHour->closes_at = $day->closes_at;
                }
                $workingHour->save();
            }
        }

        $branch->save();
        DB::commit();
    }

}
