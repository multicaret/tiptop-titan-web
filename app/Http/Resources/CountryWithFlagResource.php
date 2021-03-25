<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Country */
class CountryWithFlagResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return array
     * @throws \Exception
     */
    public function toArray($request)
    {
        return [
            'id' => (int) $this->id,
            'nameEnglish' => $this->english_name,
            'name' => $this->name,
            'phoneCode' => $this->phone_code,
            'alpha2Code' => $this->alpha2_code,
            'alpha3Code' => $this->alpha3_code,
            'flag' => is_null($this->flag) ? '' : $this->flag,
        ];
    }
}
