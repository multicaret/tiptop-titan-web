<?php

namespace App\Http\Controllers\Admin;

use App\Exports\BranchProductsExport;
use App\Exports\ProductsExportGeneral;
use App\Http\Controllers\Controller;
use App\Imports\ProductsImporter;
use App\Jobs\Zoho\CreateBranchAccountJob;
use App\Jobs\Zoho\CreateDeliveryItemJob;
use App\Jobs\Zoho\CreateTipTopDeliveryItemJob;
use App\Jobs\Zoho\SyncBranchJob;
use App\Models\Branch;
use App\Models\Chain;
use App\Models\Location;
use App\Models\Region;
use App\Models\Taxonomy;
use App\Models\WorkingHour;
use DB;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class BranchController extends Controller
{

    public function __construct()
    {
        $branchType = request('type');
        $this->middleware('permission:'.$branchType.'.permissions.index', ['only' => ['index', 'store']]);
        $this->middleware('permission:'.$branchType.'.permissions.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:'.$branchType.'.permissions.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:'.$branchType.'.permissions.destroy', ['only' => ['destroy']]);
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
                'data' => 'uuid',
                'name' => 'uuid',
                'title' => 'UUID',
                'orderable' => false,
                'searchable' => true,
                'visible' => false,
            ],
            [
                'data' => 'title',
                'name' => 'translations.title',
                'title' => 'Title',
                'orderable' => false,
                'searchable' => true
            ],
            [
                'data' => 'chain',
                'name' => 'chain_id',
                'title' => 'Chain',
                'width' => '10',
            ],
            [
                'data' => 'region',
                'name' => 'region_id',
                'title' => 'City',
                'searchable' => false,
                'bSortable' => false,
                'width' => '10',
            ],
            [
                'data' => 'city',
                'name' => 'city_id',
                'title' => 'Neighborhood',
                'searchable' => false,
                'bSortable' => false,
                'width' => '10',
            ],
            [
                'data' => 'status',
                'name' => 'status',
                'searchable' => true,
                'bSortable' => true,
                'title' => trans('strings.status'),
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
        $searchTags = Branch::isFood() ? Taxonomy::searchTags()->get() : [];
        $branch = new Branch();
        $regions = Region::active()->whereCountryId(config('defaults.country.id'))->get();
        $chains = Chain::active()->whereType($type)->get();
        $branch->chain_id = optional(Chain::whereType($type)->first())->id;
        $branch->load('chain');
        $foodCategories = Taxonomy::foodCategories()->get();
        $workingHours = $branch->getWorkingHours();

        return view('admin.branches.form',
            compact('branch',
                'regions',
                'chains',
                'typeName',
                'type',
                'contacts',
                'searchTags',
                'foodCategories',
                'workingHours'));
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

        Bus::chain(
            [
                new SyncBranchJob($branch),
                new CreateDeliveryItemJob($branch),
                new CreateTipTopDeliveryItemJob($branch),
                new CreateBranchAccountJob($branch),
            ]
        );

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
        $branch->load([
            'region',
            'city',
            'chain',
            'locations',
        ]);
        $contacts = $branch->locations()->get()->map(fn($item) => [
            'id' => $item->id,
            'name' => $item->name,
            'position' => $item->position,
            'email' => $item->emails,
            'phone' => $item->phones
        ]);
        $searchTags = Branch::isFood() ? Taxonomy::searchTags()->get() : [];
        $regions = Region::active()->whereCountryId(config('defaults.country.id'))->get();
        $chains = Chain::active()->whereType($type)->get();
        $foodCategories = Taxonomy::foodCategories()->get();
        $workingHours = $branch->getWorkingHoursForJs();

        // To apply to this task requirements:
        // https://app.clickup.com/t/2609896/DEV-1424
        if ($branch->status == Branch::STATUS_INACTIVE) {
            $branch->status = Branch::STATUS_DRAFT;
        }


        return view('admin.branches.form',
            compact('branch',
                'regions',
                'typeName',
                'type',
                'chains',
                'contacts',
                'searchTags',
                'foodCategories',
                'workingHours'));
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
                'has_tip_top_delivery' => 'required_without:has_restaurant_delivery',
                'has_restaurant_delivery' => 'required_without:has_tip_top_delivery',
            ];
            if ($request->has('has_tip_top_delivery') && $request->has('has_tip_top_delivery') == 'on') {
                $toValidateInFood['minimum_order'] = 'required';
                $toValidateInFood['under_minimum_order_delivery_fee'] = 'required';
                $toValidateInFood['fixed_delivery_fee'] = 'required';
            }

            if ($request->has('has_restaurant_delivery') && $request->has('has_restaurant_delivery') == 'on') {
                $toValidateInFood['restaurant_minimum_order'] = 'required';
                $toValidateInFood['restaurant_under_minimum_order_delivery_fee'] = 'required';
                $toValidateInFood['restaurant_fixed_delivery_fee'] = 'required';
            }

            if ($request->has('has_jet_delivery') && $request->has('has_jet_delivery') == 'on') {
                $toValidateInFood['jet_fixed_delivery_fee'] = 'required';
                $toValidateInFood['jet_delivery_commission_rate'] = 'required';
                $toValidateInFood['jet_extra_delivery_fee_per_km'] = 'required';
                $toValidateInFood['jet_minimum_order'] = 'required';
            }
        }

        $generalValidateItems = [
            "{$defaultLocale}.title" => 'required',
            'city' => 'required',
            'region' => 'required',
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
        $branch->full_address = $request->input('full_address');
        if ($request->type == Branch::getCorrectChannelName(Branch::CHANNEL_FOOD_OBJECT, 0)) {
            $branch->has_tip_top_delivery = $request->input('has_tip_top_delivery') == 'on' ? 1 : 0;
            $branch->has_restaurant_delivery = $request->input('has_restaurant_delivery') == 'on' ? 1 : 0;
            $branch->has_jet_delivery = $request->input('has_jet_delivery') == 'on' ? 1 : 0;
        } else {
            $branch->has_tip_top_delivery = 1;
        }
        $inputs = [
            //tiptop inputs
            'minimum_order',
            'fixed_delivery_fee',
            'under_minimum_order_delivery_fee',
            'free_delivery_threshold',
            'extra_delivery_fee_per_km',
            //restaurant inputs
            'restaurant_minimum_order',
            'restaurant_fixed_delivery_fee',
            'restaurant_under_minimum_order_delivery_fee',
            'restaurant_free_delivery_threshold',
            'restaurant_extra_delivery_fee_per_km',
            //jet inputs
            'jet_minimum_order',
            'jet_fixed_delivery_fee',
            'jet_delivery_commission_rate',
            'jet_extra_delivery_fee_per_km',
        ];

        foreach ($inputs as $input) {
            if ($request->input($input)) {
                $branch->$input = $request->input($input);
            } else {
                $branch->$input = 0;
            }
        }

        $branch->primary_phone_number = $request->input('primary_phone_number');
//        $branch->secondary_phone_number = $request->input('secondary_phone_number');
//        $branch->whatsapp_phone_number = $request->input('whatsapp_phone_number');
        $branch->type = Branch::getCorrectChannel($request->type);
        $branch->status = $request->input('status');
        $branch->is_open_now = ! $request->has('is_closed_now');

        if (is_null($branch->published_at) && $request->input('status') == Branch::STATUS_ACTIVE) {
            $branch->published_at = now();
        }
        $branch->featured_at = $request->input('featured_at');

        $branch->save();

        foreach (localization()->getSupportedLocales() as $key => $value) {
            if ($request->input($key.'.title')) {
                $branch->translateOrNew($key)->title = $request->input($key.'.title');
                $branch->translateOrNew($key)->description = $request->input($key.'.description');
            }
        }
        $branch->searchTags()->sync($request->input('search_tags'));
        $branch->foodCategories()->sync($request->input('food_categories'));
        $requestContactDetails = json_decode($request->contactDetails);
        $contactToDelete = $branch->locations()->get()->pluck('id')->toArray();
        $contactToDelete = array_combine($contactToDelete, $contactToDelete);
        foreach ($requestContactDetails as $requestContactDetail) {
            if (isset($requestContactDetail->id) && ! is_null($location = Location::whereId($requestContactDetail->id)->first())) {
                $location->name = $requestContactDetail->name;
                $location->position = $requestContactDetail->position;
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
                $location->position = $requestContactDetail->position;
                $location->emails = $requestContactDetail->email;
                $location->phones = $requestContactDetail->phone;
            }
            $location->save();
        }
        Location::whereIn('id', $contactToDelete)->delete();
//        $this->storeWorkingHours($request, $branch);
        $branch->save();
        DB::commit();

        cache()->tags('branches', 'api-home')->flush();
    }

    /**
     * @param  Request  $request
     * @param  Branch  $branch
     */
    public function storeWorkingHours(Request $request, Branch $branch): RedirectResponse
    {
        $businessHours = $request->input('business_hours');
        $workingHoursIdsWithDayNumberForDelete = [];
        if ( ! is_null(json_decode($businessHours)) && is_array($days = json_decode($businessHours, true))) {
            $workingHoursIdsWithDayNumberForDelete = WorkingHour::where('workable_id', $branch->id)
                                                                ->where('workable_type', Branch::class)
                                                                ->orderBy('day')
                                                                ->get()
                                                                ->groupBy('day')
                                                                ->map(fn($v) => $v->pluck('id', 'id')->all())
                                                                ->all();
            foreach ($days as $dayNumber => $dayShifts) {
                $dayNumber = WorkingHour::numberOfDays()[$dayNumber];
                foreach ($dayShifts as $index => $dayShift) {
//                    $dayShift['isOpen'] = ! is_null($dayShift['open']) && ! is_null($dayShift['close']);
                    if (is_null($workingHour = WorkingHour::find($dayShift['id']))) {
                        $workingHour = new WorkingHour();
                        $workingHour->workable_id = $branch->id;
                        $workingHour->workable_type = Branch::class;
                    }
                    $workingHour->day = $dayNumber;
                    $workingHour->is_day_off = ! $dayShift['isOpen'];
                    $workingHour->opens_at = WorkingHour::updateHourValue($dayShift, 'open');
                    $workingHour->closes_at = WorkingHour::updateHourValue($dayShift, 'close');
                    if ($index === 0) {
                        $workingHour->save();
                    } else {
                        if ( ! (empty($dayShift['open']) || empty($dayShift['close']))) {
                            $workingHour->save();
                        }
                    }

                    unset($workingHoursIdsWithDayNumberForDelete[$dayNumber][$workingHour->id]);
                }
            }
        }
        WorkingHour::whereIn('id', \Arr::flatten($workingHoursIdsWithDayNumberForDelete))->delete();

        return redirect()->back()->with('message',
            ['type' => 'Success', 'text' => 'Working hours updated successfully',]);
    }

    public function importFromExcel(Request $request, Branch $branch): RedirectResponse
    {
        if ($request->has('excel-file')) {
            $filename = \Str::of('branch-products-')
                            ->append($branch->uuid)
                            ->append('-')
                            ->append(\now()->timestamp)
                            ->append('.')
                            ->append($request->file('excel-file')->clientExtension())
                            ->jsonSerialize();
            $request->file('excel-file')->storeAs(storage_path('/'), $filename);
            $path = storage_path("{$filename}");
            $withOptions = $request->input('with_options') === 'true';
            $productsImporter = new ProductsImporter($branch->chain, $branch, $withOptions);
            $productsImporter->isChecking = true;
            try {
                // Work on validation data on all sheets
                $productsImporter->onlySheets(ProductsImporter::getAllWorksheets());
                Excel::import($productsImporter, $path);
                $productsImporter->resetAllIdsAttributesValues();
                // Start importing
                $productsImporter->isChecking = false;
                // Start importing menu categories
                $productsImporter->onlySheets(ProductsImporter::WORKSHEET_MENU_CATEGORIES);
                Excel::import($productsImporter, $path);

                // Start importing branch products
                $productsImporter->onlySheets(ProductsImporter::WORKSHEET_PRODUCTS);
                Excel::import($productsImporter, $path);

                // Start importing products options
                $productsImporter->onlySheets(ProductsImporter::WORKSHEET_OPTIONS);
                Excel::import($productsImporter, $path);

                // Start importing products options selections
                $productsImporter->onlySheets(ProductsImporter::WORKSHEET_SELECTIONS);
                Excel::import($productsImporter, $path);

                $messageData = [
                    'type' => 'Success',
                    'text' => 'Imported successfully',
                ];

                return redirect()->route('admin.branches.edit', [$branch, 'type' => $request->type])
                                 ->with('message', $messageData);
            } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
                $failures = $e->failures();
                $errors = [];
                foreach ($failures as $failure) {
                    $sheetName = \Str::title($this->getWorksheetName($failure->values()));
                    $rowIndex = $failure->row();
                    $fieldName = $failure->attribute();
                    $errorMessage = implode(PHP_EOL, $failure->errors());
                    $errors[] = "* Error on Row index <b>{$rowIndex}</b> on field name: {$fieldName} on Worksheet {$sheetName}. <br>Message: {$errorMessage}<br>";
                }

                $messageData = [
                    'type' => 'Error',
                    'text' => 'Imported failed',
                    'message' => implode('', $errors),
                ];
            } catch (Exception $exception) {
                $messageData = [
                    'type' => 'Error',
                    'text' => 'Imported failed',
                    'message' => $exception->getMessage(),
                ];
            }

            return redirect()->route('admin.branches.edit', [$branch, 'type' => $request->type])
                             ->with('message-alert', str_replace('"', '\'', $messageData));
        }

        $messageData = [
            'type' => 'Error',
            'text' => 'Imported failed',
            'message' => implode('', ['File not found']),
        ];

        return redirect()->route('admin.branches.edit', [$branch, 'type' => $request->type])
                         ->with('message-alert', str_replace('"', '\'', $messageData));
    }

    public function exportToExcel(Request $request, Branch $branch)
    {
        $filename = \Str::of('branch-products-')
                        ->append($branch->uuid)
                        ->append('-')
                        ->append(\now()->timestamp)
                        ->append('.xlsx')
                        ->jsonSerialize();

        switch ($request->for) {
            case 'importing':
                return Excel::download(new BranchProductsExport($branch), $filename);
            default:
                return Excel::download(new ProductsExportGeneral($branch), $filename);
        }


    }

    private function getWorksheetName(array $values): string
    {
        if (array_key_exists('category_ids', $values)) {
            return ProductsImporter::WORKSHEET_PRODUCTS;
        }
        if (array_key_exists('is_based_on_ingredients', $values)) {
            return ProductsImporter::WORKSHEET_OPTIONS;
        }
        if (array_key_exists('product_option_id', $values)) {
            return ProductsImporter::WORKSHEET_SELECTIONS;
        }

        return ProductsImporter::WORKSHEET_MENU_CATEGORIES;
    }

    public function applyDiscount(Request $request, Branch $branch) {

        $request->validate([
            'discount_percentage' => 'required|numeric',
            'price_discount_began_at' => 'required|date',
            'price_discount_finished_at' => 'required|date|after:price_discount_began_at',

        ]);
        $branch->products()->whereHas('category',function ($query) use ($request){
            $query->whereIn('taxonomies.id',$request->menu_categories);
        })->update([
            'price_discount_by_percentage' => 1,
            'price_discount_amount' => $request->discount_percentage,
            'price_discount_began_at' => $request->price_discount_began_at,
            'price_discount_finished_at' => $request->price_discount_finished_at,
        ]);

        return redirect()->back()->with('message',
            ['type' => 'Success', 'text' => 'Discount applied successfully',]);
    }

}
