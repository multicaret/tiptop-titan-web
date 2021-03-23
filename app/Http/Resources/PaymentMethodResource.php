<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\PaymentMethod */
class PaymentMethodResource extends JsonResource
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'logo' => $this->logo,
            'title' => $this->title,
            'description' => [
                'raw' => strip_tags($this->description),
                'formatted' => $this->description,
            ],
            'instructions' => [
                'raw' => strip_tags($this->instructions),
                'formatted' => $this->instructions,
            ],
        ];
    }
}
