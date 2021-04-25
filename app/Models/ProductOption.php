<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\ProductOption
 *
 * @property int $id
 * @property int $product_id
 * @property bool $is_based_on_ingredients
 * @property bool $is_required
 * @property int $type
 *                     1: Including,
 *                     2: Excluding,
 * @property int|null $max_number_of_selection
 * @property int|null $min_number_of_selection
 * @property int $input_type
 * @property int $selection_type
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Taxonomy[] $ingredients
 * @property-read int|null $ingredients_count
 * @property-read Product $product
 * @property-read Collection|ProductOptionSelection[] $selections
 * @property-read int|null $selections_count
 * @property-read ProductOptionTranslation|null $translation
 * @property-read Collection|ProductOptionTranslation[] $translations
 * @property-read int|null $translations_count
 * @method static Builder|ProductOption listsTranslations(string $translationField)
 * @method static Builder|ProductOption newModelQuery()
 * @method static Builder|ProductOption newQuery()
 * @method static Builder|ProductOption notTranslatedIn(?string $locale = null)
 * @method static Builder|ProductOption orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static Builder|ProductOption orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static Builder|ProductOption orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static Builder|ProductOption query()
 * @method static Builder|ProductOption translated()
 * @method static Builder|ProductOption translatedIn(?string $locale = null)
 * @method static Builder|ProductOption whereCreatedAt($value)
 * @method static Builder|ProductOption whereId($value)
 * @method static Builder|ProductOption whereInputType($value)
 * @method static Builder|ProductOption whereIsBasedOnIngredients($value)
 * @method static Builder|ProductOption whereIsRequired($value)
 * @method static Builder|ProductOption whereMaxNumberOfSelection($value)
 * @method static Builder|ProductOption whereMinNumberOfSelection($value)
 * @method static Builder|ProductOption whereProductId($value)
 * @method static Builder|ProductOption whereSelectionType($value)
 * @method static Builder|ProductOption whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static Builder|ProductOption whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static Builder|ProductOption whereType($value)
 * @method static Builder|ProductOption whereUpdatedAt($value)
 * @method static Builder|ProductOption withTranslation()
 * @mixin Eloquent
 * @property int|null $order_column
 * @method static Builder|ProductOption whereOrderColumn($value)
 */
class ProductOption extends Model
{
    use Translatable;

    protected $translatedAttributes = ['title'];

    public const TYPE_INCLUDING = 1;
    public const TYPE_EXCLUDING = 2;

    public const INPUT_TYPE_PILL = 1;
    public const INPUT_TYPE_RADIO = 2;
    public const INPUT_TYPE_CHECKBOX = 6;
    public const INPUT_TYPE_SELECT = 7;

    public const SELECTION_TYPE_SINGLE_VALUE = 1;
    public const SELECTION_TYPE_MULTIPLE_VALUE = 2;

    protected $casts = [
        'is_based_on_ingredients' => 'boolean',
        'is_required' => 'boolean',
    ];

    /**
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * @return HasMany
     */
    public function selections(): HasMany
    {
        return $this->hasMany(ProductOptionSelection::class);
    }

    /**
     * @return BelongsToMany
     */
    public function ingredients(): BelongsToMany
    {
        return $this->belongsToMany(Taxonomy::class, 'product_option_ingredient', 'product_option_id', 'ingredient_id')
                    ->withPivot(['id', 'price'])
                    ->withTimestamps();
    }

    public static function inputTypesArray(): array
    {
        return [
            self::INPUT_TYPE_PILL => 'pill',
            self::INPUT_TYPE_RADIO => 'radio',
            self::INPUT_TYPE_CHECKBOX => 'checkbox',
            self::INPUT_TYPE_SELECT => 'select',
        ];
    }

}
