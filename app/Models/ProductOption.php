<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\ProductOption
 *
 * @property int $id
 * @property int $product_id
 * @property int $type
 *                     1: Including,
 *                     2: Excluding,
 * @property int|null $max_number_of_selection
 * @property int|null $min_number_of_selection
 * @property int $selection_type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Product $product
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ProductOptionSelection[] $selections
 * @property-read int|null $selections_count
 * @property-read \App\Models\ProductOptionTranslation|null $translation
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
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOption whereId($value)
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

    protected $translatedAttributes = ['title'];

    public const TYPE_INCLUDING = 1;
    public const TYPE_EXCLUDING = 2;

    public const SELECTION_TYPE_SINGLE_VALUE = 1;
    public const SELECTION_TYPE_MULTIPLE_VALUE = 2;

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

}
