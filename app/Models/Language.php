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
 * App\Models\Language
 *
 * @property int $id
 * @property string $english_name
 * @property string $code
 * @property string $locale_country
 * @property bool $is_rtl
 * @property int $status 1:draft, 2:active, 3:Inactive, 4..n:CUSTOM
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|\App\Models\Country[] $countries
 * @property-read int|null $countries_count
 * @property-read bool $is_active
 * @property-read bool $is_inactive
 * @property-read mixed $status_class
 * @property-read mixed $status_name
 * @property-read \App\Models\LanguageTranslation|null $translation
 * @property-read Collection|\App\Models\LanguageTranslation[] $translations
 * @property-read int|null $translations_count
 * @property-read Collection|\App\Models\User[] $users
 * @property-read int|null $users_count
 * @method static Builder|Language active()
 * @method static Builder|Language draft()
 * @method static Builder|Language inactive()
 * @method static Builder|Language listsTranslations(string $translationField)
 * @method static Builder|Language newModelQuery()
 * @method static Builder|Language newQuery()
 * @method static Builder|Language notActive()
 * @method static Builder|Language notDraft()
 * @method static Builder|Language notTranslatedIn(?string $locale = null)
 * @method static Builder|Language orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static Builder|Language orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static Builder|Language orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static Builder|Language query()
 * @method static Builder|Language translated()
 * @method static Builder|Language translatedIn(?string $locale = null)
 * @method static Builder|Language whereCode($value)
 * @method static Builder|Language whereCreatedAt($value)
 * @method static Builder|Language whereEnglishName($value)
 * @method static Builder|Language whereId($value)
 * @method static Builder|Language whereIsRtl($value)
 * @method static Builder|Language whereLocaleCountry($value)
 * @method static Builder|Language whereStatus($value)
 * @method static Builder|Language whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static Builder|Language whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static Builder|Language whereUpdatedAt($value)
 * @method static Builder|Language withTranslation()
 * @mixin Eloquent
 * @noinspection PhpFullyQualifiedNameUsageInspection
 * @noinspection PhpUnnecessaryFullyQualifiedNameInspection
 */
class Language extends Model
{
    use HasStatuses;
    use Translatable;


    public const STATUS_DRAFT = 1;
    public const STATUS_ACTIVE = 2;
    public const STATUS_INACTIVE = 3;

    protected $translatedAttributes = ['name'];
    protected $fillable = ['name'];
    protected $with = ['translations'];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
//        static::addGlobalScope(new ActiveScope);
    }

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_rtl' => 'boolean',
    ];

    public function countries()
    {
        return $this->hasMany(Country::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class)
                    ->withPivot(['level'])
                    ->withTimestamps();
    }
}
