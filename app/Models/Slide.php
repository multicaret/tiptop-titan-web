<?php

namespace App\Models;

use App\Traits\HasAppTypes;
use App\Traits\HasStatuses;
use App\Traits\HasUuid;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo as BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

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
    use HasAppTypes;
    use HasStatuses;
    use HasUuid;
    use SoftDeletes;
    use Translatable;

    public const STATUS_INCOMPLETE = 0;
    public const STATUS_DRAFT = 1;
    public const STATUS_PUBLISHED = 2;
    public const STATUS_INACTIVE = 3;

    public const LINK_TYPE_EXTERNAL = 1;
    public const LINK_TYPE_UNIVERSAL = 2;
    public const LINK_TYPE_DEFERRED_DEEPLINK = 3;
    public const LINK_TYPE_DEEPLINK = 4;

    public const TYPE_GROCERY_OBJECT = 8;
    public const TYPE_FOOD_OBJECT = 9;
    public const TYPE_FOOD_AND_GROCERY_OBJECT = 10;

    protected $with = ['translations'];

    protected $translatedAttributes = ['alt_tag', 'image'];

    protected $fillable = ['title', 'description', 'link_value', 'link_type', 'begins_at', 'expires_at', 'status'];

    protected $casts = [
        'begins_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public static function getTypesArray(): array
    {
        return [
            self::LINK_TYPE_EXTERNAL => 'external',
            self::LINK_TYPE_UNIVERSAL => 'universal',
            self::LINK_TYPE_DEFERRED_DEEPLINK => 'deferred-deeplink',
            self::LINK_TYPE_DEEPLINK => 'deeplink',
        ];
    }

    public static function getAllChannelsRich(): array
    {
        return [
            self::TYPE_GROCERY_OBJECT => [
                'id' => self::TYPE_GROCERY_OBJECT,
                'title' => trans("strings.grocery"),
                'class' => 'success',
            ],
            self::TYPE_FOOD_OBJECT => [
                'id' => self::TYPE_FOOD_OBJECT,
                'title' => trans("strings.food"),
                'class' => 'dark',
            ],
            self::TYPE_FOOD_AND_GROCERY_OBJECT => [
                'id' => self::TYPE_FOOD_AND_GROCERY_OBJECT,
                'title' => trans("strings.grocery_and_food"),
                'class' => 'info',
            ],
        ];
    }


    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
