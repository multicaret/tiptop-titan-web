<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Region */
class RegionResource extends JsonResource
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
            'id' => (int) $this->id,
            'name' => [
                'original' => $this->english_name,
                'translated' => $this->name,
            ],
            'code' => $this->code,
        ];
    }
}
