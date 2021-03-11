<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\CountryResource;
use App\Http\Resources\RegionResource;
use App\Models\Country;
use App\Models\Region;
use App\Models\RegionTranslation;
use Illuminate\Http\Request;

class CountryRegionController extends BaseApiController
{
    /**
     * Display a listing of the resource.
     *
     * @param  Country  $country
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index($country)
    {
        return RegionResource::collection(Region::whereCountryId($country)->get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  Country  $country
     *
     * @return \Illuminate\Http\Response
     */
    public function create($country)
    {
        return response([
            'countries' => CountryResource::collection(Country::getAll()),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Country  $country
     * @param  \Illuminate\Http\Request  $request
     *
     * @return RegionResource
     */
    public function store($country, Request $request)
    {

        \DB::beginTransaction();
        $defaultLocale = localization()->getDefaultLocale();

//        $request->validate([
////            "{$defaultLocale}.title" => 'required',
////            "{$defaultLocale}.content" => 'required'
////        ]);

        $region = new Region();
//        $region->creator_id = $region->editor_id = auth()->check() ? auth()->id() : User::first()->id;
        $region->country_id = $request->country_id;
        $region->english_name = $request->english_name;
        $region->code = $request->code;
        $region->save();

        // Filling translations
        foreach ($request->translations as $locale => $translation) {
            if ( ! is_null($translation['name'])) {
                $regionTranslation = new RegionTranslation();
                $regionTranslation->region_id = $region->id;
                $regionTranslation->locale = $locale;
                $regionTranslation->name = $translation['name'];
                $regionTranslation->slug = isset($translation['slug']) ? $translation['slug'] : null;
                $regionTranslation->save();
            }
        }
        $region->save();

        \DB::commit();

        return $this->respond([
            'success' => true,
            'message' => 'Successfully Stored',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  Country  $country
     *
     * @param  Region  $region
     *
     * @return RegionResource
     */
    public function show($country, $region)
    {
        return new RegionResource($region);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Country  $country
     *
     * @param  Region  $region
     *
     * @return void
     */
    public function edit($country, $region)
    {
        if (is_null(Region::find($region))) {
            return $this->respondNotFound([
                'message' => 'Region not found',
            ]);
        }

        return response([
            'countries' => CountryResource::collection(Country::getAll()),
            'region' => new RegionResource(Region::find($region))
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Country  $country
     *
     * @param  Region  $region
     *
     * @return void
     */
    public function update(Request $request, $country, $region)
    {
        \DB::beginTransaction();
        $defaultLocale = localization()->getDefaultLocale();

//        $request->validate([
////            "{$defaultLocale}.title" => 'required',
////            "{$defaultLocale}.content" => 'required'
////        ]);

        $region = Region::find($region);
//        $region->creator_id = $region->editor_id = auth()->check() ? auth()->id() : User::first()->id;
        $region->country_id = $request->country_id;
        $region->english_name = $request->english_name;
        $region->code = $request->code;
        $region->save();

        // Filling translations
        foreach ($request->translations as $locale => $translation) {
            if ( ! is_null($translation['name'])) {
                if (is_null($regionTranslation = RegionTranslation::where('locale', $locale)->where('region_id',
                    $region->id)->first())) {
                    $regionTranslation = new RegionTranslation();
                    $regionTranslation->region_id = $region->id;
                    $regionTranslation->locale = $locale;
                }
                $regionTranslation->region_id = $region->id;
                $regionTranslation->locale = $locale;
                $regionTranslation->name = $translation['name'];
                $regionTranslation->slug = isset($translation['slug']) ? $translation['slug'] : null;
                $regionTranslation->save();
            }
        }
        $region->save();

        \DB::commit();

        return $this->respond([
            'success' => true,
            'message' => 'Successfully Updated',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Country  $country
     *
     * @param  Region  $region
     *
     * @return void
     */
    public function destroy($country, $region)
    {
        $region = Region::find($region);

        if (is_null(Region::find($region))) {
            return $this->respondNotFound("This Item does not exist");
        }

        if ($region->delete()) {
            return $this->respond([
                'success' => true,
                'message' => 'Successfully Deleted',
            ]);
        }

        return $this->respond([
            'errors' => 'Unknown',
            'message' => 'Deletion failed',
        ]);
    }
}
