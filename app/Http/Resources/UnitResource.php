<?php

namespace App\Http\Resources;

use App\Models\Taxonomy;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Taxonomy */
class UnitResource extends JsonResource
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
            'title' => $this->title,
            'step' => $this->step,
        ];
    }
}
