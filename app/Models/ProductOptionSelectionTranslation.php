<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\ProductOptionSelectionTranslation
 *
 * @property int $id
 * @property int $product_option_selection_id
 * @property string|null $title
 * @property string $locale
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|ProductOptionSelectionTranslation newModelQuery()
 * @method static Builder|ProductOptionSelectionTranslation newQuery()
 * @method static Builder|ProductOptionSelectionTranslation query()
 * @method static Builder|ProductOptionSelectionTranslation whereCreatedAt($value)
 * @method static Builder|ProductOptionSelectionTranslation whereId($value)
 * @method static Builder|ProductOptionSelectionTranslation whereLocale($value)
 * @method static Builder|ProductOptionSelectionTranslation whereProductOptionSelectionId($value)
 * @method static Builder|ProductOptionSelectionTranslation whereTitle($value)
 * @method static Builder|ProductOptionSelectionTranslation whereUpdatedAt($value)
 * @mixin Eloquent
 * @noinspection PhpFullyQualifiedNameUsageInspection
 * @noinspection PhpUnnecessaryFullyQualifiedNameInspection
 */
class ProductOptionSelectionTranslation extends Model
{
    protected $fillable = ['title'];
}
