<?php

namespace App\Http\Resources;

use App\Models\Preference;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Preference */
class SupportResource extends JsonResource
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
            'id' => (int) $this->id,
            'key' => $this->key,
            'title' => trans('strings.'.$this->key),
            'value' => $this->type == 'file' ? url($this->value) : $this->value,
            'notes' => $this->notes ? trans('preferences.'.$this->notes) : null,
            'type' => $this->type,
        ];
    }
}
