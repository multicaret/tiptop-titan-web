<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\CountryWithFlagResource;
use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends BaseApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function countreisWithFlags(Request $request)
    {

        if ($request->has('all')) {
            $countries = Country::getAll();
        } else {
            $countries = Country::whereIn('id', [Country::IRAQ_COUNTRY_ID, Country::TURKEY_COUNTRY_ID])->get();
        }
        foreach ($countries as $country) {
            if ( ! empty($country->alpha3_code)) {
                $countryFlagUrl = 'https://restcountries.eu/data/'.strtolower($country->alpha3_code).'.svg';
                $filename = basename($countryFlagUrl);
                if ( ! \Storage::disk('public')->exists('flags/'.$filename)) {
                    try {
                        \Storage::disk('public')->put('flags/'.$filename, file_get_contents($countryFlagUrl));
                    } catch (\Exception $e) {
                        dd($country->id, $e->getMessage(), $countryFlagUrl);
                    }
                }
                $flagPath = asset('flags/'.$filename);
                $country->flag = $flagPath;
            }
        }


        return $this->respond(CountryWithFlagResource::collection($countries));
    }
}
