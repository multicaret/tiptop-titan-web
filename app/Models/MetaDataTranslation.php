<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
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
 * @method static Builder|MetaDataTranslation newModelQuery()
 * @method static Builder|MetaDataTranslation newQuery()
 * @method static Builder|MetaDataTranslation query()
 * @method static Builder|MetaDataTranslation whereId($value)
 * @method static Builder|MetaDataTranslation whereLocale($value)
 * @method static Builder|MetaDataTranslation whereMetaDataId($value)
 * @method static Builder|MetaDataTranslation whereMetaDescription($value)
 * @method static Builder|MetaDataTranslation whereMetaTitle($value)
 * @method static Builder|MetaDataTranslation whereOgDescription($value)
 * @method static Builder|MetaDataTranslation whereOgTitle($value)
 * @method static Builder|MetaDataTranslation whereOgType($value)
 * @method static Builder|MetaDataTranslation whereTwitterCard($value)
 * @method static Builder|MetaDataTranslation whereTwitterDescription($value)
 * @method static Builder|MetaDataTranslation whereTwitterTitle($value)
 * @mixin Eloquent
 * @noinspection PhpFullyQualifiedNameUsageInspection
 * @noinspection PhpUnnecessaryFullyQualifiedNameInspection
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
