<?php

namespace App\Models;

use App\Traits\HasMediaTrait;
use App\Traits\HasUuid;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Slide extends Model implements HasMedia
{
    use SoftDeletes,
        HasUuid,
        Translatable,
        HasMediaTrait;

    const TYPE_EXTERNAL = 1;
    const TYPE_UNIVERSAL = 2;
    const TYPE_DEFERRED_DEEPLINK = 3;
    const TYPE_DEEPLINK = 4;

    protected $with = ['translations'];

    protected $translatedAttributes = ['title', 'description'];

    protected $fillable = ['link_value', 'link_type'];

    protected $appends = [
        'image',
    ];

    public function getImageAttribute()
    {
        $image = config('defaults.images.slider_image');

        if ( ! is_null($media = $this->getFirstMedia('image'))) {
            $image = $media->getUrl('1K');
        }

        return url($image);
    }

    public function getImageFullAttribute()
    {
        return $this->getFirstMediaUrl('image', 'HD');
    }

    public function getThumbnailAttribute()
    {
        return $this->getFirstMediaUrl('image', 'SD');
    }


    public function registerMediaCollections(): void
    {
        $fallBackImageUrl = config('defaults.images.slider_image');
        $this->addMediaCollection('image')
             ->useFallbackUrl(url($fallBackImageUrl))
             ->singleFile()
             ->withResponsiveImages()
             ->registerMediaConversions(function (Media $media) {
                 foreach (config('defaults.image_conversions.generic_cover') as $conversionName => $dimensions) {
                     $this->addMediaConversion($conversionName)
                          ->width($dimensions['width'])
                          ->height($dimensions['height']);
                 }
             });
    }

}
