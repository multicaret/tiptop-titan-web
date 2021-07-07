<?php

namespace App\Models;

use App\Contracts\ShouldHaveTypes;
use App\Traits\HasMediaTrait;
use App\Traits\HasStatuses;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;

class Brand extends Model implements HasMedia
{
    use HasFactory;
    use HasMediaTrait;
    use SoftDeletes;
    use Translatable;
    use HasStatuses;


    public const STATUS_DRAFT = 1;
    public const STATUS_ACTIVE = 2;
    public const STATUS_INACTIVE = 3;

    protected $guarded = [];

    protected $translatedAttributes = ['title'];

    public function getCoverAttribute()
    {
        return $this->getFirstMediaUrl('cover', 'HD');
    }
    public function registerMediaCollections(): void
    {
        $fallBackImageUrl = config('defaults.images.post_cover');
        $this->addMediaCollection('cover')
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
