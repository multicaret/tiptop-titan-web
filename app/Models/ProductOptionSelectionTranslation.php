<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ProductOptionSelectionTranslation
 *
 * @property int $id
 * @property int $product_option_selection_id
 * @property string|null $title
 * @property string $locale
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOptionSelectionTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOptionSelectionTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOptionSelectionTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOptionSelectionTranslation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOptionSelectionTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOptionSelectionTranslation whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOptionSelectionTranslation whereProductOptionSelectionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOptionSelectionTranslation whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOptionSelectionTranslation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ProductOptionSelectionTranslation extends Model
{
}
