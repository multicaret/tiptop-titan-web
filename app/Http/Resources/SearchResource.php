<?php

namespace App\Http\Resources;

use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Search */
class SearchResource extends JsonResource
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'term' => $this->term,
            'count' => [
                'raw' => $this->count,
                'formatted' => Controller::numberToReadable($this->count),
            ],
            'locale' => $this->locale,
            'createdAt' => [
                'formatted' => $this->created_at->format(config('defaults.date.short_format')),
                'diffForHumans' => $this->created_at->diffForHumans(),
                'timestamp' => $this->created_at->timestamp,
            ],
            'updatedAt' => [
                'formatted' => $this->updated_at->format(config('defaults.date.short_format')),
                'diffForHumans' => $this->updated_at->diffForHumans(),
                'timestamp' => $this->updated_at->timestamp,
            ],
        ];
    }
}
