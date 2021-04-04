<?php

namespace App\Models;

use App\Traits\HasMediaTrait;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;

/**
 * App\Models\SlideTranslation
 *
 * @property int $id
 * @property int $slide_id
 * @property string $alt_tag
 * @property string $locale
 * @property-read mixed $image
 * @property-read mixed $image_full
 * @property-read mixed $thumbnail
 * @property-read MediaCollection|\Spatie\MediaLibrary\MediaCollections\Models\Media[] $media
 * @property-read int|null $media_count
 * @method static Builder|SlideTranslation newModelQuery()
 * @method static Builder|SlideTranslation newQuery()
 * @method static Builder|SlideTranslation query()
 * @method static Builder|SlideTranslation whereAltTag($value)
 * @method static Builder|SlideTranslation whereId($value)
 * @method static Builder|SlideTranslation whereLocale($value)
 * @method static Builder|SlideTranslation whereSlideId($value)
 * @mixin Eloquent
 */
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
