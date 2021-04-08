<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ProductOption
 *
 * @property int $id
 * @property int $product_id
 * @property int $ingredient_id
 * @property int $type
 *                     1: Including,
 *                     2: Excluding,
 * @property bool $is_behaviour_method_excluding
 * @property int|null $max_number_of_selection
 * @property int|null $min_number_of_selection
 * @property float|null $extra_price
 * @property int $selection_type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ProductOptionTranslation[] $translations
 * @property-read int|null $translations_count
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOption listsTranslations(string $translationField)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOption newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOption newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOption notTranslatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOption orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOption orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOption orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOption query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOption translated()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOption translatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOption whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOption whereExtraPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOption whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOption whereIngredientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOption whereIsBehaviourMethodExcluding($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOption whereMaxNumberOfSelection($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOption whereMinNumberOfSelection($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOption whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOption whereSelectionType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOption whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOption whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOption whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOption whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOption withTranslation()
 * @mixin \Eloquent
 */
class ProductOption extends Model
{
    use Translatable;

//    protected $fillable = [''];

    protected $translatedAttributes = ['group_title', 'option_title'];


    protected $casts = [
        'is_behaviour_method_excluding' => 'boolean',
        'extra_price' => 'double',
    ];

    public const TYPE_INCLUDING = 1;
    public const TYPE_EXCLUDING = 2;


    public const SELECTION_TYPE_SINGLE_VALUE = 1;
    public const SELECTION_TYPE_MULTIPLE_VALUE = 2;


}
