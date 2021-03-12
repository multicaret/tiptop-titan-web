<?php

namespace App\Models;

use App\Traits\HasMediaTrait;
use App\Traits\HasStatuses;
use App\Traits\HasViewCount;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;

class Barcode extends Model implements HasMedia
{
    use HasMediaTrait,
        HasStatuses,
        HasViewCount;

    const STATUS_INCOMPLETE = 0;
    const STATUS_DRAFT = 1;
    const STATUS_PUBLISHED = 2;
    const STATUS_INACTIVE = 3;

    protected $appends = [
        'image',
    ];

    /**
     * Get the image attribute.
     *
     * @param  string  $image
     *
     * @return bool
     */
    public function getImageAttribute()
    {
        $image = url(config('defaults.images.barcode_image'));

        if ( ! is_null($media = $this->getFirstMedia('image'))) {
            $image = $media->getFullUrl('thumbnail');
        }

        return $image;
    }


    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')
             ->singleFile()
             ->registerMediaConversions(function (Media $media) {
                 $this->addMediaConversion('medium')
                      ->width(1024)
                      ->height(512);
                 $this->addMediaConversion('thumbnail')
                      ->width(512)
                      ->height(256);
             });
    }

}
