<?php

namespace App\Models;

use App\Traits\HasMediaTrait;
use App\Traits\HasStatuses;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Region extends Model implements HasMedia
{
    use HasMediaTrait,
        Translatable,
        HasStatuses;

    const STATUS_INCOMPLETE = 0;
    const STATUS_DRAFT = 1;
    const STATUS_PUBLISHED = 2;
    const STATUS_INACTIVE = 3;

    protected $fillable = ['name', 'slug'];
    protected $with = ['translations'];
    protected $translatedAttributes = ['name', 'slug'];
    protected $appends = [
        'cover',
        'gallery',
    ];

    public function contacts()
    {
        return $this->hasMany(Location::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function cities()
    {
        return $this->hasMany(City::class);
    }


    public function getCoverAttribute()
    {
        $image = config('defaults.images.region_cover');

        if ( ! is_null($media = $this->getFirstMedia('cover'))) {
            $image = $media->getUrl();
        }
        if ( ! is_null($media = $this->getFirstMedia('gallery'))) {
            $image = $media->getUrl();
        }

        return url($image);
    }

    public function getGalleryAttribute()
    {
        return $this->getMediaForUploader('gallery');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('cover')
             ->singleFile();

        $this->addMediaCollection('gallery');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('medium')
             ->width(1280)
             ->height(873)
             ->performOnCollections('cover', 'gallery')
             ->nonQueued();

        $this->addMediaConversion('thumbnail')
             ->width(480)
             ->height(270)
             ->performOnCollections('cover', 'gallery')
             ->nonQueued();
    }

    /**
     * @param  string  $name
     *
     * @param  int  $countryId
     * @param  int  $regionId
     *
     * @return Region
     */
    public static function create(string $name, int $countryId): Region
    {
        $region = new self;
        $region->country_id = $countryId;
        $region->english_name = $name;
        $region->save();
        $region->translateOrNew('ar')->name = $name;
        $region->save();

        return $region;
    }

    public static function getAllOfCountry($countryId = null)
    {
        if (is_null($countryId)) {
            $countryId = config('defaults.country.id');
        }

        return cache()->tags('regions')
                      ->rememberForever('regions_of_country_'.$countryId,
                          function () use ($countryId) {
                              return Region::whereCountryId($countryId)->get();
                          }
                      );
    }

    public static function getAll()
    {
        return cache()->tags('regions')
                      ->rememberForever('regions',
                          function () {
                              return Region::all();
                          }
                      );
    }
}
