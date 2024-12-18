<?php

namespace App\Http\Resources;

use App\Models\RemoteConfig;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin RemoteConfig */
class RemoteConfigResource extends JsonResource
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
            'buildNumber' => $this->build_number,
            'applicationType' => $this->application_type,
            'platformType' => $this->platform_type,
            'updateMethod' => $this->update_method,
            'data' => $this->data,
            'dataTranslated' => $this->data_translated,
        ];
    }
}
