<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MetaData
 *
 * @property int $id
 * @property string $model_type
 * @property int $model_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\MetaDataTranslation|null $translation
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\MetaDataTranslation[] $translations
 * @property-read int|null $translations_count
 * @method static \Illuminate\Database\Eloquent\Builder|MetaData listsTranslations(string $translationField)
 * @method static \Illuminate\Database\Eloquent\Builder|MetaData newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MetaData newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MetaData notTranslatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|MetaData orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|MetaData orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|MetaData orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|MetaData query()
 * @method static \Illuminate\Database\Eloquent\Builder|MetaData translated()
 * @method static \Illuminate\Database\Eloquent\Builder|MetaData translatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|MetaData whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetaData whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetaData whereModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetaData whereModelType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetaData whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static \Illuminate\Database\Eloquent\Builder|MetaData whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|MetaData whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetaData withTranslation()
 * @mixin \Eloquent
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
