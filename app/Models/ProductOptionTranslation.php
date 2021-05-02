<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\ProductOptionTranslation
 *
 * @property int $id
 * @property int $product_option_id
 * @property string|null $title
 * @property string $locale
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|ProductOptionTranslation newModelQuery()
 * @method static Builder|ProductOptionTranslation newQuery()
 * @method static Builder|ProductOptionTranslation query()
 * @method static Builder|ProductOptionTranslation whereCreatedAt($value)
 * @method static Builder|ProductOptionTranslation whereId($value)
 * @method static Builder|ProductOptionTranslation whereLocale($value)
 * @method static Builder|ProductOptionTranslation whereProductOptionId($value)
 * @method static Builder|ProductOptionTranslation whereTitle($value)
 * @method static Builder|ProductOptionTranslation whereUpdatedAt($value)
 * @mixin Eloquent
 * @noinspection PhpFullyQualifiedNameUsageInspection
 * @noinspection PhpUnnecessaryFullyQualifiedNameInspection
 */
class ProductOptionTranslation extends Model
{
    protected $fillable = ['title'];
}
