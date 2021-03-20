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
    const PRIVACY_PAGE_ID = 3;
    const TERMS_PAGE_ID = 4;
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
