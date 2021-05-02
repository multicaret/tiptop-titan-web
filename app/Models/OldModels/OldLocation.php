<?php

namespace App\Models\OldModels;


use App\Models\City;
use App\Models\Country;
use App\Models\Location;
use App\Models\Region;
use App\Models\User;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

/**
 * App\Models\OldModels\OldLocation
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $name
 * @property string|null $country
 * @property string|null $city
 * @property string|null $municipality
 * @property string|null $neighborhood
 * @property string|null $street
 * @property string|null $building
 * @property string|null $floor
 * @property string|null $apartment
 * @property string|null $address
 * @property string|null $address_description
 * @property string|null $postal_code
 * @property string|null $latitude
 * @property string|null $longitude
 * @property int|null $default
 * @property string|null $phone_number
 * @property string|null $email
 * @property string|null $title
 * @property string $type
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read mixed $city_id
 * @property-read string $contactable_type
 * @property-read mixed $country_id
 * @property-read string|null $kind
 * @property-read mixed $phones
 * @property-read mixed $region_id
 * @method static Builder|OldLocation newModelQuery()
 * @method static Builder|OldLocation newQuery()
 * @method static Builder|OldLocation query()
 * @method static Builder|OldLocation whereAddress($value)
 * @method static Builder|OldLocation whereAddressDescription($value)
 * @method static Builder|OldLocation whereApartment($value)
 * @method static Builder|OldLocation whereBuilding($value)
 * @method static Builder|OldLocation whereCity($value)
 * @method static Builder|OldLocation whereCountry($value)
 * @method static Builder|OldLocation whereCreatedAt($value)
 * @method static Builder|OldLocation whereDefault($value)
 * @method static Builder|OldLocation whereDeletedAt($value)
 * @method static Builder|OldLocation whereEmail($value)
 * @method static Builder|OldLocation whereFloor($value)
 * @method static Builder|OldLocation whereId($value)
 * @method static Builder|OldLocation whereLatitude($value)
 * @method static Builder|OldLocation whereLongitude($value)
 * @method static Builder|OldLocation whereMunicipality($value)
 * @method static Builder|OldLocation whereName($value)
 * @method static Builder|OldLocation whereNeighborhood($value)
 * @method static Builder|OldLocation wherePhoneNumber($value)
 * @method static Builder|OldLocation wherePostalCode($value)
 * @method static Builder|OldLocation whereStreet($value)
 * @method static Builder|OldLocation whereTitle($value)
 * @method static Builder|OldLocation whereType($value)
 * @method static Builder|OldLocation whereUpdatedAt($value)
 * @method static Builder|OldLocation whereUserId($value)
 * @mixin Eloquent
 * @noinspection PhpFullyQualifiedNameUsageInspection
 * @noinspection PhpUnnecessaryFullyQualifiedNameInspection
 */
class OldLocation extends OldModel
{
    protected $table = 'addresses';
    protected $primaryKey = 'id';


    public const IS_DEFAULT = 1;

    public const STATUS_ACTIVE = 'ACTIVE';
    public const STATUS_DISABLED = 'DISABLED';
    public const STATUS_SUSPENDED = 'SUSPENDED';

    public function attributesComparing(): array
    {
        $attributesKeys = [
            'id' => 'id',
            'contactable_type' => 'contactable_type',
            'user_id' => 'contactable_id',
            'country_id' => 'country_id',
            'region_id' => 'region_id',
            'city_id' => 'city_id',
            'building' => 'building',
            'floor' => 'floor',
            'apartment' => 'apartment',
            'address' => 'address1',
            'address_description' => 'address2',
            'postal_code' => 'postcode',
            'name' => 'name',
            'title' => 'alias',
            'kind' => 'kind',
            'deleted_at' => 'deleted_at',
            'created_at' => 'created_at',
            'updated_at' => 'updated_at',
        ];

        if (self::validateLatLong($this->latitude, $this->longitude)) {
            $attributesKeys = array_merge($attributesKeys, [
                'latitude' => 'latitude',
                'longitude' => 'longitude'
            ]);
        }

        return $attributesKeys;
    }

    public function typeKindComparing(): array
    {
        return [
            'مبيعات' => Location::KIND_WORK,
            'بيت' => Location::KIND_HOME,
            'WORK' => Location::KIND_WORK,
            'HOME' => Location::KIND_HOME,
            'Home Address' => Location::KIND_HOME,
            'UNIVERSITY' => Location::KIND_WORK,
            'OTHER' => Location::KIND_OTHER,
            'DELIVERY' => Location::KIND_OTHER,
            'المنزل' => Location::KIND_HOME,
            'منزل' => Location::KIND_HOME,
            'ي' => Location::KIND_OTHER,
            '' => Location::KIND_OTHER,
            ' ' => Location::KIND_OTHER,
            null => Location::KIND_OTHER,
        ];
    }

    public function getPhonesAttribute()
    {
        $countyCode = '';
        $phoneNumber = $this->phone_number;
        $countyCodeAttempts = ['+90', '+964', '+963', '+null'];
        foreach ($countyCodeAttempts as $tempCountryCode) {
            if ((strpos($phoneNumber, $tempCountryCode) !== false)) {
                $countyCode = $tempCountryCode;
                break;
            }
        }

        if (empty($countyCode)) {
            return null;
        }
        $phoneNumber = str_replace($countyCode, '', $phoneNumber);
        $phoneNumber = str_replace(['+', ',', ' ', '(', ')'], '', $phoneNumber);
        if (strlen($phoneNumber) < 4) {
            return null;
        }
        if ( ! ctype_digit($phoneNumber)) {
            return null;
        }
        if (count(array_unique(str_split($phoneNumber))) === 1) {
            return null;
        }

        return $countyCode.$phoneNumber;
    }

    public function getCountryIdAttribute()
    {
        if (is_null($this->country) || empty($this->country)) {
            return null;
        }
        $country = Country::whereTranslationLike('name', 'like', "%$this->country%")->first();
        if (is_null($country)) {
            return null;
        }

        return $country->id;
    }

    public function getRegionIdAttribute()
    {
        if (is_null($this->city) || empty($this->city)) {
            return null;
        }
        $region = Region::whereTranslationLike('name', "%$this->city%")->first();
        if (is_null($region)) {
            return null;
        }

        return $region->id;
    }

    public function getCityIdAttribute()
    {
        if (is_null($this->municipality) || empty($this->municipality)) {
            return null;
        }

        $city = City::whereTranslationLike('name', "%$this->municipality%")->first();
        if (is_null($city)) {
            return null;
        }

        return $city->id;
    }

    public function getContactableTypeAttribute(): string
    {
        return User::class;
    }

    public function getKindAttribute(): ?string
    {
        return $this->typeKindComparing()[$this->type] ?? Location::KIND_OTHER;
    }
}
