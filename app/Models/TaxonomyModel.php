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
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;


/**
 * App\Models\TaxonomyModel
 *
 * @property int $id
 * @property string $uuid
 * @property int $creator_id
 * @property int $editor_id
 * @property int|null $parent_id
 * @property int|null $branch_id
 * @property int|null $chain_id
 * @property int|null $ingredient_category_id
 * @property int $type 1:Category, 2: Tag, 3..n: CUSTOM
 * @property string|null $icon
 * @property int $view_count
 * @property int $left
 * @property int $right
 * @property int|null $depth
 * @property string $step
 * @property int|null $order_column
 * @property int $status 1:draft, 2:active, 3:Inactive, 4..n:CUSTOM
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Branch|null $branch
 * @property-read Collection|Branch[] $branches
 * @property-read int|null $branches_count
 * @property-read Chain|null $chain
 * @property-read User $creator
 * @property-read Collection|Product[] $crossSellsProducts
 * @property-read int|null $cross_sells_products_count
 * @property-read User $editor
 * @property-read mixed $cover
 * @property-read mixed $cover_small
 * @property-read bool $is_active
 * @property-read bool $is_inactive
 * @property-read mixed $link
 * @property-read array $status_js
 * @property-read mixed $status_name
 * @property-read Taxonomy|null $ingredientCategory
 * @property-read Collection|TaxonomyModel[] $ingredientsOfCategory
 * @property-read int|null $ingredients_of_category_count
 * @property-read MediaCollection|Media[] $media
 * @property-read int|null $media_count
 * @property-read Collection|Product[] $menuProducts
 * @property-read int|null $menu_products_count
 * @property-read Collection|Post[] $posts
 * @property-read int|null $posts_count
 * @property-read Collection|Product[] $products
 * @property-read int|null $products_count
 * @property-read Collection|Post[] $tagPosts
 * @property-read int|null $tag_posts_count
 * @property-read TaxonomyTranslation|null $translation
 * @property-read Collection|TaxonomyTranslation[] $translations
 * @property-read int|null $translations_count
 * @property-read Collection|Product[] $upSellsProducts
 * @property-read int|null $up_sells_products_count
 * @property-read Collection|User[] $usersOfCategory
 * @property-read int|null $users_of_category_count
 * @property-read Collection|User[] $usersOfSkill
 * @property-read int|null $users_of_skill_count
 * @method static Builder|TaxonomyModel active()
 * @method static Builder|TaxonomyModel draft()
 * @method static Builder|TaxonomyModel foodCategories()
 * @method static Builder|TaxonomyModel groceryCategories()
 * @method static Builder|TaxonomyModel inactive()
 * @method static Builder|TaxonomyModel ingredientCategories()
 * @method static Builder|TaxonomyModel ingredients()
 * @method static Builder|TaxonomyModel listsTranslations(string $translationField)
 * @method static Builder|TaxonomyModel menuCategories()
 * @method static Builder|TaxonomyModel newModelQuery()
 * @method static Builder|TaxonomyModel newQuery()
 * @method static Builder|TaxonomyModel notActive()
 * @method static Builder|TaxonomyModel notTranslatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Query\Builder|TaxonomyModel onlyTrashed()
 * @method static Builder|TaxonomyModel orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static Builder|TaxonomyModel orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static Builder|TaxonomyModel orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static Builder|TaxonomyModel ordersCancellationReasons()
 * @method static Builder|TaxonomyModel parents()
 * @method static Builder|TaxonomyModel postCategories()
 * @method static Builder|TaxonomyModel postTags()
 * @method static Builder|TaxonomyModel query()
 * @method static Builder|TaxonomyModel ratingIssues()
 * @method static Builder|TaxonomyModel searchTags()
 * @method static Builder|TaxonomyModel tags()
 * @method static Builder|TaxonomyModel translated()
 * @method static Builder|TaxonomyModel translatedIn(?string $locale = null)
 * @method static Builder|TaxonomyModel unitCategories()
 * @method static Builder|TaxonomyModel whereBranchId($value)
 * @method static Builder|TaxonomyModel whereChainId($value)
 * @method static Builder|TaxonomyModel whereCreatedAt($value)
 * @method static Builder|TaxonomyModel whereCreatorId($value)
 * @method static Builder|TaxonomyModel whereDeletedAt($value)
 * @method static Builder|TaxonomyModel whereDepth($value)
 * @method static Builder|TaxonomyModel whereEditorId($value)
 * @method static Builder|TaxonomyModel whereIcon($value)
 * @method static Builder|TaxonomyModel whereId($value)
 * @method static Builder|TaxonomyModel whereIngredientCategoryId($value)
 * @method static Builder|TaxonomyModel whereLeft($value)
 * @method static Builder|TaxonomyModel whereOrderColumn($value)
 * @method static Builder|TaxonomyModel whereParentId($value)
 * @method static Builder|TaxonomyModel whereRight($value)
 * @method static Builder|TaxonomyModel whereStatus($value)
 * @method static Builder|TaxonomyModel whereStep($value)
 * @method static Builder|TaxonomyModel whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static Builder|TaxonomyModel whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static Builder|TaxonomyModel whereType($value)
 * @method static Builder|TaxonomyModel whereUpdatedAt($value)
 * @method static Builder|TaxonomyModel whereUuid($value)
 * @method static Builder|TaxonomyModel whereViewCount($value)
 * @method static Builder|TaxonomyModel withTranslation()
 * @method static \Illuminate\Database\Query\Builder|TaxonomyModel withTrashed()
 * @method static \Illuminate\Database\Query\Builder|TaxonomyModel withoutTrashed()
 * @mixin Eloquent
 */
class TaxonomyModel extends Model implements HasMedia, ShouldHaveTypes, TranslatableContract
{
    use HasMediaTrait;
    use HasStatuses;
    use HasTypes;
    use HasUuid;
    use HasViewCount;
    use SoftDeletes;
    use Translatable;


    public const STATUS_DRAFT = 1;
    public const STATUS_ACTIVE = 2;
    public const STATUS_INACTIVE = 3;

    public const TYPE_POST_CATEGORY = 1;
    public const TYPE_TAG = 2;
    public const TYPE_GROCERY_CATEGORY = 3;
    public const TYPE_FOOD_CATEGORY = 4;
    public const TYPE_MENU_CATEGORY = 5;
    public const TYPE_SEARCH_TAGS = 7;
    public const TYPE_RATING_ISSUE = 10;
    public const TYPE_INGREDIENT = 11;
    public const TYPE_INGREDIENT_CATEGORY = 12;
    public const TYPE_UNIT = 15;
    public const TYPE_ORDERS_CANCELLATION_REASONS = 16;
    protected $table = 'taxonomies';
    public $translationForeignKey = 'taxonomy_id';
    public $translationModel = TaxonomyTranslation::class;
    protected $fillable = ['creator_id', 'editor_id', 'title', 'description', 'parent_id', 'type', 'order_column'];
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

    public function scopeSearchTags($query)
    {
        return $query->where('type', self::TYPE_SEARCH_TAGS);
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

    public function scopeOrdersCancellationReasons($query): Builder
    {
        return $query->where('type', '=', self::TYPE_ORDERS_CANCELLATION_REASONS);
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

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function editor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'editor_id');
    }

    public function chain(): BelongsTo
    {
        return $this->belongsTo(Chain::class, 'chain_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function ingredientCategory(): BelongsTo
    {
        return $this->belongsTo(Taxonomy::class, 'ingredient_category_id');
    }

    public function ingredientsOfCategory()
    {
        return $this->hasMany(self::class, 'ingredient_category_id');
    }

    public function branches(): BelongsToMany
    {
        return $this->belongsToMany(Branch::class, 'category_branch', 'category_id', 'branch_id')
                    ->withTimestamps();
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'category_product', 'category_id', 'product_id')
                    ->withTimestamps();
    }

    public function menuProducts(): HasMany
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    public function upSellsProducts(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'category_product_up_sell', 'category_id', 'product_id')
                    ->withTimestamps();
    }

    public function crossSellsProducts(): BelongsToMany
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


    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'category_id');
    }

    /**
     * Get all of the posts that are assigned this tag.
     */
    public function tagPosts(): MorphToMany
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
            self::TYPE_SEARCH_TAGS => 'search-tags',
            self::TYPE_GROCERY_CATEGORY => 'grocery-category',
            self::TYPE_FOOD_CATEGORY => 'food-category',
            self::TYPE_MENU_CATEGORY => 'menu-category',
            self::TYPE_RATING_ISSUE => 'rating-issue',
            self::TYPE_INGREDIENT => 'ingredient',
            self::TYPE_INGREDIENT_CATEGORY => 'ingredient-category',
            self::TYPE_UNIT => 'unit',
            self::TYPE_ORDERS_CANCELLATION_REASONS => 'orders-cancellation-reasons',
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
