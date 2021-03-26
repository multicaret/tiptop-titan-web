<?php

namespace App\Models;

use App\Traits\HasMediaTrait;
use App\Traits\HasStatuses;
use App\Traits\HasTypes;
use App\Traits\HasUuid;
use App\Traits\HasViewCount;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo as BelongsTo;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Chain extends Model implements HasMedia
{
    use HasMediaTrait,
        HasUuid,
        Translatable,
        HasStatuses,
        HasViewCount,
        HasTypes;

    const STATUS_INCOMPLETE = 0;
    const STATUS_DRAFT = 1;
    const STATUS_PUBLISHED = 2;
    const STATUS_INACTIVE = 3;

    const TYPE_GROCERY = 1;
    const TYPE_FOOD = 2;

    protected $fillable = ['title', 'description'];
    protected $with = ['translations'];
    protected $translatedAttributes = ['title', 'description'];
    protected $appends = [
        'logo',
        'cover',
        'gallery',
    ];


    public function branches(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Branch::class, 'chain_id');
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }


    /**
     * Scope a query to only include Grocery.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeGroceries($query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('type', self::TYPE_GROCERY);
    }

    /**
     * Scope a query to only include foods.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFoods($query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('type', self::TYPE_FOOD);
    }


    /**
     * @return array
     */
    public static function getTypesArray(): array
    {
        return [
            self::TYPE_GROCERY => 'grocery',
            self::TYPE_FOOD => 'food',
        ];
    }


    /**
     * Get the logo attribute.
     *
     * @param  string  $logo
     *
     * @return bool
     */
    public function getLogoAttribute()
    {
        $logo = url(config('defaults.images.chain_logo'));

        if ( ! is_null($media = $this->getFirstMedia('logo'))) {
            $logo = $media->getFullUrl('1K');
        }

        return $logo;
    }


    public function getCoverAttribute()
    {
        $image = config('defaults.images.chain_cover');

        if ( ! is_null($media = $this->getFirstMedia('cover'))) {
            $image = $media->getUrl();
        }
        if ($image == config('defaults.images.chain_cover') &&
            ! is_null($media = $this->getFirstMedia('gallery'))) {
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
        $this->addMediaCollection('logo')
             ->singleFile()
             ->withResponsiveImages()
             ->registerMediaConversions(function (Media $media) {
                 foreach (config('defaults.image_conversions.generic_logo') as $conversionName => $dimensions) {
                     $this->addMediaConversion($conversionName)
                          ->width($dimensions['width'])
                          ->height($dimensions['height']);
                 }
                 $this->addMediaConversion('256-cropped')
                      ->crop(Manipulations::CROP_CENTER, 256, 256);
             });

        $this->addMediaCollection('cover')
             ->singleFile()
             ->withResponsiveImages()
             ->registerMediaConversions(function (Media $media) {
                 foreach (config('defaults.image_conversions.generic_cover') as $conversionName => $dimensions) {
                     $this->addMediaConversion($conversionName)
                          ->width($dimensions['width'])
                          ->height($dimensions['height']);
                 }
             });


        $this->addMediaCollection('gallery')
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
