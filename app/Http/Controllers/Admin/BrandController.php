<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Brand;
use DB;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BrandController extends Controller
{

    public function __construct()
    {
//        $this->middleware('permission:brand.permissions.index', ['only' => ['index', 'store']]);
//        $this->middleware('permission:brand.permissions.create', ['only' => ['create', 'store']]);
//        $this->middleware('permission:brand.permissions.edit', ['only' => ['edit', 'update']]);
//        $this->middleware('permission:brand.permissions.destroy', ['only' => ['destroy']]);
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
                'title' => 'ID',
                'width' => '100',
            ],
            [
                'data' => 'title',
                'name' => 'translations.title',
                'title' => 'Title',
                'orderable' => false,
                'searchable' => false
            ],
            [
                'data' => 'status',
                'name' => 'status',
                'title' => 'Status',
                'orderable' => false,
                'searchable' => false
            ],

        ];
        return view('admin.brands.index', compact('columns'));

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
        $data['brand'] = new Brand();

        return view('admin.brands.form', $data);
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
        $defaultLocale = localization()->getDefaultLocale();
        $validationRules = [
            "{$defaultLocale}.title" => 'required',
        ];
        $request->validate($validationRules);

        DB::beginTransaction();
        $brand = new Brand();
        $brand->status = $request->status;
        $brand->save();

        // Filling translations
        foreach (localization()->getSupportedLocales() as $key => $value) {
            if ($request->input($key.'.title')) {
                $brand->translateOrNew($key)->title = $request->input($key.'.title');
            }
        }

        $brand->save();


        $this->handleSubmittedSingleMedia('cover', $request, $brand);

        DB::commit();

        return redirect()
            ->route('admin.brands.index')
            ->with('message', [
                'type' => 'Success',
                'text' => 'Added successfully',
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Post  $post
     *
     * @param  Request  $request
     *
     * @return Factory|View
     */
    public function edit(Brand $brand, Request $request)
    {
        $data['brand'] = $brand;

        return view('admin.brands.form', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  Post  $post
     *
     * @return RedirectResponse
     */
    public function update(Request $request, Brand $brand)
    {
        $defaultLocale = localization()->getDefaultLocale();
        $validationRules = [
            "{$defaultLocale}.title" => 'required',
        ];
        $request->validate($validationRules);


        DB::beginTransaction();

        // Filling translations
        foreach (localization()->getSupportedLocales() as $key => $value) {
            if ($request->input($key.'.title')) {
                $brand->translateOrNew($key)->title = $request->input($key.'.title');
            }
        }

        $brand->save();


        $this->handleSubmittedSingleMedia('cover', $request, $brand);

        DB::commit();

        return redirect()
            ->route('admin.brands.index')
            ->with('message', [
                'type' => 'Success',
                'text' => 'Edited successfully',
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Post  $post
     *
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(Brand $brand)
    {
        /*todo: handle deleting the media files*/
        $brand->delete();

        return back()->with('message', [
            'type' => 'Success',
            'text' => 'Successfully Deleted',
        ]);
    }

}
