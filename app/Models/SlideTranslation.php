<?php

namespace App\Models;

use App\Traits\HasMediaTrait;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;

class SlideTranslation extends Model implements HasMedia
{
    use HasMediaTrait;

    public $timestamps = false;

    protected $fillable = ['title', 'description', 'alt_tag'];

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
             ->registerMediaConversions(function ($media) {
                 foreach (config('defaults.image_conversions.generic_cover') as $conversionName => $dimensions) {
                     $this->addMediaConversion($conversionName)
                          ->width($dimensions['width'])
                          ->height($dimensions['height']);
                 }
             });
    }
}
