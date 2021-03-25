<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\WorkingHour */
class WorkingHourResource extends JsonResource
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
            'day' => trans('strings.working_day_'.$this->day),
            'opensAt' => Carbon::parse($this->opens_at)->format('H:i'),
            'closesAt' => Carbon::parse($this->closes_at)->format('H:i'),
        ];
    }
}
