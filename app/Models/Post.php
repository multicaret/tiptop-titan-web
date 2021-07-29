<?php

namespace App\Models;

use App\Contracts\ShouldHaveTypes;
use App\Traits\Commentable;
use App\Traits\HasMediaTrait;
use App\Traits\HasMetaData;
use App\Traits\HasStatuses;
use App\Traits\HasTags;
use App\Traits\HasTypes;
use App\Traits\HasUuid;
use App\Traits\HasViewCount;
use App\Traits\RecordsActivity;
use Astrotomic\Translatable\Translatable;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * App\Models\Post
 *
 * @property int $id
 * @property string $uuid
 * @property int $creator_id
 * @property int $editor_id
 * @property int|null $category_id
 * @property int $type 1:Article, 2:Page, 3:Testimonial, 4..n: CUSTOM
 * @property string $avg_rating
 * @property int $rating_count
 * @property int $view_count
 * @property int|null $order_column
 * @property int $status 1:draft, 2:active, 3:Inactive, 4..n:CUSTOM
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Collection|\App\Models\Activity[] $activity
 * @property-read int|null $activity_count
 * @property-read \App\Models\Taxonomy|null $category
 * @property-read Collection|\App\Models\Comment[] $comments
 * @property-read int|null $comments_count
 * @property-read \App\Models\User $creator
 * @property-read \App\Models\User $editor
 * @property-read mixed $cover
 * @property-read mixed $gallery
 * @property-read bool $is_active
 * @property-read bool $is_inactive
 * @property-read mixed $link
 * @property-read mixed $status_name
 * @property-read MediaCollection|Media[] $media
 * @property-read int|null $media_count
 * @property-read \App\Models\MetaData $meta
 * @property-read Collection|\App\Models\Comment[] $ratings
 * @property-read int|null $ratings_count
 * @property-read Collection|\App\Models\Taxonomy[] $tags
 * @property-read int|null $tags_count
 * @property-read \App\Models\PostTranslation|null $translation
 * @property-read Collection|\App\Models\PostTranslation[] $translations
 * @property-read int|null $translations_count
 * @method static Builder|Post active()
 * @method static Builder|Post articles()
 * @method static Builder|Post blog()
 * @method static Builder|Post companiesTestimonials()
 * @method static Builder|Post draft()
 * @method static Builder|Post faq()
 * @method static Builder|Post inactive()
 * @method static Builder|Post listsTranslations(string $translationField)
 * @method static Builder|Post newModelQuery()
 * @method static Builder|Post newQuery()
 * @method static Builder|Post news()
 * @method static Builder|Post notActive()
 * @method static Builder|Post notDraft()
 * @method static Builder|Post notTranslatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Query\Builder|Post onlyTrashed()
 * @method static Builder|Post orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static Builder|Post orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static Builder|Post orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static Builder|Post pages()
 * @method static Builder|Post portfolios()
 * @method static Builder|Post query()
 * @method static Builder|Post services()
 * @method static Builder|Post translated()
 * @method static Builder|Post translatedIn(?string $locale = null)
 * @method static Builder|Post usersTestimonials()
 * @method static Builder|Post whereAvgRating($value)
 * @method static Builder|Post whereCategoryId($value)
 * @method static Builder|Post whereCreatedAt($value)
 * @method static Builder|Post whereCreatorId($value)
 * @method static Builder|Post whereDeletedAt($value)
 * @method static Builder|Post whereEditorId($value)
 * @method static Builder|Post whereId($value)
 * @method static Builder|Post whereOrderColumn($value)
 * @method static Builder|Post whereRatingCount($value)
 * @method static Builder|Post whereStatus($value)
 * @method static Builder|Post whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static Builder|Post whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static Builder|Post whereType($value)
 * @method static Builder|Post whereUpdatedAt($value)
 * @method static Builder|Post whereUuid($value)
 * @method static Builder|Post whereViewCount($value)
 * @method static Builder|Post withTranslation()
 * @method static \Illuminate\Database\Query\Builder|Post withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Post withoutTrashed()
 * @mixin Eloquent
 * @noinspection PhpFullyQualifiedNameUsageInspection
 * @noinspection PhpUnnecessaryFullyQualifiedNameInspection
 */
class Post extends Model implements HasMedia, ShouldHaveTypes
{
    use Commentable;
    use HasMediaTrait;
    use HasMetaData;
    use HasStatuses;
    use HasTags;
    use HasTypes;
    use HasUuid;
    use HasViewCount;
    use RecordsActivity;
    use SoftDeletes;
    use Translatable;

    public const TYPE_ARTICLE = 1;
    public const TYPE_PAGE = 2;
    public const TYPE_TESTIMONIAL_USER = 3;
    public const TYPE_TESTIMONIAL_COMPANY = 4;
    public const TYPE_FAQ = 5;
    public const TYPE_PORTFOLIO = 6;
    public const TYPE_SERVICE = 7;
    public const TYPE_NEWS = 8;


    public const STATUS_DRAFT = 1;
    public const STATUS_ACTIVE = 2;
    public const STATUS_INACTIVE = 3;

    //These values are hardcoded here because they are part of the DB seeder
    public const ABOUT_PAGE_ID = 1;
    public const CONTACT_PAGE_ID = 2;
    public const PRIVACY_PAGE_ID = 3;
    public const TERMS_PAGE_ID = 4;
//    public const RESTAURANT_ABOUT_PAGE_ID = 5;
//    public const RESTAURANT_PRIVACY_PAGE_ID = 6;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'type',
        'title',
        'content',
        'excerpt',
        'notes',
        'uuid',
        'status',
        'order_column',
    ];

    protected $with = [
        'translations',
//        'categories',
//        'comments'
    ];

    protected $translatedAttributes = ['title', 'content', 'excerpt', 'notes'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [];


    /**
     * Fetch all model events that require activity recording.
     *
     * @return array
     */
    protected static function getActivitiesToRecord()
    {
        return [];
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id')->withTrashed();
    }

    public function editor()
    {
        return $this->belongsTo(User::class, 'editor_id')->withTrashed();
    }

    public function categories()
    {
        return $this->tags()->where('type', Taxonomy::TYPE_POST_CATEGORY);
    }

    public function category()
    {
        return $this->belongsTo(Taxonomy::class, 'category_id');
    }

    /**
     * @return array
     */
    public static function getTypesArray(): array
    {
        return [
            self::TYPE_ARTICLE => 'article',
            self::TYPE_PAGE => 'page',
            self::TYPE_TESTIMONIAL_USER => 'user-testimonial',
            self::TYPE_TESTIMONIAL_COMPANY => 'company-testimonial',
            self::TYPE_FAQ => 'faq',
            self::TYPE_PORTFOLIO => 'portfolio',
            self::TYPE_SERVICE => 'service',
            self::TYPE_NEWS => 'news',
        ];
    }

    /**
     * Scope a query to only all blog alike posts.
     *
     * @param  Builder  $query
     *
     * @return Builder
     */
    public function scopeBlog($query)
    {
        return $query->whereIn('type', [self::TYPE_ARTICLE, self::TYPE_PORTFOLIO]);
    }

    /**
     * Scope a query to only include articles.
     *
     * @param  Builder  $query
     *
     * @return Builder
     */
    public function scopeArticles($query)
    {
        return $query->where('type', self::TYPE_ARTICLE);
    }

    /**
     * Scope a query to only include news.
     *
     * @param  Builder  $query
     *
     * @return Builder
     */
    public function scopeNews($query)
    {
        return $query->where('type', self::TYPE_NEWS);
    }

    /**
     * Scope a query to only include portfolio items.
     *
     * @param  Builder  $query
     *
     * @return Builder
     */
    public function scopePortfolios($query)
    {
        return $query->where('type', self::TYPE_PORTFOLIO);
    }

    /**
     * Scope a query to only include services.
     *
     * @param  Builder  $query
     *
     * @return Builder
     */
    public function scopeServices($query)
    {
        return $query->where('type', self::TYPE_SERVICE);
    }

    /**
     * Scope a query to only include user testimonials.
     *
     * @param  Builder  $query
     *
     * @return Builder
     */
    public function scopeUsersTestimonials($query)
    {
        return $query->where('type', self::TYPE_TESTIMONIAL_USER);
    }

    /**
     * Scope a query to only include company testimonials.
     *
     * @param  Builder  $query
     *
     * @return Builder
     */
    public function scopeCompaniesTestimonials($query)
    {
        return $query->where('type', self::TYPE_TESTIMONIAL_COMPANY);
    }

    /**
     * Scope a query to only include FAQ.
     *
     * @param  Builder  $query
     *
     * @return Builder
     */
    public function scopeFaq($query)
    {
        return $query->where('type', self::TYPE_FAQ);
    }

    /**
     * Scope a query to only include pages.
     *
     * @param  Builder  $query
     *
     * @return Builder
     */
    public function scopePages($query)
    {
        return $query->where('type', self::TYPE_PAGE);
    }

    public function getCoverAttribute()
    {
        return $this->getFirstMediaUrl('cover', 'HD');
    }

    public function getGalleryAttribute()
    {
        return $this->getMediaForUploader('gallery', 'HD');
    }

    public function registerMediaCollections(): void
    {
        $fallBackImageUrl = config('defaults.images.post_cover');
        $this->addMediaCollection('cover')
             ->useFallbackUrl(url($fallBackImageUrl))
             ->singleFile()
             ->withResponsiveImages()
             ->registerMediaConversions(function ($media) {
                 foreach (config('defaults.image_conversions.generic_cover') as $conversionName => $dimensions) {
                     $this->addMediaConversion($conversionName)
                          ->width($dimensions['width'])
                          ->height($dimensions['height']);
                 }
             });
        $this->addMediaCollection('gallery')
             ->useFallbackUrl(url($fallBackImageUrl))
             ->singleFile()
             ->withResponsiveImages()
             ->registerMediaConversions(function ($media) {
                 foreach (config('defaults.image_conversions.generic_cover') as $conversionName => $dimensions) {
                     $this->addMediaConversion($conversionName)
                          ->width($dimensions['width'])
                          ->height($dimensions['height']);
                 }
             });
    }

    public function getLinkAttribute()
    {
        $urls = [
            self::TYPE_ARTICLE => 'articles.show',
            self::TYPE_PORTFOLIO => 'portfolios.show',
            self::TYPE_PAGE => 'pages.show',
            self::TYPE_TESTIMONIAL_COMPANY => 'pages.show',
            self::TYPE_TESTIMONIAL_USER => 'pages.show',
            self::TYPE_NEWS => 'news.show',
        ];
        $routeName = 'pages.show';
        if (array_key_exists($this->type, $urls)) {
            $routeName = $urls[$this->type];
        }

        if (is_null($title = $this->title)) {
            $title = $this->translate('ar', true)->title;
        }

        return trim(route($routeName, [HasUuid::slugify($title).'-'.$this->uuid]), '-');
    }

}
