<?php

namespace App\Models;

use App\Traits\HasTypes;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Boot
 *
 * @property int $id
 * @property int $build_number
 * @property int $application_type 1:customer, 2:restaurant, 3:driver
 * @property string $platform_type ios,android,other
 * @property int $update_method 0:disabled, 1:soft, 2:hard
 * @property array|null $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\BootTranslation|null $translation
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\BootTranslation[] $translations
 * @property-read int|null $translations_count
 * @method static \Illuminate\Database\Eloquent\Builder|Boot listsTranslations(string $translationField)
 * @method static \Illuminate\Database\Eloquent\Builder|Boot newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Boot newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Boot notTranslatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Boot orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Boot orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Boot orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|Boot query()
 * @method static \Illuminate\Database\Eloquent\Builder|Boot translated()
 * @method static \Illuminate\Database\Eloquent\Builder|Boot translatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Boot whereApplicationType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Boot whereBuildNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Boot whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Boot whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Boot whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Boot wherePlatformType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Boot whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static \Illuminate\Database\Eloquent\Builder|Boot whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Boot whereUpdateMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Boot whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Boot withTranslation()
 * @mixin \Eloquent
 */
class Boot extends Model
{
    use Translatable,
        HasTypes;

    //Application Types
    const TYPE_APPLICATION_CUSTOMER = 1;
    const TYPE_APPLICATION_RESTAURANT = 2;
    const TYPE_APPLICATION_DRIVER = 3;

    //Platform Types
    const TYPE_PLATFORM_IOS = 1;
    const TYPE_PLATFORM_ANDROID = 2;

    const FORCE_UPDATE_METHOD_DISABLED = 0;
    const FORCE_UPDATE_METHOD_SOFT = 1;
    const FORCE_UPDATE_METHOD_HARD = 2;


    protected $translatedAttributes = ['title', 'data_translated'];
    protected $fillable = ['data', 'title', 'data_translated'];
    protected $casts = ['data' => 'json'];

}

