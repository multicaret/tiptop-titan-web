<?php

namespace App\Http\Resources;

use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin City */
class CityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     *
     * @return array
     */
    public function toArray($request)
    {

        return [
            'id' => $this->id,
            'nameEnglish' => $this->english_name,
            'name' => $this->name,
            'description' => $this->description,
            'code' => $this->code,
            'country' => new CountryResource($this->country),
            'region' => new RegionResource($this->region),
            'timezone' => new TimezoneResource($this->timezone),
            'population' => $this->population,
            'latitude' => (float) $this->latitude,
            'longitude' => (float) $this->longitude,
        ];
    }
}
