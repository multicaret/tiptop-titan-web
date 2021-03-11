<?php

namespace App\Models;

use App\Traits\HasMediaTrait;
use App\Traits\HasStatuses;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * App\Models\Region
 *
 * @property int $id
 * @property int $country_id
 * @property string $english_name
 * @property string|null $code
 * @property int|null $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\City[] $cities
 * @property-read int|null $cities_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Location[] $contacts
 * @property-read int|null $contacts_count
 * @property-read \App\Models\Country $country
 * @property-read mixed $cover
 * @property-read mixed $gallery
 * @property-read mixed $is_published
 * @property-read mixed $status_name
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection|Media[] $media
 * @property-read int|null $media_count
 * @property-read \App\Models\RegionTranslation|null $translation
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\RegionTranslation[] $translations
 * @property-read int|null $translations_count
 * @method static \Illuminate\Database\Eloquent\Builder|Region draft()
 * @method static \Illuminate\Database\Eloquent\Builder|Region inactive()
 * @method static \Illuminate\Database\Eloquent\Builder|Region incomplete()
 * @method static \Illuminate\Database\Eloquent\Builder|Region listsTranslations($translationField)
 * @method static \Illuminate\Database\Eloquent\Builder|Region newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Region newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Region notPublished()
 * @method static \Illuminate\Database\Eloquent\Builder|Region notTranslatedIn($locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Region orWhereTranslation($translationField, $value, $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Region orWhereTranslationLike($translationField, $value, $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Region orderByTranslation($translationField, $sortMethod = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|Region published()
 * @method static \Illuminate\Database\Eloquent\Builder|Region query()
 * @method static \Illuminate\Database\Eloquent\Builder|Region translated()
 * @method static \Illuminate\Database\Eloquent\Builder|Region translatedIn($locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereEnglishName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereTranslation($translationField, $value, $locale = null, $method = 'whereHas', $operator = '=')
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereTranslationLike($translationField, $value, $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Region withTranslation()
 * @mixin \Eloquent
 * @property int|null $order_column
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereOrderColumn($value)
 */
class Region extends Model implements HasMedia
{
    use HasMediaTrait,
        Translatable,
        HasStatuses;

    const STATUS_INCOMPLETE = 0;
    const STATUS_DRAFT = 1;
    const STATUS_PUBLISHED = 2;
    const STATUS_INACTIVE = 3;

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
