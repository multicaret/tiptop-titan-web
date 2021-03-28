<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MetaDataTranslation
 *
 * @property int $id
 * @property int $meta_data_id
 * @property string $locale
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property string|null $og_title
 * @property string|null $og_description
 * @property string|null $og_type
 * @property string|null $twitter_card
 * @property string|null $twitter_title
 * @property string|null $twitter_description
 * @method static \Illuminate\Database\Eloquent\Builder|MetaDataTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MetaDataTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MetaDataTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|MetaDataTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetaDataTranslation whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetaDataTranslation whereMetaDataId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetaDataTranslation whereMetaDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetaDataTranslation whereMetaTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetaDataTranslation whereOgDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetaDataTranslation whereOgTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetaDataTranslation whereOgType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetaDataTranslation whereTwitterCard($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetaDataTranslation whereTwitterDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetaDataTranslation whereTwitterTitle($value)
 * @mixin \Eloquent
 */
class MetaDataTranslation extends Model
{
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
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
