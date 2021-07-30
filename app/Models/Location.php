<?php

namespace App\Models;

use App\Traits\HasStatuses;
use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * App\Models\Location
 *
 * @property int $id
 * @property int $creator_id
 * @property int|null $editor_id
 * @property string|null $contactable_type
 * @property int|null $contactable_id
 * @property int|null $country_id
 * @property int|null $region_id
 * @property int|null $city_id
 * @property string|null $alias
 * @property string|null $name
 * @property string|null $address1
 * @property string|null $address2
 * @property string|null $building
 * @property string|null $floor
 * @property string|null $apartment
 * @property string|null $postcode
 * @property float|null $latitude
 * @property float|null $longitude
 * @property string|null $notes
 * @property object|null $phones
 * @property mixed|null $mobiles
 * @property object|null $emails
 * @property mixed|null $social_media
 * @property string|null $website
 * @property string|null $position
 * @property string|null $company
 * @property string|null $vat value added tax
 * @property string|null $vat_office
 * @property bool $is_default
 * @property int $type 1: Address, 2: Contact
 * @property int $kind 1: Home, 2: Work, 3:Other
 * @property int $status 1:draft, 2:active, 3:Inactive, 4..n:CUSTOM
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read \App\Models\City|null $city
 * @property-read Model|\Eloquent $contactable
 * @property-read \App\Models\Country|null $country
 * @property-read \App\Models\User $creator
 * @property-read \App\Models\User|null $editor
 * @property-read mixed $actual_kind
 * @property-read bool $is_active
 * @property-read bool $is_inactive
 * @property-read mixed $kind_name
 * @property-read mixed $status_class
 * @property-read mixed $status_name
 * @property-read \App\Models\Region|null $region
 * @method static \Illuminate\Database\Eloquent\Builder|Location active()
 * @method static \Illuminate\Database\Eloquent\Builder|Location addresses()
 * @method static \Illuminate\Database\Eloquent\Builder|Location contacts()
 * @method static \Illuminate\Database\Eloquent\Builder|Location draft()
 * @method static \Illuminate\Database\Eloquent\Builder|Location inactive()
 * @method static \Illuminate\Database\Eloquent\Builder|Location newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Location newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Location notActive()
 * @method static \Illuminate\Database\Eloquent\Builder|Location notDraft()
 * @method static \Illuminate\Database\Query\Builder|Location onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Location query()
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereAddress1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereAddress2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereAlias($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereApartment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereBuilding($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereCompany($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereContactableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereContactableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereEditorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereEmails($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereFloor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereIsDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereKind($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereMobiles($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location wherePhones($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location wherePostcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereRegionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereSocialMedia($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereVat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereVatOffice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereWebsite($value)
 * @method static \Illuminate\Database\Query\Builder|Location withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Location withoutTrashed()
 * @mixin Eloquent
 * @noinspection PhpFullyQualifiedNameUsageInspection
 * @noinspection PhpUnnecessaryFullyQualifiedNameInspection
 */
class Location extends Model
{
    use HasStatuses;
    use SoftDeletes;

    public const STATUS_DRAFT = 1;
    public const STATUS_ACTIVE = 2;
    public const STATUS_INACTIVE = 3;

    public const TYPE_ADDRESS = 1;
    public const TYPE_CONTACT = 2;

    public const KIND_HOME = 1;
    public const KIND_WORK = 2;
    public const KIND_OTHER = 3;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'latitude' => 'double',
        'longitude' => 'double',
        'phones' => 'object',
        'emails' => 'object',
        'is_default' => 'boolean',
    ];

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeAddresses($query)
    {
        return $query->where('type', '=', self::TYPE_ADDRESS);
    }

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeContacts($query)
    {
        return $query->where('type', '=', self::TYPE_CONTACT);
    }

    public function getType()
    {
        switch ($this->type) {
            case self::TYPE_CONTACT:
                return 'Contact';
            default:
                return 'Address';
        }
    }

    public static function getKinds(): array
    {
        return [
            self::KIND_HOME => [
                'title' => trans('api.address_kind_Home'),
                'icon' => asset(config('defaults.images.address_home_icon')),
            ],
            self::KIND_WORK => [
                'title' => trans('api.address_kind_Work'),
                'icon' => asset(config('defaults.images.address_work_icon')),
            ],
            self::KIND_OTHER => [
                'title' => trans('api.address_kind_Other'),
                'icon' => asset(config('defaults.images.address_other_icon')),
            ],
        ];
    }

    public static function getKindsForMaps(): array
    {
        $kindsForMaps = [];
        foreach (self::getKinds() as $id => $kind) {
            $kindsForMaps[$id] = [
                'id' => $id,
                'title' => $kind['title'],
                'icon' => asset($kind['icon']),
                'markerIcon' => asset(Str::replaceLast('-icon', '-marker-icon', $kind['icon'])),
            ];
        }

        return $kindsForMaps;
    }

    public static function getKindForMarker($id)
    {
        $kindsForMaps = collect(Location::getKindsForMaps());

        return $kindsForMaps->filter(function ($kind) use ($id) {
            return $kind['id'] == $id;
        })->first();
    }

    public function getActualKindAttribute()
    {
        return self::getKindsForMaps()[$this->kind];
    }

    public function getKindNameAttribute()
    {
        return self::getActualKindAttribute()['title'];
    }

    /**
     * Get all of the owning contactable models.
     */
    public function contactable()
    {
        return $this->morphTo();
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id')->withTrashed();
    }

    public function editor()
    {
        return $this->belongsTo(User::class, 'editor_id')->withTrashed();
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }


    public function formatted()
    {
        if (
            $this->building ||
            $this->apartment ||
            $this->floor
        ) {
            $format = '%s %s/%s %s - %s %s %s %s';
        } else {
            $format = '%s %s %s %s %s %s %s %s';
        }

        return sprintf($format,
            $this->Contact1,
            $this->building,
            $this->apartment,
            $this->floor,
            $this->region ? $this->region->name : null,
            $this->city ? $this->city->name : null,
            $this->country ? $this->country->name : null
        );
    }
}
