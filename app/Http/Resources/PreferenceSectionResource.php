<?php

namespace App\Http\Resources;

use App\Models\Preference;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Preference */
class PreferenceSectionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     *
     * @param                          $children
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->key,
            'notes' => $this->notes ? $this->notes : null,
            'icon' => $this->icon,
            'children' => PreferenceResource::collection($this->children),
        ];
    }
}
