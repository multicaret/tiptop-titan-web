<?php

namespace App\Http\Resources;

use App\Models\Timezone;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Timezone */
class TimezoneResource extends JsonResource
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
            'name' => $this->name,
            'utcOffset' => $this->utc_offset,
            'dstOffset' => $this->dst_offset,
        ];
    }
}
