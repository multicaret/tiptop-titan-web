<?php

namespace App\Models;

use App\Traits\HasMediaTrait;
use App\Traits\HasStatuses;
use Astrotomic\Translatable\Translatable;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * App\Models\City
 *
 * @property int $id
 * @property int $country_id
 * @property int|null $region_id
 * @property int|null $timezone_id
 * @property string $english_name
 * @property int|null $population
 * @property string|null $latitude
 * @property string|null $longitude
 * @property int|null $order_column
 * @property int $status 1:draft, 2:active, 3:Inactive, 4..n:CUSTOM
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \App\Models\Country $country
 * @property-read mixed $cover
 * @property-read mixed $gallery
 * @property-read bool $is_active
 * @property-read bool $is_inactive
 * @property-read mixed $status_name
 * @property-read MediaCollection|Media[] $media
 * @property-read int|null $media_count
 * @property-read \App\Models\Region|null $region
 * @property-read \App\Models\Timezone|null $timezone
 * @property-read \App\Models\CityTranslation|null $translation
 * @property-read Collection|\App\Models\CityTranslation[] $translations
 * @property-read int|null $translations_count
 * @method static Builder|City active()
 * @method static Builder|City draft()
 * @method static Builder|City inactive()
 * @method static Builder|City listsTranslations(string $translationField)
 * @method static Builder|City newModelQuery()
 * @method static Builder|City newQuery()
 * @method static Builder|City notActive()
 * @method static Builder|City notDraft()
 * @method static Builder|City notTranslatedIn(?string $locale = null)
 * @method static Builder|City orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static Builder|City orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static Builder|City orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static Builder|City query()
 * @method static Builder|City translated()
 * @method static Builder|City translatedIn(?string $locale = null)
 * @method static Builder|City whereCountryId($value)
 * @method static Builder|City whereCreatedAt($value)
 * @method static Builder|City whereEnglishName($value)
 * @method static Builder|City whereId($value)
 * @method static Builder|City whereLatitude($value)
 * @method static Builder|City whereLongitude($value)
 * @method static Builder|City whereOrderColumn($value)
 * @method static Builder|City wherePopulation($value)
 * @method static Builder|City whereRegionId($value)
 * @method static Builder|City whereStatus($value)
 * @method static Builder|City whereTimezoneId($value)
 * @method static Builder|City whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static Builder|City whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static Builder|City whereUpdatedAt($value)
 * @method static Builder|City withTranslation()
 * @mixin Eloquent
 * @noinspection PhpFullyQualifiedNameUsageInspection
 * @noinspection PhpUnnecessaryFullyQualifiedNameInspection
 */
class City extends Model implements HasMedia
{
    use HasMediaTrait;
    use HasStatuses;
    use Translatable;


    public const STATUS_DRAFT = 1;
    public const STATUS_ACTIVE = 2;
    public const STATUS_INACTIVE = 3;

    protected $fillable = [
        'name',
        'slug',
        'order_column',
    ];
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
                              return City::active()->whereRegionId($regionId)->get();
                          }
                      );
    }
}
