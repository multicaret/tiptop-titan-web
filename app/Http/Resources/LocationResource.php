<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Location */
class LocationResource extends JsonResource
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
        /*switch ($this->contactable_type) {
            case User::class:
                $type = 'user';
                $contactableResource = UserResource::class;
                break;
            case Publisher::class:
                $type = 'publisher';
                $contactableResource = PublisherResource::class;
                break;
            case Vendor::class:
                $type = 'vendor';
                $contactableResource = VendorResource::class;
                break;
            default:
                $type = $contactableResource = null;
        }*/

        $kind = $this->getKind();
        $kind['id'] = $this->kind;

        return [
            'id' => (int) $this->id,
            'country' => new CountryResource($this->country),
            'region' => new RegionResource($this->region),
            'city' => new CityResource($this->city),
            'alias' => $this->alias,
            'name' => $this->name,
            'address1' => $this->address1,
//            'address2' => $this->address2,
//            'building' => $this->building,
//            'floor' => $this->floor,
//            'apartment' => $this->apartment,
//            'postcode' => $this->postcode,
            'latitude' => (double) $this->latitude,
            'longitude' => (double) $this->longitude,
            'notes' => [
                'raw' => strip_tags($this->notes),
                'formatted' => $this->notes,
            ],
            'kind' => $kind,
//            'phones' => $this->phones,
//            'mobiles' => $this->mobiles,
//            'emails' => $this->emails,
//            'socialMedia' => $this->social_media,
//            'website' => $this->website,
//            'position' => $this->position,
//            'company' => $this->company,
//            'vat' => [
//                'number' => $this->vat,
//                'office' => $this->vat_office
//            ],
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
