<?php

namespace App\Models;

use App\Traits\HasTypes;
use Astrotomic\Translatable\Translatable;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\RemoteConfig
 *
 * @property int $id
 * @property int $build_number
 * @property int $application_type 1:customer, 2:restaurant, 3:driver
 * @property string $platform_type ios,android,other
 * @property int $update_method 0:disabled, 1:soft, 2:hard
 * @property array|null $data
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \App\Models\RemoteTranslation|null $translation
 * @property-read Collection|\App\Models\RemoteTranslation[] $translations
 * @property-read int|null $translations_count
 * @method static Builder|RemoteConfig listsTranslations(string $translationField)
 * @method static Builder|RemoteConfig newModelQuery()
 * @method static Builder|RemoteConfig newQuery()
 * @method static Builder|RemoteConfig notTranslatedIn(?string $locale = null)
 * @method static Builder|RemoteConfig orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static Builder|RemoteConfig orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static Builder|RemoteConfig orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static Builder|RemoteConfig query()
 * @method static Builder|RemoteConfig translated()
 * @method static Builder|RemoteConfig translatedIn(?string $locale = null)
 * @method static Builder|RemoteConfig whereApplicationType($value)
 * @method static Builder|RemoteConfig whereBuildNumber($value)
 * @method static Builder|RemoteConfig whereCreatedAt($value)
 * @method static Builder|RemoteConfig whereData($value)
 * @method static Builder|RemoteConfig whereId($value)
 * @method static Builder|RemoteConfig wherePlatformType($value)
 * @method static Builder|RemoteConfig whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static Builder|RemoteConfig whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static Builder|RemoteConfig whereUpdateMethod($value)
 * @method static Builder|RemoteConfig whereUpdatedAt($value)
 * @method static Builder|RemoteConfig withTranslation()
 * @mixin Eloquent
 */
class RemoteConfig extends Model
{
    use HasTypes;
    use Translatable;

    //Application Types
    public const TYPE_APPLICATION_CUSTOMER = 1;
    public const TYPE_APPLICATION_RESTAURANT = 2;
    public const TYPE_APPLICATION_DRIVER = 3;

    //Platform Types
    public const TYPE_PLATFORM_IOS = 1;
    public const TYPE_PLATFORM_ANDROID = 2;

    public const FORCE_UPDATE_METHOD_DISABLED = 0;
    public const FORCE_UPDATE_METHOD_SOFT = 1;
    public const FORCE_UPDATE_METHOD_HARD = 2;


    protected $translatedAttributes = ['title', 'data_translated'];
    protected $fillable = ['data', 'title', 'data_translated'];
    protected $casts = ['data' => 'json'];

}

