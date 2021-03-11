<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\City */
class CityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return array
     */
    public function toArray($request)
    {

        return [
            'id' => $this->id,
            'name' => [
                'original' => $this->english_name,
                'translated' => $this->name,
            ],
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
