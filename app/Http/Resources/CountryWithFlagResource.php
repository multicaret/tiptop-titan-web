<?php

namespace App\Http\Resources;

use App\Models\Country;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Country */
class CountryWithFlagResource extends JsonResource
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
        return [
            'id' => $this->id,
            'nameEnglish' => $this->english_name,
            'name' => $this->name,
            'phoneCode' => $this->phone_code,
            'alpha2Code' => $this->alpha2_code,
            'alpha3Code' => $this->alpha3_code,
            'flagUrl' => is_null($this->flag) ? '' : $this->flag,
        ];
    }
}
