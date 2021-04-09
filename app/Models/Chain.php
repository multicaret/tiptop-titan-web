<?php

namespace App\Models;

use App\Traits\HasAppTypes;
use App\Traits\HasMediaTrait;
use App\Traits\HasStatuses;
use App\Traits\HasTypes;
use App\Traits\HasUuid;
use App\Traits\HasViewCount;
use Astrotomic\Translatable\Translatable;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo as BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
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
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read Collection|\App\Models\Branch[] $branches
 * @property-read int|null $branches_count
 * @property-read \App\Models\City|null $city
 * @property-read \App\Models\Currency|null $currency
 * @property-read mixed $cover
 * @property-read mixed $gallery
 * @property-read bool $is_active
 * @property-read bool $is_inactive
 * @property-read bool $logo
 * @property-read mixed $status_name
 * @property-read MediaCollection|Media[] $media
 * @property-read int|null $media_count
 * @property-read \App\Models\Region|null $region
 * @property-read \App\Models\ChainTranslation|null $translation
 * @property-read Collection|\App\Models\ChainTranslation[] $translations
 * @property-read int|null $translations_count
 * @method static Builder|Chain active()
 * @method static Builder|Chain draft()
 * @method static Builder|Chain foods()
 * @method static Builder|Chain groceries()
 * @method static Builder|Chain inactive()
 * @method static Builder|Chain listsTranslations(string $translationField)
 * @method static Builder|Chain newModelQuery()
 * @method static Builder|Chain newQuery()
 * @method static Builder|Chain notActive()
 * @method static Builder|Chain notTranslatedIn(?string $locale = null)
 * @method static Builder|Chain orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static Builder|Chain orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static Builder|Chain orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static Builder|Chain query()
 * @method static Builder|Chain translated()
 * @method static Builder|Chain translatedIn(?string $locale = null)
 * @method static Builder|Chain whereAvgRating($value)
 * @method static Builder|Chain whereCityId($value)
 * @method static Builder|Chain whereCreatedAt($value)
 * @method static Builder|Chain whereCreatorId($value)
 * @method static Builder|Chain whereCurrencyId($value)
 * @method static Builder|Chain whereDeletedAt($value)
 * @method static Builder|Chain whereEditorId($value)
 * @method static Builder|Chain whereId($value)
 * @method static Builder|Chain whereNumberOfItemsOnMobileGridView($value)
 * @method static Builder|Chain whereOrderColumn($value)
 * @method static Builder|Chain wherePrimaryColor($value)
 * @method static Builder|Chain wherePrimaryPhoneNumber($value)
 * @method static Builder|Chain whereRatingCount($value)
 * @method static Builder|Chain whereRegionId($value)
 * @method static Builder|Chain whereSecondaryColor($value)
 * @method static Builder|Chain whereSecondaryPhoneNumber($value)
 * @method static Builder|Chain whereStatus($value)
 * @method static Builder|Chain whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static Builder|Chain whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static Builder|Chain whereType($value)
 * @method static Builder|Chain whereUpdatedAt($value)
 * @method static Builder|Chain whereUuid($value)
 * @method static Builder|Chain whereViewCount($value)
 * @method static Builder|Chain whereWhatsappPhoneNumber($value)
 * @method static Builder|Chain withTranslation()
 * @mixin Eloquent
 */
class Chain extends Model implements HasMedia
{
    use HasAppTypes;
    use HasMediaTrait;
    use HasStatuses;
    use HasTypes;
    use HasUuid;
    use HasViewCount;
    use Translatable;


    public const STATUS_DRAFT = 1;
    public const STATUS_ACTIVE = 2;
    public const STATUS_INACTIVE = 3;

    public const CHANNEL_GROCERY_OBJECT = 1;
    public const CHANNEL_FOOD_OBJECT = 2;

    protected $fillable = ['title', 'description'];
    protected $with = ['translations'];
    protected $translatedAttributes = ['title', 'description'];
    protected $appends = [
        'logo',
        'cover',
        'gallery',
    ];


    public function branches(): HasMany
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
