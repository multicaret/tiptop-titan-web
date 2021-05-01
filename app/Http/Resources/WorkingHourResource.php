<?php

namespace App\Http\Resources;

use App\Models\WorkingHour;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin WorkingHour */
class WorkingHourResource extends JsonResource
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
            'id' => $this->id,
            'day' => trans('strings.working_day_'.$this->day),
            'opensAt' => Carbon::parse($this->opens_at)->format('H:i'),
            'closesAt' => Carbon::parse($this->closes_at)->format('H:i'),
        ];
    }
}
