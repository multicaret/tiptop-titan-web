<?php

namespace App\Models;

use App\Traits\HasMediaTrait;
use App\Traits\HasStatuses;
use App\Traits\HasUuid;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * App\Models\Slide
 *
 * @property int $id
 * @property int $creator_id
 * @property int $editor_id
 * @property string $uuid
 * @property string $title
 * @property string|null $description
 * @property int $link_type
 * @property string|null $link_value
 * @property string|null $linkage The entity the deeplink will point to that has ID of link_value (i.e: Restaurant::class
 * @property \Illuminate\Support\Carbon|null $begins_at
 * @property \Illuminate\Support\Carbon|null $expires_at
 * @property int $status 0:incomplete, 1:draft, 2:published, 3:Inactive, 4..n:CUSTOM
 * @property int|null $order_column
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read mixed $is_published
 * @property-read mixed $status_name
 * @property-read \App\Models\SlideTranslation|null $translation
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\SlideTranslation[] $translations
 * @property-read int|null $translations_count
 * @method static \Illuminate\Database\Eloquent\Builder|Slide draft()
 * @method static \Illuminate\Database\Eloquent\Builder|Slide inactive()
 * @method static \Illuminate\Database\Eloquent\Builder|Slide incomplete()
 * @method static \Illuminate\Database\Eloquent\Builder|Slide listsTranslations(string $translationField)
 * @method static \Illuminate\Database\Eloquent\Builder|Slide newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Slide newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Slide notPublished()
 * @method static \Illuminate\Database\Eloquent\Builder|Slide notTranslatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Query\Builder|Slide onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Slide orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Slide orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Slide orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|Slide published()
 * @method static \Illuminate\Database\Eloquent\Builder|Slide query()
 * @method static \Illuminate\Database\Eloquent\Builder|Slide translated()
 * @method static \Illuminate\Database\Eloquent\Builder|Slide translatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Slide whereBeginsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Slide whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Slide whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Slide whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Slide whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Slide whereEditorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Slide whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Slide whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Slide whereLinkType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Slide whereLinkValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Slide whereLinkage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Slide whereOrderColumn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Slide whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Slide whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Slide whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static \Illuminate\Database\Eloquent\Builder|Slide whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Slide whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Slide whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Slide withTranslation()
 * @method static \Illuminate\Database\Query\Builder|Slide withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Slide withoutTrashed()
 * @mixin \Eloquent
 */
class Slide extends Model
{
    use SoftDeletes,
        HasUuid,
        Translatable,
        HasStatuses;

    const STATUS_INCOMPLETE = 0;
    const STATUS_DRAFT = 1;
    const STATUS_PUBLISHED = 2;
    const STATUS_INACTIVE = 3;

    const TYPE_EXTERNAL = 1;
    const TYPE_UNIVERSAL = 2;
    const TYPE_DEFERRED_DEEPLINK = 3;
    const TYPE_DEEPLINK = 4;

    protected $with = ['translations'];

    protected $translatedAttributes = ['alt_tag','image'];

    protected $fillable = ['title', 'description', 'link_value', 'link_type', 'begins_at', 'expires_at', 'status'];

    protected $casts = [
        'begins_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public static function getTypesArray(): array
    {
        return [
            self::TYPE_EXTERNAL => 'external',
            self::TYPE_UNIVERSAL => 'universal',
            self::TYPE_DEFERRED_DEEPLINK => 'deferred-deeplink',
            self::TYPE_DEEPLINK => 'deeplink',
        ];
    }
}
