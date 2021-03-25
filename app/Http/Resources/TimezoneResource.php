<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Timezone */
class TimezoneResource extends JsonResource
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
            'name' => $this->name,
            'utcOffset' => $this->utc_offset,
            'dstOffset' => $this->dst_offset,
        ];
    }
}
