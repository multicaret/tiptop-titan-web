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


/**
 * App\Models\Taxonomy
 *
 * @property int $id
 * @property string $uuid
 * @property int $creator_id
 * @property int $editor_id
 * @property int|null $parent_id
 * @property int|null $branch_id
 * @property int $type 1:Category, 2: Tag, 3..n: CUSTOM
 * @property string|null $icon
 * @property int $view_count
 * @property int $left
 * @property int $right
 * @property int|null $depth
 * @property string $step
 * @property int|null $order_column
 * @property int $status 0:incomplete, 1:draft, 2:published, 3:Inactive, 4..n:CUSTOM
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|Taxonomy[] $children
 * @property-read int|null $children_count
 * @property-read \App\Models\User $creator
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product[] $crossSellsProducts
 * @property-read int|null $cross_sells_products_count
 * @property-read \App\Models\User $editor
 * @property-read mixed $cover
 * @property-read mixed $cover_small
 * @property-read mixed $is_published
 * @property-read mixed $link
 * @property-read mixed $status_name
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection|Media[] $media
 * @property-read int|null $media_count
 * @property-read Taxonomy|null $parent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Post[] $posts
 * @property-read int|null $posts_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product[] $products
 * @property-read int|null $products_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Post[] $tagPosts
 * @property-read int|null $tag_posts_count
 * @property-read \App\Models\TaxonomyTranslation|null $translation
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\TaxonomyTranslation[] $translations
 * @property-read int|null $translations_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product[] $upSellsProducts
 * @property-read int|null $up_sells_products_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $usersOfCategory
 * @property-read int|null $users_of_category_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $usersOfSkill
 * @property-read int|null $users_of_skill_count
 * @method static Builder|Taxonomy draft()
 * @method static Builder|Taxonomy foodCategories()
 * @method static Builder|Taxonomy groceryCategories()
 * @method static Builder|Taxonomy inactive()
 * @method static Builder|Taxonomy incomplete()
 * @method static \Illuminate\Database\Eloquent\Builder|Node limitDepth($limit)
 * @method static Builder|Taxonomy listsTranslations(string $translationField)
 * @method static Builder|Taxonomy newModelQuery()
 * @method static Builder|Taxonomy newQuery()
 * @method static Builder|Taxonomy notPublished()
 * @method static Builder|Taxonomy notTranslatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Query\Builder|Taxonomy onlyTrashed()
 * @method static Builder|Taxonomy orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static Builder|Taxonomy orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static Builder|Taxonomy orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static Builder|Taxonomy parents()
 * @method static Builder|Taxonomy postCategories()
 * @method static Builder|Taxonomy postTags()
 * @method static Builder|Taxonomy published()
 * @method static Builder|Taxonomy query()
 * @method static Builder|Taxonomy ratingIssues()
 * @method static Builder|Taxonomy tags()
 * @method static Builder|Taxonomy translated()
 * @method static Builder|Taxonomy translatedIn(?string $locale = null)
 * @method static Builder|Taxonomy whereBranchId($value)
 * @method static Builder|Taxonomy whereCreatedAt($value)
 * @method static Builder|Taxonomy whereCreatorId($value)
 * @method static Builder|Taxonomy whereDeletedAt($value)
 * @method static Builder|Taxonomy whereDepth($value)
 * @method static Builder|Taxonomy whereEditorId($value)
 * @method static Builder|Taxonomy whereIcon($value)
 * @method static Builder|Taxonomy whereId($value)
 * @method static Builder|Taxonomy whereLeft($value)
 * @method static Builder|Taxonomy whereOrderColumn($value)
 * @method static Builder|Taxonomy whereParentId($value)
 * @method static Builder|Taxonomy whereRight($value)
 * @method static Builder|Taxonomy whereStatus($value)
 * @method static Builder|Taxonomy whereStep($value)
 * @method static Builder|Taxonomy whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static Builder|Taxonomy whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static Builder|Taxonomy whereType($value)
 * @method static Builder|Taxonomy whereUpdatedAt($value)
 * @method static Builder|Taxonomy whereUuid($value)
 * @method static Builder|Taxonomy whereViewCount($value)
 * @method static Builder|Taxonomy withTranslation()
 * @method static \Illuminate\Database\Query\Builder|Taxonomy withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Node withoutNode($node)
 * @method static \Illuminate\Database\Eloquent\Builder|Node withoutRoot()
 * @method static \Illuminate\Database\Eloquent\Builder|Node withoutSelf()
 * @method static \Illuminate\Database\Query\Builder|Taxonomy withoutTrashed()
 * @mixin \Eloquent
 * @property-read \App\Models\Branch $branch
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Branch[] $branches
 * @property-read int|null $branches_count
 * @property-read \App\Models\Chain $chain
 * @method static Builder|Taxonomy ingredientCategories()
 * @method static Builder|Taxonomy ingredients()
 */
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
    const TYPE_MENU_CATEGORY = 5;
    const TYPE_RATING_ISSUE = 10;
    const TYPE_INGREDIENT = 11;
    const TYPE_INGREDIENT_CATEGORY = 12;
    const TYPE_UNIT = 15;

    protected $fillable = ['title', 'description', 'parent_id', 'type', 'order_column'];
    protected $translatedAttributes = ['title', 'description'];
    protected $with = ['translations', 'chain', 'branches'];

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

    public function scopeUnitCategories($query): Builder
    {
        return $query->where('type', '=', self::TYPE_UNIT);
    }

    public function scopeMenuCategories($query): Builder
    {
        return $query->where('type', '=', self::TYPE_MENU_CATEGORY);
    }

    /**
     * Scope a query to only include rating issues
     *
     * @param  Builder  $query
     *
     * @return Builder
     */
    public function scopeRatingIssues($query): Builder
    {
        return $query->where('type', '=', self::TYPE_RATING_ISSUE);
    }

    /**
     * Scope a query to only include Ingredients issues
     *
     * @param  Builder  $query
     *
     * @return Builder
     */
    public function scopeIngredients($query): Builder
    {
        return $query->where('type', '=', self::TYPE_INGREDIENT);
    }

    /**
     * Scope a query to only include Ingredient Categories issues
     *
     * @param  Builder  $query
     *
     * @return Builder
     */
    public function scopeIngredientCategories($query): Builder
    {
        return $query->where('type', '=', self::TYPE_INGREDIENT_CATEGORY);
    }

    public function creator(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function editor(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'editor_id');
    }

    public function chain(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Chain::class, 'chain_id');
    }

    public function branch(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function branches(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Branch::class, 'category_branch', 'category_id', 'branch_id')
                    ->withTimestamps();
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
                Taxonomy::TYPE_GROCERY_CATEGORY,
                Taxonomy::TYPE_FOOD_CATEGORY,

            ],
            'cover_image' => [
                Taxonomy::TYPE_GROCERY_CATEGORY,
                Taxonomy::TYPE_FOOD_CATEGORY,
            ],
            'content' => [
                Taxonomy::TYPE_POST_CATEGORY,
            ],
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
            self::TYPE_MENU_CATEGORY => 'menu-category',
            self::TYPE_RATING_ISSUE => 'rating-issue',
            self::TYPE_INGREDIENT => 'ingredient',
            self::TYPE_INGREDIENT_CATEGORY => 'ingredient-category',
            self::TYPE_UNIT => 'unit',
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
