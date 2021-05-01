<?php

namespace App\Http\Resources;

use App\Models\Country;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Country */
class CountryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     *
     * @return array
     * @throws Exception
     */
    public function toArray($request)
    {

        $data = cache()->tags('countries')->rememberForever('country_resource_'.$this->id, function () {
            $flag_url = null;
            $ch = curl_init();
            $url = 'https://restcountries.eu/data/'.strtolower($this->alpha3_code).'.svg';
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_NOBODY, 1);
            curl_setopt($ch, CURLOPT_FAILONERROR, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            $result = curl_exec($ch);
            curl_close($ch);
            if ($result !== false) {
                $flag_url = $url;
            }

            return [
                'id' => $this->id,
                'nameEnglish' => $this->english_name,
                'name' => $this->name,
                'phoneCode' => $this->phone_code,
                'alpha2Code' => $this->alpha2_code,
                'alpha3Code' => $this->alpha3_code,
                'flagUrl' => $flag_url,
            ];
        });

        return $data;
    }
}
