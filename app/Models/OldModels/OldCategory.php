<?php

namespace App\Models\OldModels;


use App\Models\Taxonomy;
use Astrotomic\Translatable\Translatable;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;


/**
 * App\Models\OldModels\OldCategory
 *
 * @property int $id
 * @property int|null $added_by
 * @property int|null $parent_id
 * @property string $type Multi Category Types
 * @property int|null $sort_order
 * @property int $appears_on_suggestions
 * @property int $deletable Can Be Deleted or Disabled
 * @property string $published_at
 * @property string|null $disabled_at Is Entity Disabled
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read bool $has_children
 * @property-read \App\Models\OldModels\OldCategoryTranslation|null $translation
 * @property-read Collection|\App\Models\OldModels\OldCategoryTranslation[] $translations
 * @property-read int|null $translations_count
 * @method static Builder|OldCategory grocery()
 * @method static Builder|OldCategory listsTranslations(string $translationField)
 * @method static Builder|OldCategory newModelQuery()
 * @method static Builder|OldCategory newQuery()
 * @method static Builder|OldCategory notTranslatedIn(?string $locale = null)
 * @method static Builder|OldCategory orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static Builder|OldCategory orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static Builder|OldCategory orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static Builder|OldCategory query()
 * @method static Builder|OldCategory translated()
 * @method static Builder|OldCategory translatedIn(?string $locale = null)
 * @method static Builder|OldCategory whereAddedBy($value)
 * @method static Builder|OldCategory whereAppearsOnSuggestions($value)
 * @method static Builder|OldCategory whereCreatedAt($value)
 * @method static Builder|OldCategory whereDeletable($value)
 * @method static Builder|OldCategory whereDeletedAt($value)
 * @method static Builder|OldCategory whereDisabledAt($value)
 * @method static Builder|OldCategory whereId($value)
 * @method static Builder|OldCategory whereParentId($value)
 * @method static Builder|OldCategory wherePublishedAt($value)
 * @method static Builder|OldCategory whereSortOrder($value)
 * @method static Builder|OldCategory whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static Builder|OldCategory whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static Builder|OldCategory whereType($value)
 * @method static Builder|OldCategory whereUpdatedAt($value)
 * @method static Builder|OldCategory withTranslation()
 * @mixin Eloquent
 */
class OldCategory extends Model
{
    use Translatable;


    protected $connection = 'mysql-old';
    protected $table = 'cms_categories';
    protected $primaryKey = 'id';
    protected $appends = ['has_children'];
    protected $with = ['translations'];
    protected array $translatedAttributes = ['title', 'description', 'icon', 'image'];
    protected $translationForeignKey = 'category_id';

    public const TYPE_MEALS = 'MEALS';
    public const TYPE_KITCHENS = 'KITCHENS';
    public const TYPE_RESTAURANTS = 'RESTAURANTS';
    public const TYPE_DISHES = 'DISHES';
    public const TYPE_SIDE_DISHES = 'SIDE_DISHES';


    public function scopeGrocery($query)
    {
        return $query->where('type', self::TYPE_DISHES)->whereNull('parent_id');
    }

    public function getHasChildrenAttribute(): bool
    {
        return ! is_null(OldCategory::whereParentId($this->id)->first());
    }


    public static function attributesComparing(): array
    {
        return [
            'id' => 'id',
            'parent_id' => 'parent_id',
            'sort_order' => 'order_column',
            'created_at' => 'created_at',
            'updated_at' => 'updated_at',
        ];
    }


    public static function typesComparing(): array
    {
        return [
            self::TYPE_DISHES => Taxonomy::TYPE_GROCERY_CATEGORY,
            self::TYPE_RESTAURANTS => Taxonomy::TYPE_FOOD_CATEGORY,
            self::TYPE_KITCHENS => Taxonomy::TYPE_FOOD_CATEGORY,
        ];
    }


}
