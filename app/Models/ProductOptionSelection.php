<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\ProductOptionSelection
 *
 * @property int $id
 * @property int $product_option_id
 * @property int $product_id this is a helper
 * @property float $price
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \App\Models\ProductOption $option
 * @property-read \App\Models\Product $product
 * @property-read \App\Models\ProductOptionSelectionTranslation|null $translation
 * @property-read Collection|\App\Models\ProductOptionSelectionTranslation[] $translations
 * @property-read int|null $translations_count
 * @method static Builder|ProductOptionSelection listsTranslations(string $translationField)
 * @method static Builder|ProductOptionSelection newModelQuery()
 * @method static Builder|ProductOptionSelection newQuery()
 * @method static Builder|ProductOptionSelection notTranslatedIn(?string $locale = null)
 * @method static Builder|ProductOptionSelection orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static Builder|ProductOptionSelection orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static Builder|ProductOptionSelection orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static Builder|ProductOptionSelection query()
 * @method static Builder|ProductOptionSelection translated()
 * @method static Builder|ProductOptionSelection translatedIn(?string $locale = null)
 * @method static Builder|ProductOptionSelection whereCreatedAt($value)
 * @method static Builder|ProductOptionSelection whereId($value)
 * @method static Builder|ProductOptionSelection wherePrice($value)
 * @method static Builder|ProductOptionSelection whereProductId($value)
 * @method static Builder|ProductOptionSelection whereProductOptionId($value)
 * @method static Builder|ProductOptionSelection whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static Builder|ProductOptionSelection whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static Builder|ProductOptionSelection whereUpdatedAt($value)
 * @method static Builder|ProductOptionSelection withTranslation()
 * @mixin Eloquent
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
