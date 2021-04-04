<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\MetaData
 *
 * @property int $id
 * @property string $model_type
 * @property int $model_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read MetaDataTranslation|null $translation
 * @property-read Collection|MetaDataTranslation[] $translations
 * @property-read int|null $translations_count
 * @method static Builder|MetaData listsTranslations(string $translationField)
 * @method static Builder|MetaData newModelQuery()
 * @method static Builder|MetaData newQuery()
 * @method static Builder|MetaData notTranslatedIn(?string $locale = null)
 * @method static Builder|MetaData orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static Builder|MetaData orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static Builder|MetaData orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static Builder|MetaData query()
 * @method static Builder|MetaData translated()
 * @method static Builder|MetaData translatedIn(?string $locale = null)
 * @method static Builder|MetaData whereCreatedAt($value)
 * @method static Builder|MetaData whereId($value)
 * @method static Builder|MetaData whereModelId($value)
 * @method static Builder|MetaData whereModelType($value)
 * @method static Builder|MetaData whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static Builder|MetaData whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static Builder|MetaData whereUpdatedAt($value)
 * @method static Builder|MetaData withTranslation()
 * @mixin Eloquent
 */
class MetaData extends Model
{
    use Translatable;

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['translations'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['model_id', 'model_type'];

    /**
     * The attributes that are translatable.
     *
     * @var array
     */
    protected $translatedAttributes = [
        'meta_title',
        'meta_description',
        'og_title',
        'og_description',
        'og_type',
        'twitter_card',
        'twitter_title',
        'twitter_description',
    ];
}
