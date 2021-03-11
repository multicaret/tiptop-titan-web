<?php

namespace App\Http\Controllers\Ajax;

use App\Models\Country;

class CountryController extends AjaxController
{
    /**
     * Display a listing of the resource.
     *
     *
     * @return array
     */
    public function index()
    {
        return Country::all();
    }

    /**
     * Display a listing of the resource.
     *
     *
     * @param  Country  $country
     *
     * @return Country
     */
    public function show(Country $country)
    {
        return $country;
    }

}
