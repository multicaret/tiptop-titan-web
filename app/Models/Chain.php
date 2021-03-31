<?php

namespace App\Models;

use App\Traits\HasAppTypes;
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

/**
 * App\Models\Chain
 *
 * @property int $id
 * @property string $uuid
 * @property int $creator_id
 * @property int $editor_id
 * @property int|null $region_id
 * @property int|null $city_id
 * @property int|null $currency_id
 * @property int $type 1:Market, 2: Food
 * @property string|null $primary_phone_number
 * @property string|null $secondary_phone_number
 * @property string|null $whatsapp_phone_number
 * @property string $primary_color
 * @property string $secondary_color
 * @property int $number_of_items_on_mobile_grid_view
 * @property string $avg_rating
 * @property int $rating_count
 * @property int $view_count
 * @property int|null $order_column
 * @property int|null $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Branch[] $branches
 * @property-read int|null $branches_count
 * @property-read \App\Models\City|null $city
 * @property-read \App\Models\Currency|null $currency
 * @property-read mixed $cover
 * @property-read mixed $gallery
 * @property-read mixed $is_published
 * @property-read bool $logo
 * @property-read mixed $status_name
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection|Media[] $media
 * @property-read int|null $media_count
 * @property-read \App\Models\Region|null $region
 * @property-read \App\Models\ChainTranslation|null $translation
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ChainTranslation[] $translations
 * @property-read int|null $translations_count
 * @method static \Illuminate\Database\Eloquent\Builder|Chain draft()
 * @method static \Illuminate\Database\Eloquent\Builder|Chain foods()
 * @method static \Illuminate\Database\Eloquent\Builder|Chain groceries()
 * @method static \Illuminate\Database\Eloquent\Builder|Chain inactive()
 * @method static \Illuminate\Database\Eloquent\Builder|Chain incomplete()
 * @method static \Illuminate\Database\Eloquent\Builder|Chain listsTranslations(string $translationField)
 * @method static \Illuminate\Database\Eloquent\Builder|Chain newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Chain newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Chain notPublished()
 * @method static \Illuminate\Database\Eloquent\Builder|Chain notTranslatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Chain orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Chain orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Chain orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|Chain published()
 * @method static \Illuminate\Database\Eloquent\Builder|Chain query()
 * @method static \Illuminate\Database\Eloquent\Builder|Chain translated()
 * @method static \Illuminate\Database\Eloquent\Builder|Chain translatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Chain whereAvgRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chain whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chain whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chain whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chain whereCurrencyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chain whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chain whereEditorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chain whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chain whereNumberOfItemsOnMobileGridView($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chain whereOrderColumn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chain wherePrimaryColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chain wherePrimaryPhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chain whereRatingCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chain whereRegionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chain whereSecondaryColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chain whereSecondaryPhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chain whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chain whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static \Illuminate\Database\Eloquent\Builder|Chain whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Chain whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chain whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chain whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chain whereViewCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chain whereWhatsappPhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chain withTranslation()
 * @mixin \Eloquent
 */
class Chain extends Model implements HasMedia
{
    use HasMediaTrait,
        HasUuid,
        Translatable,
        HasStatuses,
        HasViewCount,
        HasTypes,
        HasAppTypes;

    const STATUS_INCOMPLETE = 0;
    const STATUS_DRAFT = 1;
    const STATUS_PUBLISHED = 2;
    const STATUS_INACTIVE = 3;

    const TYPE_GROCERY_OBJECT = 1;
    const TYPE_FOOD_OBJECT = 2;

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
        return $query->where('type', self::TYPE_GROCERY_OBJECT);
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
        return $query->where('type', self::TYPE_FOOD_OBJECT);
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
