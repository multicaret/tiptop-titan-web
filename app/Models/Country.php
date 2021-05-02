<?php

namespace App\Models;

use App\Traits\HasStatuses;
use Astrotomic\Translatable\Translatable;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\Country
 *
 * @property int $id
 * @property int|null $currency_id
 * @property int|null $language_id
 * @property int|null $timezone_id
 * @property string $english_name
 * @property string $alpha2_code
 * @property string $alpha3_code
 * @property int $numeric_code
 * @property string|null $phone_code
 * @property int|null $order_column
 * @property int $status 1:draft, 2:active, 3:Inactive, 4..n:CUSTOM
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|\App\Models\City[] $cities
 * @property-read int|null $cities_count
 * @property-read \App\Models\Currency|null $currency
 * @property-read bool $is_active
 * @property-read bool $is_inactive
 * @property-read array $status_js
 * @property-read mixed $status_name
 * @property-read \App\Models\Language|null $language
 * @property-read Collection|\App\Models\Location[] $locations
 * @property-read int|null $locations_count
 * @property-read Collection|\App\Models\Region[] $regions
 * @property-read int|null $regions_count
 * @property-read \App\Models\Timezone|null $timezone
 * @property-read \App\Models\CountryTranslation|null $translation
 * @property-read Collection|\App\Models\CountryTranslation[] $translations
 * @property-read int|null $translations_count
 * @method static Builder|Country active()
 * @method static Builder|Country draft()
 * @method static Builder|Country inactive()
 * @method static Builder|Country listsTranslations(string $translationField)
 * @method static Builder|Country newModelQuery()
 * @method static Builder|Country newQuery()
 * @method static Builder|Country notActive()
 * @method static Builder|Country notDraft()
 * @method static Builder|Country notTranslatedIn(?string $locale = null)
 * @method static Builder|Country orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static Builder|Country orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static Builder|Country orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static Builder|Country query()
 * @method static Builder|Country translated()
 * @method static Builder|Country translatedIn(?string $locale = null)
 * @method static Builder|Country whereAlpha2Code($value)
 * @method static Builder|Country whereAlpha3Code($value)
 * @method static Builder|Country whereCreatedAt($value)
 * @method static Builder|Country whereCurrencyId($value)
 * @method static Builder|Country whereEnglishName($value)
 * @method static Builder|Country whereId($value)
 * @method static Builder|Country whereLanguageId($value)
 * @method static Builder|Country whereNumericCode($value)
 * @method static Builder|Country whereOrderColumn($value)
 * @method static Builder|Country wherePhoneCode($value)
 * @method static Builder|Country whereStatus($value)
 * @method static Builder|Country whereTimezoneId($value)
 * @method static Builder|Country whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static Builder|Country whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static Builder|Country whereUpdatedAt($value)
 * @method static Builder|Country withTranslation()
 * @mixin Eloquent
 * @noinspection PhpFullyQualifiedNameUsageInspection
 * @noinspection PhpUnnecessaryFullyQualifiedNameInspection
 */
class Country extends Model
{
    use HasStatuses;
    use Translatable;


    public const STATUS_DRAFT = 1;
    public const STATUS_ACTIVE = 2;
    public const STATUS_INACTIVE = 3;


    public const IRAQ_COUNTRY_ID = 107;
    public const TURKEY_COUNTRY_ID = 225;

    protected $translatedAttributes = ['name', 'slug'];
    protected $fillable = ['name', 'slug'];
    protected $with = ['translations'];
    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    /*protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new ActiveScope);
    }*/

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function timezone()
    {
        return $this->belongsTo(Timezone::class);
    }

    public function locations()
    {
        return $this->hasMany(Location::class);
    }

    public function regions()
    {
        return $this->hasMany(Region::class);
    }

    public function cities()
    {
        return $this->hasMany(City::class);
    }

    public static function getAll()
    {
        return cache()->tags('countries')
                      ->rememberForever('countries', function () {
                          return Country::all();
                      });
    }

}
