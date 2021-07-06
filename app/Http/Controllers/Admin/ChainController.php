<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\ChainProductsImporter;
use App\Imports\ProductsImporter;
use App\Models\Chain;
use App\Models\Region;
use DB;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class ChainController extends Controller
{

    public function __construct()
    {
        $chainType = request('type');
        $this->middleware('permission:'.$chainType.'.permissions.index', ['only' => ['index', 'store']]);
        $this->middleware('permission:'.$chainType.'.permissions.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:'.$chainType.'.permissions.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:'.$chainType.'.permissions.destroy', ['only' => ['destroy']]);
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
                'searchable' => false,
            ],
            [
                'data' => 'title',
                'name' => 'translations.title',
                'title' => trans('strings.chain'),
                'width' => '40',
                'searchable' => true,
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
                'searchable' => false,
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

    public function sync(Chain $chain)
    {
        return view('admin.chains.sync', compact('chain'));
    }

    public function postSync(Chain $chain, Request $request)
    {
        $branchIds = $request->sync_branch_ids;
        $chainsIds = [$chain->id];

        try {
            Artisan::call('datum:sync-chains', [
                '--id' => $chainsIds,
                '--branchIds' => $branchIds,
            ]);
            $outputMessage = Artisan::output();
        } catch (Exception $e) {
            $outputMessage = $e->getMessage();
        }

        return redirect(route('admin.index', $chain))
            ->with('message', [
                'type' => 'Success',
                'text' => str_replace(PHP_EOL, '', $outputMessage),
            ]);
    }


    public function productsExcelImporter(Request $request, $chain): RedirectResponse
    {
//        Artisan::call('cache:clear');
        $chain = Chain::whereUuid($chain)->first();
        if ($request->has('excel-file')) {
            $filename = \Str::of('chain-products-')
                            ->append($chain->uuid)
                            ->append('-')
                            ->append(\now()->timestamp)
                            ->append('.')
                            ->append($request->file('excel-file')->clientExtension())
                            ->jsonSerialize();
            $request->file('excel-file')->storeAs(storage_path('/'), $filename);
            $path = storage_path("{$filename}");
            $productsImporter = new ChainProductsImporter($chain);
            $productsImporter->isChecking = true;
            try {
                // Start importing branch products
                $productsImporter->onlySheets(ProductsImporter::WORKSHEET_PRODUCTS);
                Excel::import($productsImporter, $path);

                $messageData = [
                    'type' => 'Success',
                    'text' => 'Imported successfully',
                ];


                return redirect()->route('admin.chains.edit', [$chain, 'type' => $request->type])
                                 ->with('message', $messageData);
            } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
                $failures = $e->failures();
                $errors = [];
                foreach ($failures as $failure) {
                    $sheetName = 'Products';
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

            return redirect()->route('admin.chains.edit', [$chain, 'type' => $request->type])
                             ->with('message-alert', str_replace('"', '\'', $messageData));
        }

        $messageData = [
            'type' => 'Error',
            'text' => 'Imported failed',
            'message' => implode('', ['File not found']),
        ];

        return redirect()->route('admin.chains.edit', [$chain, 'type' => $request->type])
                         ->with('message-alert', str_replace('"', '\'', $messageData));
    }
}
