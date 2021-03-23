<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Preference */
class PreferenceSectionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @param                          $children
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => (int) $this->id,
            'title' => $this->key,
            'notes' => $this->notes ? $this->notes : null,
            'icon' => $this->icon,
            'children' => PreferenceResource::collection($this->children),
        ];
    }
}
