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
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
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
 * @property float $avg_rating
 * @property int $rating_count
 * @property int $view_count
 * @property int|null $order_column
 * @property int|null $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Activity[] $activity
 * @property-read int|null $activity_count
 * @property-read \App\Models\Taxonomy|null $category
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Comment[] $comments
 * @property-read int|null $comments_count
 * @property-read \App\Models\User $creator
 * @property-read \App\Models\User $editor
 * @property-read mixed $cover
 * @property-read mixed $gallery
 * @property-read mixed $is_published
 * @property-read mixed $link
 * @property-read mixed $status_name
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\MediaLibrary\MediaCollections\Models\Media[] $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Comment[] $ratings
 * @property-read int|null $ratings_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Taxonomy[] $taxonomies
 * @property-read int|null $taxonomies_count
 * @property-read \App\Models\PostTranslation|null $translation
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PostTranslation[] $translations
 * @property-read int|null $translations_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post articles()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post blog()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post companiesTestimonials()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post draft()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post faq()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post inactive()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post incomplete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post listsTranslations($translationField)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post notPublished()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post notTranslatedIn($locale = null)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Post onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post orWhereTranslation($translationField, $value, $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post orWhereTranslationLike($translationField, $value, $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post orderByTranslation($translationField, $sortMethod = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post pages()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post portfolios()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post published()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post services()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post translated()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post translatedIn($locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post usersTestimonials()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post whereAvgRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post whereEditorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post whereRatingCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post whereTranslation($translationField, $value, $locale = null, $method = 'whereHas', $operator = '=')
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post whereTranslationLike($translationField, $value, $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post whereViews($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post withTranslation()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Post withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Post withoutTrashed()
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereOrderColumn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereViewCount($value)
 */
class Post extends Model implements HasMedia, ShouldHaveTypes
{
    use HasMediaTrait,
        SoftDeletes,
        Translatable,
        Commentable,
        HasViewCount,
        RecordsActivity,
        HasUuid,
        HasTypes,
        HasMetaData,
        HasStatuses,
        HasTags;

    const TYPE_ARTICLE = 1;
    const TYPE_PAGE = 2;
    const TYPE_TESTIMONIAL_USER = 3;
    const TYPE_TESTIMONIAL_COMPANY = 4;
    const TYPE_FAQ = 5;
    const TYPE_PORTFOLIO = 6;
    const TYPE_SERVICE = 7;
    const TYPE_NEWS = 8;

    const STATUS_INCOMPLETE = 0;
    const STATUS_DRAFT = 1;
    const STATUS_PUBLISHED = 2;
    const STATUS_INACTIVE = 3;

    //These values are hardcoded here because they are part of the DB seeder
    const ABOUT_PAGE_ID = 1;
    const CONTACT_PAGE_ID = 2;
    const FAQ_PAGE_ID = 3;
    const PRIVACY_PAGE_ID = 4;
    const TERMS_PAGE_ID = 5;
    const SERVICES_PAGE_ID = 6;
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
        'categories',
        'comments'
    ];

    protected $translatedAttributes = ['title', 'content', 'excerpt', 'notes'];

    protected $appends = [
        'cover',
        'gallery',
    ];
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
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function editor()
    {
        return $this->belongsTo(User::class, 'editor_id');
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
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeBlog($query)
    {
        return $query->whereIn('type', [self::TYPE_ARTICLE, self::TYPE_PORTFOLIO]);
    }

    /**
     * Scope a query to only include articles.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeArticles($query)
    {
        return $query->where('type', self::TYPE_ARTICLE);
    }

    /**
     * Scope a query to only include news.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNews($query)
    {
        return $query->where('type', self::TYPE_NEWS);
    }

    /**
     * Scope a query to only include portfolio items.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePortfolios($query)
    {
        return $query->where('type', self::TYPE_PORTFOLIO);
    }

    /**
     * Scope a query to only include services.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeServices($query)
    {
        return $query->where('type', self::TYPE_SERVICE);
    }

    /**
     * Scope a query to only include user testimonials.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUsersTestimonials($query)
    {
        return $query->where('type', self::TYPE_TESTIMONIAL_USER);
    }

    /**
     * Scope a query to only include company testimonials.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCompaniesTestimonials($query)
    {
        return $query->where('type', self::TYPE_TESTIMONIAL_COMPANY);
    }

    /**
     * Scope a query to only include FAQ.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFaq($query)
    {
        return $query->where('type', self::TYPE_FAQ);
    }

    /**
     * Scope a query to only include pages.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePages($query)
    {
        return $query->where('type', self::TYPE_PAGE);
    }

    public function getCoverAttribute()
    {
        $image = config('defaults.images.post_cover');

        if ( ! is_null($media = $this->getFirstMedia('cover'))) {
            $image = $media->getUrl();
        }
        if ( ! is_null($media = $this->getFirstMedia('gallery'))) {
            $image = $media->getUrl();
        }

        return url($image);
    }

    public function getGalleryAttribute()
    {
        return $this->getMediaForUploader('gallery');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('cover')
             ->singleFile();

        $this->addMediaCollection('gallery');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('medium')
             ->width(1280)
             ->height(873)
             ->performOnCollections('cover', 'gallery')
             ->nonQueued();

        $this->addMediaConversion('thumbnail')
             ->width(480)
             ->height(270)
             ->performOnCollections('cover', 'gallery')
             ->nonQueued();
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
