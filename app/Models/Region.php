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
 * App\Models\Region
 *
 * @property int $id
 * @property int $country_id
 * @property string $english_name
 * @property string|null $code
 * @property int|null $order_column
 * @property int $status 1:draft, 2:active, 3:Inactive, 4..n:CUSTOM
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|\App\Models\City[] $cities
 * @property-read int|null $cities_count
 * @property-read Collection|\App\Models\Location[] $contacts
 * @property-read int|null $contacts_count
 * @property-read \App\Models\Country $country
 * @property-read mixed $cover
 * @property-read mixed $gallery
 * @property-read bool $is_active
 * @property-read bool $is_inactive
 * @property-read mixed $status_name
 * @property-read MediaCollection|Media[] $media
 * @property-read int|null $media_count
 * @property-read \App\Models\RegionTranslation|null $translation
 * @property-read Collection|\App\Models\RegionTranslation[] $translations
 * @property-read int|null $translations_count
 * @method static Builder|Region active()
 * @method static Builder|Region draft()
 * @method static Builder|Region inactive()
 * @method static Builder|Region listsTranslations(string $translationField)
 * @method static Builder|Region newModelQuery()
 * @method static Builder|Region newQuery()
 * @method static Builder|Region notActive()
 * @method static Builder|Region notTranslatedIn(?string $locale = null)
 * @method static Builder|Region orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static Builder|Region orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static Builder|Region orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static Builder|Region query()
 * @method static Builder|Region translated()
 * @method static Builder|Region translatedIn(?string $locale = null)
 * @method static Builder|Region whereCode($value)
 * @method static Builder|Region whereCountryId($value)
 * @method static Builder|Region whereCreatedAt($value)
 * @method static Builder|Region whereEnglishName($value)
 * @method static Builder|Region whereId($value)
 * @method static Builder|Region whereOrderColumn($value)
 * @method static Builder|Region whereStatus($value)
 * @method static Builder|Region whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static Builder|Region whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static Builder|Region whereUpdatedAt($value)
 * @method static Builder|Region withTranslation()
 * @mixin Eloquent
 * @property-read array $status_js
 */
class Region extends Model implements HasMedia
{
    use HasMediaTrait;
    use HasStatuses;
    use Translatable;


    public const STATUS_DRAFT = 1;
    public const STATUS_ACTIVE = 2;
    public const STATUS_INACTIVE = 3;

    protected $fillable = ['name', 'slug'];
    protected $with = ['translations'];
    protected $translatedAttributes = ['name', 'slug'];
    protected $appends = [
        'cover',
        'gallery',
    ];

    public function contacts()
    {
        return $this->hasMany(Location::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function cities()
    {
        return $this->hasMany(City::class);
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
     * @return Region
     */
    public static function create(string $name, int $countryId): Region
    {
        $region = new self;
        $region->country_id = $countryId;
        $region->english_name = $name;
        $region->save();
        $region->translateOrNew('ar')->name = $name;
        $region->save();

        return $region;
    }

    public static function getAllOfCountry($countryId = null)
    {
        if (is_null($countryId)) {
            $countryId = config('defaults.country.id');
        }

        return cache()->tags('regions')
                      ->rememberForever('regions_of_country_'.$countryId,
                          function () use ($countryId) {
                              return Region::whereCountryId($countryId)->get();
                          }
                      );
    }

    public static function getAll()
    {
        return cache()->tags('regions')
                      ->rememberForever('regions',
                          function () {
                              return Region::all();
                          }
                      );
    }
}
