<?php

namespace App\Http\Resources;

use App\Models\Currency;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Order */
class OrderIndexResource extends JsonResource
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
//            TODO: uncommitted it when you work on addresses
//            'address' => [
//                'icon' => $this->address->icon,
//                'alias' => $this->address->name,
//            ],
            'completedAt' => [
                'formatted' => $this->completed_at->format(config('defaults.date.short_format')),
                'diffForHumans' => $this->completed_at->diffForHumans(),
                'timestamp' => $this->completed_at->timestamp,
            ],
            'grandTotal' => [
                'raw' => $this->grand_total,
                'formatted' => Currency::format($this->grand_total),
            ],
        ];
    }
}
