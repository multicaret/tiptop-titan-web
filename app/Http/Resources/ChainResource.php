<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Chain */
class ChainResource extends JsonResource
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => (int) $this->id,
            'regionId' => $this->region_id,
            'cityId' => $this->city_id,
            'currencyId' => $this->currency_id,
            'primaryPhoneNumber' => $this->primary_phone_number,
            'secondaryPhoneNumber' => $this->secondary_phone_number,
            'whatsappPhoneNumber' => $this->whatsapp_phone_number,
            'primaryColor' => $this->primary_color,
            'secondaryColor' => $this->secondary_color,
            'numberOfItemsOnMobileGridView' => $this->number_of_items_on_mobile_grid_view,
            'media' => [
                'logo' => $this->logo,
                'cover' => $this->cover,
                'gallery' => $this->gallery,
            ],
        ];
    }
}
