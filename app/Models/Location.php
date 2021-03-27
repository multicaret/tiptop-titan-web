<?php

namespace App\Models;

use App\Scopes\ActiveScope;
use App\Traits\HasStatuses;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Location extends Model
{
    use SoftDeletes,
        HasStatuses;

    const STATUS_INCOMPLETE = 0;
    const STATUS_DRAFT = 1;
    const STATUS_PUBLISHED = 2;
    const STATUS_INACTIVE = 3;

    const TYPE_ADDRESS = 1;
    const TYPE_CONTACT = 2;

    const KIND_HOME = 1;
    const KIND_WORK = 2;
    const KIND_OTHER = 3;

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

    protected $with = [
        'country',
        'region',
        'city'
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new ActiveScope);
    }

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
            $kindsForMaps[] = [
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

    public function getKind()
    {
        return self::getKinds()[$this->kind];
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
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function editor()
    {
        return $this->belongsTo(User::class, 'editor_id');
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
