<?php

namespace App\Models;

use App\Contracts\ShouldHaveTypes;
use App\Traits\HasMediaTrait;
use App\Traits\HasStatuses;
use App\Traits\HasTypes;
use App\Traits\HasUuid;
use App\Traits\HasViewCount;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Baum\Node;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;


class Taxonomy extends Node implements HasMedia, ShouldHaveTypes, TranslatableContract
{
    use Translatable,
        HasMediaTrait,
        SoftDeletes,
        HasViewCount,
        HasTypes,
        HasUuid,
        HasStatuses;

    const STATUS_INCOMPLETE = 0;
    const STATUS_DRAFT = 1;
    const STATUS_PUBLISHED = 2;
    const STATUS_INACTIVE = 3;

    const TYPE_POST_CATEGORY = 1;
    const TYPE_TAG = 2;
    const TYPE_GROCERY_CATEGORY = 3;
    const TYPE_FOOD_CATEGORY = 4;
    const TYPE_RATING_1_ISSUE = 10;
    const TYPE_RATING_2_ISSUE = 12;

    protected $fillable = ['title', 'description', 'parent_id', 'type', 'order_column'];
    protected $translatedAttributes = ['title', 'description'];
    protected $with = ['translations'];

    protected $appends = [
        'cover',
        'cover_small',
    ];


    /**
     * Scope a query to only include only parents.
     *
     * @param  Builder  $query
     *
     * @return Builder
     */
    public function scopeParents($query)
    {
        return $query->whereNull('parent_id');
    }


    /**
     * Scope a query to only include category taxonomies.
     *
     * @param  Builder  $query
     *
     * @return Builder
     */
    public function scopePostCategories($query)
    {
        return $query->where('type', '=', self::TYPE_POST_CATEGORY);
    }

    /**
     * Scope a query to only include all tags.
     *
     * @param  Builder  $query
     *
     * @return Builder
     */
    public function scopeTags($query)
    {
        return $query->where('type', self::TYPE_TAG);
    }

    public function scopePostTags($query)
    {
        return $query->where('type', '=', self::TYPE_TAG);
    }

    /**
     * Scope a query to only include tags taxonomies for grocery categories.
     *
     * @param  Builder  $query
     *
     * @return Builder
     */
    public function scopeGroceryCategories($query): Builder
    {
        return $query->where('type', '=', self::TYPE_GROCERY_CATEGORY);
    }

    /**
     * Scope a query to only include tags taxonomies for food categories.
     *
     * @param  Builder  $query
     *
     * @return Builder
     */
    public function scopeFoodCategories($query): Builder
    {
        return $query->where('type', '=', self::TYPE_FOOD_CATEGORY);
    }

    /**
     * Scope a query to only include rating issues
     *
     * @param  Builder  $query
     *
     * @return Builder
     */
    public function scopeRatingOneIssues($query): Builder
    {
        return $query->where('type', '=', self::TYPE_RATING_1_ISSUE);
    }

    public function scopeRatingTwoIssues($query): Builder
    {
        return $query->where('type', '=', self::TYPE_RATING_2_ISSUE);
    }


    public function creator(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function editor(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'editor_id');
    }

    public function products(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'category_product', 'category_id', 'product_id')
                    ->withTimestamps();
    }

    public function upSellsProducts(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'category_product_up_sell', 'category_id', 'product_id')
                    ->withTimestamps();
    }

    public function crossSellsProducts(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'category_product_cross_sell', 'category_id', 'product_id')
                    ->withTimestamps();
    }

    public function hasChildren()
    {
        return $this->children()->count() > 0;
    }

    public static function typesHaving($index): array
    {
        $typeVariations = [
            'parent' => [
                Taxonomy::TYPE_POST_CATEGORY,
            ],
            'cover_image' => []
        ];

        return $typeVariations[$index];
    }


    public function posts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Post::class, 'category_id');
    }

    /**
     * Get all of the posts that are assigned this tag.
     */
    public function tagPosts(): \Illuminate\Database\Eloquent\Relations\MorphToMany
    {
        return $this->morphedByMany(Post::class, 'taggable')
                    ->withPivot(['order_column'])
                    ->withTimestamps();
    }


    /**
     * @return array
     */
    public static function getTypesArray(): array
    {
        return [
            self::TYPE_POST_CATEGORY => 'category',
            self::TYPE_TAG => 'tag',
            self::TYPE_GROCERY_CATEGORY => 'grocery-category',
            self::TYPE_FOOD_CATEGORY => 'food-category',
            self::TYPE_RATING_1_ISSUE => 'rating-issue-1',
            self::TYPE_RATING_2_ISSUE => 'rating-issue-2',
        ];
    }


    public function usersOfCategory()
    {
        return $this->belongsToMany(User::class, 'category_user')
                    ->withPivot(['order'])
                    ->withTimestamps();
    }

    public function usersOfSkill()
    {
        return $this->belongsToMany(User::class, 'skill_user')
                    ->withPivot(['order'])
                    ->withTimestamps();
    }

    /**
     * @param $type
     * @param $taxonomy
     *
     * @return Taxonomy
     */
    public static function createTaxonomy(string $taxonomy, int $type): Taxonomy
    {
        $taxonomyObject = new self;
        $taxonomyObject->creator_id = auth()->id();
        $taxonomyObject->editor_id = auth()->id();
        $taxonomyObject->type = $type;
        $taxonomyObject->save();
        if (app()->isLocale('en')) {
            $taxonomyObject->translateOrNew('en')->title = $taxonomy;
        } else {
            $taxonomyObject->translateOrNew(app()->getLocale())->title = $taxonomy;
            $taxonomyObject->translateOrNew('en')->title = $taxonomy;
            $taxonomyObject->translateOrNew('en')->is_auto_inserted = true;
        }
        $taxonomyObject->save();

        return $taxonomyObject;
    }

    /**
     * @param  array|null  $taxonomies
     * @param  int  $type
     *
     * @return array|null
     */
    public static function checkExistenceOrCreate(?array $taxonomies, int $type): ?array
    {
        if (empty($taxonomies)) {
            return $taxonomies;
        }
        if ( ! is_array($taxonomies)) {
            $taxonomies = [$taxonomies];
        }
        foreach ($taxonomies as $key => $taxonomy) {
            if (is_numeric($taxonomy)) {
                $taxonomyObject = self::find($taxonomy);
            } else {
                $taxonomyObject = self::whereHas('translations', function ($query) use ($taxonomy) {
                    return $query->where('title', $taxonomy);
                })->first();
            }
            if (is_null($taxonomyObject)) {
                $taxonomyObject = self::createTaxonomy($type, $taxonomy);
            }
            $taxonomies[$key] = $taxonomyObject->id;
        }

        return $taxonomies;
    }

    public function registerMediaCollections(): void
    {
        /*$isGroceryCategory = $this->type === self::TYPE_GROCERY_CATEGORY;*/
        $fallBackImageUrl = config('defaults.images.taxonomy_cover');
        $this->addMediaCollection('cover')
             ->useFallbackUrl(url($fallBackImageUrl))
             ->singleFile()
             ->withResponsiveImages()
             ->registerMediaConversions(function (Media $media) /*use ($isGroceryCategory)*/ {
                 /*if ($isGroceryCategory) {
                     foreach (config('defaults.image_conversions.generic_cover') as $conversionName => $dimensions) {
                         $this->addMediaConversion($conversionName)
                              ->width($dimensions['width'])
                              ->height($dimensions['height']);
                     }
                 } else {*/
                 foreach (config('defaults.image_conversions.generic_cover') as $conversionName => $dimensions) {
                     $this->addMediaConversion($conversionName)
                          ->width($dimensions['width'])
                          ->height($dimensions['height']);
                 }
//                 }
             });
    }


    public function getCoverAttribute()
    {
        $cover = url(config('defaults.images.taxonomy_cover'));
        if ($this->type == self::TYPE_GROCERY_CATEGORY) {
            $cover = url(config('defaults.images.grocery_category_cover')).'?v=2';
        }
        if ($this->type == self::TYPE_FOOD_CATEGORY) {
            $cover = url(config('defaults.images.food_category_cover'));
        }

        if ($this->hasMedia('cover')) {
            $cover = $this->getFirstMedia('cover')->getFullUrl('1K');
        }

        return $cover;
    }

    public function getCoverSmallAttribute()
    {
        $cover = url(config('defaults.images.taxonomy_cover'));
        if ($this->type == self::TYPE_GROCERY_CATEGORY) {
            $cover = url(config('defaults.images.grocery_category_cover')).'?v=2';
        }
        if ($this->type == self::TYPE_FOOD_CATEGORY) {
            $cover = url(config('defaults.images.food_category_cover'));
        }

        if ($this->hasMedia('cover')) {
            $cover = $this->getFirstMedia('cover')->getFullUrl('SD');
        }

        return $cover;
    }

    public static function determineIconProperAttributeName($icon)
    {
        $iconExploded = explode('fa-', $icon);
        $name = $iconExploded[1];
        $nameExploded = explode('-', $name);
        foreach ($nameExploded as $index => $word) {
            if ($index != 0) {
                $nameExploded[$index] = ucfirst($word);
            }
        }
        $name = implode('', $nameExploded);

        $postFix = null;
        $postFixRaw = $iconExploded[0];

        $allIcons = collect(config('font-awesome-icons.all'));

        if (Str::contains($postFixRaw, 'fas') &&
            $allIcons->filter(function ($name, $id) use ($icon) {
                return $icon != $name && $id == Str::replaceFirst('fas', 'far', $icon);
            })->count()
        ) {
            $postFix = 'solid';
            $name = ucfirst($name);
        }

        return $postFix.$name;
    }


    public function getLinkAttribute()
    {
        $urls = [
            self::TYPE_POST_CATEGORY => 'categories.show',
            self::TYPE_TAG => 'tags.show',
//            self::TYPE_GROCERY_CATEGORY => 'grocery-categories.show',
//            self::TYPE_FOOD_CATEGORY => 'food-categories.show',
        ];

        $routeName = 'categories.show';
        if (array_key_exists($this->type, $urls)) {
            $routeName = $urls[$this->type];
        }

        if (is_null($title = $this->title)) {
            $title = $this->translate('ar', true)->title;
        }

        return trim(route($routeName, [HasUuid::slugify($title).'-'.$this->uuid]), '-');
    }
}
