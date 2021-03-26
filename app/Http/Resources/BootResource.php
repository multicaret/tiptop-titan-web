<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Boot */
class BootResource extends JsonResource
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
            'buildNumber' => 1,
            'applicationType' => 1,
            'platformType' => 'ios',
            'updateMethod' => 2,
            'data' => $this->data,
            'dataTranslated' => $this->data_translated,
        ];
    }
}
