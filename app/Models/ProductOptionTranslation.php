<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ProductOptionTranslation
 *
 * @property int $id
 * @property int $product_option_id
 * @property string|null $group_title
 * @property string|null $option_title
 * @property string $locale
 * @property string|null $created_at
 * @property string|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOptionTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOptionTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOptionTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOptionTranslation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOptionTranslation whereGroupTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOptionTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOptionTranslation whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOptionTranslation whereOptionTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOptionTranslation whereProductOptionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductOptionTranslation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ProductOptionTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['group_title', 'option_title'];
}
