<?php

namespace App\Models;

use App\Traits\HasMediaTrait;
use App\Traits\HasStatuses;
use App\Traits\HasViewCount;
use Illuminate\Database\Eloquent\Model;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;

class Manufacturer extends Model implements HasMedia
{
    use HasMediaTrait,
        HasStatuses,
        HasViewCount;

    const STATUS_INCOMPLETE = 0;
    const STATUS_DRAFT = 1;
    const STATUS_PUBLISHED = 2;
    const STATUS_INACTIVE = 3;

    protected $appends = [
        'logo',
    ];

    /**
     * Get the logo attribute.
     *
     * @param  string  $logo
     *
     * @return bool
     */
    public function getLogoAttribute()
    {
        $logo = url(config('defaults.images.manufacturer_logo'));

        if ( ! is_null($media = $this->getFirstMedia('logo'))) {
            $logo = $media->getFullUrl('1K');
        }

        return $logo;
    }


    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')
             ->singleFile()
             ->registerMediaConversions(function (Media $media) {
                 $this->addMediaConversion('1K')
                      ->width(1024)
                      ->height(1024);
                 $this->addMediaConversion('512')
                      ->width(512)
                      ->height(512);
                 $this->addMediaConversion('256-cropped')
                      ->crop(Manipulations::CROP_CENTER, 256, 256);
             });
    }

}
