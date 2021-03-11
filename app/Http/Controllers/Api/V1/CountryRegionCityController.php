<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\CityResource;
use App\Http\Resources\RegionResource;
use App\Models\City;
use App\Models\CityTranslation;
use App\Models\Country;
use App\Models\Region;
use Illuminate\Http\Request;

class CountryRegionCityController extends BaseApiController
{
    /**
     * Display a listing of the resource.
     *
     * @param  Country  $country
     *
     * @param  Region  $region
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index($country, $region)
    {
        return CityResource::collection(City::whereRegionId($region)->get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  Country  $country
     *
     * @param  Region  $region
     *
     * @return \Illuminate\Http\Response
     */
    public function create($country, $region)
    {
        return response([
            'regions' => RegionResource::collection(Region::getAll()),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Country  $country
     * @param  Region  $region
     * @param  \Illuminate\Http\Request  $request
     *
     * @return void
     */
    public function store($country, $region, Request $request)
    {
        \DB::beginTransaction();
        $defaultLocale = localization()->getDefaultLocale();

//        $request->validate([
////            "{$defaultLocale}.title" => 'required',
////            "{$defaultLocale}.content" => 'required'
////        ]);

        $city = new City();
//        $city->creator_id = $city->editor_id = auth()->check() ? auth()->id() : User::first()->id;
        $city->country_id = $request->country_id;
        $city->region_id = $request->region_id;
        $city->timezone_id = $request->timezone_id;
        $city->english_name = $request->english_name;
        $city->population = $request->population;
        $city->latitude = $request->latitude;
        $city->longitude = $request->longitude;
        $city->save();

        // Filling translations
        foreach ($request->translations as $locale => $translation) {
            if ( ! is_null($translation['name'])) {
                $cityTranslation = new CityTranslation();
                $cityTranslation->city_id = $city->id;
                $cityTranslation->locale = $locale;
                $cityTranslation->name = $translation['name'];
                $cityTranslation->description = isset($translation['description']) ? $translation['description'] : null;
                $cityTranslation->slug = isset($translation['slug']) ? $translation['slug'] : null;
                $cityTranslation->save();
            }
        }
        $city->save();

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
     * @param  City  $city
     *
     * @return CityResource
     */
    public function show(Country $country, Region $region, City $city)
    {
        return new CityResource($city);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Country  $country
     *
     * @param  Region  $region
     *
     * @param  City  $city
     *
     * @return void
     */
    public function edit($country, $region, $city)
    {
        if (is_null($city = City::find($city))) {
            return $this->respondNotFound([
                'message' => 'City not found',
            ]);
        }

        return response([
            'city' => new CityResource($city),
            'regions' => RegionResource::collection(Region::getAll()),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  country  $country
     *
     * @param  Region  $region
     *
     * @param  City  $city
     *
     * @return void
     */
    public function update(Request $request, $country, $region, $city)
    {

        $defaultLocale = localization()->getDefaultLocale();

//        $request->validate([
////            "{$defaultLocale}.title" => 'required',
////            "{$defaultLocale}.content" => 'required'
////        ]);
        \DB::beginTransaction();
        $city = City::find($city);
//        $city->creator_id = $city->editor_id = auth()->check() ? auth()->id() : User::first()->id;
        $city->country_id = $request->country_id;
        $city->region_id = $request->region_id;
        $city->timezone_id = $request->timezone_id;
        $city->english_name = $request->english_name;
        $city->latitude = $request->latitude;
        $city->longitude = $request->longitude;
        $city->save();

        // Filling translations
        foreach ($request->translations as $locale => $translation) {
            if ( ! is_null($translation['name'])) {
                if (is_null($cityTranslation = CityTranslation::where('locale', $locale)->where('city_id',
                    $city->id)->first())) {
                    $cityTranslation = new CityTranslation();
                    $cityTranslation->city_id = $city->id;
                    $cityTranslation->locale = $locale;
                }
                $cityTranslation->city_id = $city->id;
                $cityTranslation->locale = $locale;
                $cityTranslation->name = $translation['name'];
                $cityTranslation->description = isset($translation['description']) ? $translation['description'] : null;
                $cityTranslation->slug = isset($translation['slug']) ? $translation['slug'] : null;
                $cityTranslation->save();
            }
        }
        $city->save();

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
     * @param  City  $city
     *
     * @return void
     */
    public function destroy($country, $region, $city)
    {
        $city = City::find($city);

        if (is_null($city)) {
            return $this->respondNotFound("This Item does not exist");
        }

        if ($city->delete()) {
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
