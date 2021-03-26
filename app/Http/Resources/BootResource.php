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
            'buildNumber' => $this->build_number,
            'applicationType' => $this->application_type,
            'platformType' => $this->platform_type,
            'updateMethod' => $this->update_method,
            'data' => $this->data,
            'dataTranslated' => $this->data_translated,
        ];
    }
}
