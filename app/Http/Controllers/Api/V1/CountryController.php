<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\CountryResource;
use App\Http\Resources\CountryWithFlagResource;
use App\Http\Resources\CurrencyResource;
use App\Http\Resources\LanguageResource;
use App\Http\Resources\TimezoneResource;
use App\Models\Country;
use App\Models\CountryTranslation;
use App\Models\Currency;
use App\Models\Language;
use App\Models\Timezone;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CountryController extends BaseApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return CountryResource::collection(Country::getAll());
    }

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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return response([
            'currencies' => CurrencyResource::collection(Currency::all()),
            'languages' => LanguageResource::collection(Language::all()),
            'timezones' => TimezoneResource::collection(Timezone::all()),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        \DB::beginTransaction();
        $defaultLocale = localization()->getDefaultLocale();

//        $request->validate([
////            "{$defaultLocale}.title" => 'required',
////            "{$defaultLocale}.content" => 'required'
////        ]);

        $country = new Country();
//        $country->creator_id = $country->editor_id = auth()->check() ? auth()->id() : User::first()->id;
        $country->currency_id = $request->currency_id;
        $country->language_id = $request->language_id;
        $country->timezone_id = $request->timezone_id;
        $country->english_name = $request->english_name;
        $country->alpha2_code = $request->alpha2_code;
        $country->alpha3_code = $request->alpha3_code;
        $country->numeric_code = $request->numeric_code;
        $country->phone_code = $request->phone_code;
        $country->status = $request->status;
        $country->save();

        // Filling translations
        foreach ($request->translations as $locale => $translation) {
            if ( ! is_null($translation['name'])) {
                $countryTranslation = new CountryTranslation();
                $countryTranslation->country_id = $country->id;
                $countryTranslation->locale = $locale;
                $countryTranslation->name = $translation['name'];
                $countryTranslation->slug = isset($translation['slug']) ? $translation['slug'] : null;
                $countryTranslation->save();
            }
        }
        $country->save();
        \DB::commit();

        cache()->tags('countries')->flush();

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
     * @return CountryResource
     */
    public function show(Country $country)
    {
        return new CountryResource($country);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Country  $country
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($country)
    {
        if (is_null(Country::find($country))) {
            return $this->respondNotFound([
                'message' => 'Country not found',
            ]);
        }

        return response([
            'country' => new CountryResource(Country::find($country)),
            'currencies' => CurrencyResource::collection(Currency::all()),
            'languages' => LanguageResource::collection(Language::all()),
            'timezones' => TimezoneResource::collection(Timezone::all())
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Country  $country
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $country)
    {
        \DB::beginTransaction();
        $defaultLocale = localization()->getDefaultLocale();

//        $request->validate([
////            "{$defaultLocale}.title" => 'required',
////            "{$defaultLocale}.content" => 'required'
////        ]);
        if (is_null(Country::find($country))) {
            return $this->respondNotFound("This Item does not exist");
        }

        $country = Country::find($country);
//        $country->creator_id = $country->editor_id = auth()->check() ? auth()->id() : User::first()->id;
        $country->currency_id = $request->currency_id;
        $country->language_id = $request->language_id;
        $country->timezone_id = $request->timezone_id;
        $country->english_name = $request->english_name;
        $country->alpha2_code = $request->alpha2_code;
        $country->alpha3_code = $request->alpha3_code;
        $country->numeric_code = $request->numeric_code;
        $country->phone_code = $request->phone_code;
        $country->status = $request->status;
        $country->save();

        // Filling translations
        foreach ($request->translations as $locale => $translation) {
            if ( ! is_null($translation['name'])) {
                if (is_null($countryTranslation = CountryTranslation::where('locale', $locale)->where('country_id',
                    $country->id)->first())) {
                    $countryTranslation = new CountryTranslation();
                    $countryTranslation->country_id = $country->id;
                    $countryTranslation->locale = $locale;
                }
                $countryTranslation->country_id = $country->id;
                $countryTranslation->locale = $locale;
                $countryTranslation->name = $translation['name'];
                $countryTranslation->slug = isset($translation['slug']) ? $translation['slug'] : null;
                $countryTranslation->save();
            }
        }
        $country->save();

        \DB::commit();

        cache()->tags('countries')->flush();

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
     * @return \Illuminate\Http\Response
     */
    public function destroy($country)
    {
        $country = Country::find($country);

        if (is_null(Country::find($country))) {
            return $this->respondNotFound("This Item does not exist");
        }

        if ($country->delete()) {
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
