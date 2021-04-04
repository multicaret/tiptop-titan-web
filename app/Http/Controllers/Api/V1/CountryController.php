<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\CountryWithFlagResource;
use App\Models\Country;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Storage;

class CountryController extends BaseApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(Request $request)
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
                if ( ! Storage::disk('public')->exists('storage/flags/'.$filename)) {
                    try {
                        Storage::disk('public')->put('storage/flags/'.$filename, file_get_contents($countryFlagUrl));
                    } catch (Exception $e) {
                        dd($country->id, $e->getMessage(), $countryFlagUrl);
                    }
                }
                $flagPath = asset('storage/flags/'.$filename);
                $country->flag = $flagPath;
            }
        }


        return $this->respond(CountryWithFlagResource::collection($countries));
    }
}
