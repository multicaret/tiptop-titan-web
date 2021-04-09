<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\ProductOptionSelection
 *
 * @property int $id
 * @property int $product_option_id
 * @property int $product_id this is a helper
 * @property float|null $price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\ProductOption $option
 * @property-read \App\Models\Product $product
 * @property-read \App\Models\ProductOptionSelectionTranslation|null $translation
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ProductOptionSelectionTranslation[] $translations
 * @property-read int|null $translations_count
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOptionSelection listsTranslations(string $translationField)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOptionSelection newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOptionSelection newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOptionSelection notTranslatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOptionSelection orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOptionSelection orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOptionSelection orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOptionSelection query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOptionSelection translated()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOptionSelection translatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOptionSelection whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOptionSelection whereExtraPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOptionSelection whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOptionSelection whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOptionSelection whereProductOptionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOptionSelection whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOptionSelection whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOptionSelection whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOptionSelection withTranslation()
 * @mixin \Eloquent
 */
class ProductOptionSelection extends Model
{
    use Translatable;

    protected $translatedAttributes = ['title'];

    protected $casts = [
        'price' => 'double',
    ];

    /**
     * @return BelongsTo
     */
    public function option(): BelongsTo
    {
        return $this->belongsTo(ProductOption::class, 'product_option_id');
    }

    // This is only a helper
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

}
