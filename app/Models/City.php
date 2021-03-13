<?php

namespace App\Models;

use App\Traits\HasMediaTrait;
use App\Traits\HasStatuses;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class City extends Model implements HasMedia
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

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function timezone()
    {
        return $this->belongsTo(Timezone::class);
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
     * @return City
     */
    public static function create(string $name, int $countryId, int $regionId): City
    {
        $city = new self;
        $city->country_id = $countryId;
        $city->region_id = $regionId;
        $city->english_name = $name;
        $city->save();
        $city->translateOrNew('ar')->name = $name;
        $city->save();

        return $city;
    }

    public static function getAllOfRegion($regionId = null)
    {
        if (is_null($regionId)) {
            $regionId = config('defaults.region.id');
        }

        return cache()->tags('cities')
                      ->rememberForever('cities_of_region_'.$regionId,
                          function () use ($regionId) {
                              return City::whereRegionId($regionId)->get();
                          }
                      );
    }
}
