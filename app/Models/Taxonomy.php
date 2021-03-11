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
 * @property int $type 1:Category, 2: Tag, 3..n: CUSTOM
 * @property string|null $icon
 * @property int $views
 * @property int $left
 * @property int $right
 * @property int|null $depth
 * @property int|null $order_column
 * @property int|null $status
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Taxonomy[] $children
 * @property-read int|null $children_count
 * @property-read \App\Models\User $creator
 * @property-read \App\Models\User $editor
 * @property-read mixed $cover
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Media[] $media
 * @property-read int|null $media_count
 * @property-read \App\Models\Taxonomy|null $parent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Post[] $posts
 * @property-read int|null $posts_count
 * @property-read \App\Models\TaxonomyTranslation|null $translation
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\TaxonomyTranslation[] $translations
 * @property-read int|null $translations_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $usersOfCategory
 * @property-read int|null $users_of_category_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $usersOfSkill
 * @property-read int|null $users_of_skill_count
 * @method static \Illuminate\Database\Eloquent\Builder|\Baum\Node limitDepth($limit)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Taxonomy listsTranslations($translationField)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Taxonomy newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Taxonomy newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Taxonomy notTranslatedIn($locale = null)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Taxonomy onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Taxonomy orWhereTranslation($translationField, $value, $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Taxonomy orWhereTranslationLike($translationField, $value, $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Taxonomy orderByTranslation($translationField, $sortMethod = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Taxonomy postCategories()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Taxonomy postTags()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Taxonomy query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Taxonomy tags()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Taxonomy translated()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Taxonomy translatedIn($locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Taxonomy whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Taxonomy whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Taxonomy whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Taxonomy whereDepth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Taxonomy whereEditorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Taxonomy whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Taxonomy whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Taxonomy whereLeft($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Taxonomy whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Taxonomy whereRight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Taxonomy whereTranslation($translationField, $value, $locale = null, $method = 'whereHas', $operator = '=')
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Taxonomy whereTranslationLike($translationField, $value, $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Taxonomy whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Taxonomy whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Taxonomy whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Taxonomy whereViews($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Taxonomy withTranslation()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Taxonomy withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\Baum\Node withoutNode($node)
 * @method static \Illuminate\Database\Eloquent\Builder|\Baum\Node withoutRoot()
 * @method static \Illuminate\Database\Eloquent\Builder|\Baum\Node withoutSelf()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Taxonomy withoutTrashed()
 * @mixin \Eloquent
 * @property int $view_count
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property-read mixed $is_published
 * @property-read mixed $status_name
 * @method static \Illuminate\Database\Eloquent\Builder|Taxonomy draft()
 * @method static \Illuminate\Database\Eloquent\Builder|Taxonomy inactive()
 * @method static \Illuminate\Database\Eloquent\Builder|Taxonomy incomplete()
 * @method static \Illuminate\Database\Eloquent\Builder|Taxonomy notPublished()
 * @method static \Illuminate\Database\Eloquent\Builder|Taxonomy published()
 * @method static \Illuminate\Database\Eloquent\Builder|Taxonomy whereOrderColumn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Taxonomy whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Taxonomy whereViewCount($value)
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
    const TYPE_USER_PROFESSION = 5;

    protected $fillable = ['title', 'description', 'parent_id', 'type', 'order_column'];
    protected $translatedAttributes = ['title', 'description'];
    protected $with = ['translations'];

    protected $appends = [
        'cover',
    ];


    /**
     * Scope a query to only include category taxonomies.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePostCategories($query)
    {
        return $query->where('type', '=', self::TYPE_POST_CATEGORY);
    }

    /**
     * Scope a query to only include all tags.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTags($query)
    {
        return $query->whereIn('type', [self::TYPE_TAG]);
    }

    /**
     * Scope a query to only include tags taxonomies for post only.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePostTags($query)
    {
        return $query->where('type', '=', self::TYPE_TAG);
    }


    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function editor()
    {
        return $this->belongsTo(User::class, 'editor_id');
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
            self::TYPE_USER_PROFESSION => 'user-profession',
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
        $this->addMediaCollection('cover')
             ->singleFile()
             ->registerMediaConversions(function (Media $media) {
                 $this->addMediaConversion('thumbnail')
                      ->width(256)
                      ->height(256);
             });
    }


    public function getCoverAttribute()
    {
        $cover = url(config('defaults.images.taxonomy_cover'));
        if ($this->type == self::TYPE_USER_PROFESSION) {
            $cover = url(config('defaults.images.taxonomy_cover'));
        }

        if ($this->hasMedia('cover')) {
            $cover = $this->getFirstMedia('cover')->getFullUrl();
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
            self::TYPE_USER_PROFESSION => 'user-profession.show',
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
