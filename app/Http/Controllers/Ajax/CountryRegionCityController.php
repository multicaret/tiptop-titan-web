<?php

namespace App\Http\Controllers\Ajax;

use App\Models\City;
use App\Models\Country;
use App\Models\Region;

class CountryRegionCityController extends AjaxController
{
    /**
     * Display a listing of the resource.
     *
     * @param  Country  $country
     *
     * @return array
     */
    public function index(Country $country, Region $region)
    {
        return $region->cities->toArray();
    }

    /**
     * @param  Country  $country
     * @param  Region  $region
     *
     * @param  City  $city
     *
     * @return City
     */
    public function show(Country $country, Region $region, City $city)
    {
        return $city;
    }

}
