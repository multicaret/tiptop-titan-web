<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Region;
use App\Models\Slide;
use App\Models\SlideTranslation;
use Arr;
use DB;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Str;

class SlideController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:slide.permissions.index', ['only' => ['index', 'store']]);
        $this->middleware('permission:slide.permissions.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:slide.permissions.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:slide.permissions.destroy', ['only' => ['destroy']]);
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
                'width' => '1',
            ],
            [
                'data' => 'thumbnail',
                'title' => trans('strings.thumbnail'),
                'width' => '1',
            ],
            [
                'data' => 'title',
                'name' => 'title',
                'title' => trans('strings.title'),
                'width' => '40',
            ],
            /*[
                'data' => 'region',
                'name' => 'region',
                'title' => trans('strings.city'),
                'width' => '40',
            ],
            [
                'data' => 'city',
                'name' => 'city',
                'title' => trans('strings.neighborhood'),
                'width' => '40',
            ],*/
            [
                'data' => 'location',
                'name' => 'location',
                'title' => trans('strings.location'),
                'width' => '30',
            ],
            [
                'data' => 'channel',
                'name' => 'channel',
                'title' => 'Channel',
                'width' => '20',
            ],
            [
                'data' => 'has_been_authenticated',
                'name' => 'has_been_authenticated',
                'title' => trans('strings.placement'),
                'width' => '40',
            ],
            [
                'data' => 'begins_at',
                'name' => 'begins_at',
                'title' => trans('strings.start_date'),
                'width' => '10',
            ],
            [
                'data' => 'expires_at',
                'name' => 'expires_at',
                'title' => trans('strings.expire_date'),
                'width' => '10',
            ],
            [
                'data' => 'state',
                'name' => 'state',
                'title' => trans('strings.state'),
                'searchable' => false,
                'bSortable' => false,
                'width' => '10',
            ],
            /*[
                'data' => 'status',
                'name' => 'status',
                'title' => 'Status',
                'width' => '10',
            ],*/
            /*[
                'data' => 'created_at',
                'name' => 'created_at',
                'title' => trans('strings.create_date'),
                'width' => '10',
            ],*/
        ];

        return view('admin.slides.index', compact('columns'));
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
        $data = $this->essentialData($request);
        $slide = new Slide();
        $slide->begins_at = now()->subMinutes(5);
        $slide->expires_at = now()->addDays(30);
        $data['slide'] = $slide;

        return view('admin.slides.form', $data);
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

        $slide = new Slide();
        $this->saveLogic($request, $slide);

        return redirect()
            ->route('admin.slides.index')
            ->with('message', [
                'type' => 'Success',
                'text' => __('strings.successfully_created'),
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Slide  $slide
     *
     * @param  Request  $request
     *
     * @return Factory|View
     */
    public function edit(Slide $slide, Request $request)
    {
        $data = $this->essentialData($request);
        $slide->load(['region', 'city']);
        $data['slide'] = $slide;

        return view('admin.slides.form', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  Slide  $slide
     *
     * @return RedirectResponse
     */
    public function update(Request $request, Slide $slide)
    {
        $this->saveLogic($request, $slide, true);

        return redirect()
            ->route('admin.slides.index')
            ->with('message', [
                'type' => 'Success',
                'text' => 'Edited successfully',
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Slide  $slide
     *
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(Slide $slide)
    {
        $slide->delete();

        return back()->with('message', [
            'type' => 'Success',
            'text' => 'Successfully Deleted',
        ]);
    }

    private function essentialData(Request $request): array
    {
        $linkTypes = Slide::getTypesArray();
        $regions = Region::whereCountryId(config('defaults.country.id'))->active()->get();

        return compact('linkTypes', 'regions');
    }

    private function validationRules(): array
    {
        $defaultLocale = localization()->getDefaultLocale();

        return [
//            "{$defaultLocale}.title" => 'required',
//            "old_price" => 'required|numeric|min:1',
        ];
    }


    private function saveLogic($request, Slide $slide, bool $isUpdating = false)
    {
        $region = json_decode($request->region);
        $city = json_decode($request->city);

        DB::beginTransaction();
        if ( ! $isUpdating) {
            $slide->creator_id = auth()->id();
        }

        $slide->editor_id = auth()->id();
        $slide->title = $request->input('title');
        $slide->description = $request->input('description');
        $slide->link_type = $request->input('link_type');
        $slide->link_value = $request->input('link_value');
        $slide->linkage = $request->input('linkage');
        $slide->begins_at = $request->input('begins_at');
        $slide->expires_at = $request->input('expires_at');
        $slide->city_id = isset($city) ? $city->id : null;
        $slide->region_id = isset($region) ? $region->id : null;
        $slide->status = $request->input('status');
        $slide->has_been_authenticated = $request->input('has_been_authenticated');
        $slide->channel = $request->input('channel');
        $slide->save();
        // Filling translations

        foreach (localization()->getSupportedLocales() as $key => $value) {
            $slide->translateOrNew($key)->alt_tag = $request->input($key.'.alt_tag');
            $inputKey = $key.'.image';
            if ($request->has($inputKey)) {
                SlideTranslation::whereSlideId($slide->id)
                                                    ->where('locale', Str::beforeLast($key, '.'))
                                                    ->first();

//                $this->handleSubmittedSingleMedia($key.".image", $request, $slideTranslation); todo: fix this. It's the delete logic but it breaks storing
            }
        }
        $slide->save();

        $allFiles = Arr::dot($request->allFiles());
        foreach ($allFiles as $tempKey => $file) {
            $slideTranslation = SlideTranslation::whereSlideId($slide->id)
                                                ->where('locale', Str::beforeLast($tempKey, '.'))
                                                ->first();
            if ( ! is_null($slideTranslation->addMediaFromRequest($tempKey))) {
                $slideTranslation->addMediaFromRequest($tempKey)
                                 ->toMediaCollection('image');
            }

        }

        DB::commit();
    }

}
