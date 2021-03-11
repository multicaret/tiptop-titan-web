<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Preference */
class PreferenceResource extends JsonResource
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
            'key' => $this->key,
            'title' => trans('strings.'.$this->key),
            'value' => $this->type == 'file' ? url($this->value) : $this->value,
            'notes' => $this->notes ? trans('preferences.'.$this->notes) : null,
            'type' => $this->type,
        ];
    }
}
