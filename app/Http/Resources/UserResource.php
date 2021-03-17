<?php

namespace App\Http\Resources;

use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\User */
class UserResource extends JsonResource
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
            'name' => $this->name,
            'first' => $this->first,
            'last' => $this->last,
            'username' => $this->username,
            'email' => $this->email,
            'phone' => $this->phone_number,
            'phoneCode' => $this->phone_country_code,
            'phoneInternational' => $this->international_phone,
            'bio' => [
                'raw' => strip_tags($this->bio),
                'formatted' => $this->bio,
            ],
            'dob' => optional($this->dob)->format(config('defaults.date.short_format')),
            'gender' => $this->getGender($this->gender),
            'avatar' => $this->avatar,
            'cover' => $this->cover,
            'rating' => [
                'average' => $this->avg_rating,
                'countRaw' => $this->rating_count,
                'countFormatted' => Controller::numberToReadable($this->rating_count),
            ],
            'views' => [
                'raw' => $this->views,
                'formatted' => Controller::numberToReadable($this->views),
            ],
//            'address' => new LocationResource($this->locations()->first()),
            'status' => $this->status,
            'approvedAt' => [
                'formatted' => optional($this->approved_at)->format(config('defaults.date.short_format')),
                'diffForHumans' => optional($this->approved_at)->diffForHumans(),
                'timestamp' => optional($this->approved_at)->timestamp,
            ],
            'verifiedAt' => [
                'formatted' => optional($this->verified_at)->format(config('defaults.date.short_format')),
                'diffForHumans' => optional($this->verified_at)->diffForHumans(),
                'timestamp' => optional($this->verified_at)->timestamp,
            ],
            'suspendedAt' => [
                'formatted' => optional($this->suspended_at)->format(config('defaults.date.short_format')),
                'diffForHumans' => optional($this->suspended_at)->diffForHumans(),
                'timestamp' => optional($this->suspended_at)->timestamp,
            ],
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
            'mobileApp' => $this->mobile_app,
            'settings' => $this->settings,
            'currency' => new CurrencyResource($this->currency),
            'country' => new CountryResource($this->country),
            'region' => new RegionResource($this->region),
            'city' => new CityResource($this->city),
        ];
    }
}
