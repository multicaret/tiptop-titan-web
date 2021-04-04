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
 * App\Models\Boot
 *
 * @property int $id
 * @property int $build_number
 * @property int $application_type 1:customer, 2:restaurant, 3:driver
 * @property string $platform_type ios,android,other
 * @property int $update_method 0:disabled, 1:soft, 2:hard
 * @property array|null $data
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read BootTranslation|null $translation
 * @property-read Collection|BootTranslation[] $translations
 * @property-read int|null $translations_count
 * @method static Builder|Boot listsTranslations(string $translationField)
 * @method static Builder|Boot newModelQuery()
 * @method static Builder|Boot newQuery()
 * @method static Builder|Boot notTranslatedIn(?string $locale = null)
 * @method static Builder|Boot orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static Builder|Boot orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static Builder|Boot orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static Builder|Boot query()
 * @method static Builder|Boot translated()
 * @method static Builder|Boot translatedIn(?string $locale = null)
 * @method static Builder|Boot whereApplicationType($value)
 * @method static Builder|Boot whereBuildNumber($value)
 * @method static Builder|Boot whereCreatedAt($value)
 * @method static Builder|Boot whereData($value)
 * @method static Builder|Boot whereId($value)
 * @method static Builder|Boot wherePlatformType($value)
 * @method static Builder|Boot whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static Builder|Boot whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static Builder|Boot whereUpdateMethod($value)
 * @method static Builder|Boot whereUpdatedAt($value)
 * @method static Builder|Boot withTranslation()
 * @mixin Eloquent
 */
class Boot extends Model
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

