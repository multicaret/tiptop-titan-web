<?php

namespace App\Http\Controllers\Ajax;

use App\Models\Country;
use App\Models\Region;

class CountryRegionController extends AjaxController
{
    /**
     * Display a listing of the resource.
     *
     * @param  Country  $country
     *
     * @return array
     */
    public function index(Country $country)
    {
        return $country->regions->toArray();
    }

    /**
     * @param  Country  $country
     * @param  Region  $region
     *
     * @return Region
     */
    public function show(Country $country, Region $region)
    {
        return $region;
    }

}
