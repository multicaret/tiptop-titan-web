<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ProductTranslation
 *
 * @property int $id
 * @property int $product_id
 * @property string $locale
 * @property string $title
 * @property string|null $description
 * @property string|null $excerpt
 * @property string|null $notes
 * @property string|null $custom_banner_text
 * @property string|null $unit_text
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static Builder|ProductTranslation newModelQuery()
 * @method static Builder|ProductTranslation newQuery()
 * @method static Builder|ProductTranslation query()
 * @method static Builder|ProductTranslation whereCreatedAt($value)
 * @method static Builder|ProductTranslation whereCustomBannerText($value)
 * @method static Builder|ProductTranslation whereDescription($value)
 * @method static Builder|ProductTranslation whereExcerpt($value)
 * @method static Builder|ProductTranslation whereId($value)
 * @method static Builder|ProductTranslation whereLocale($value)
 * @method static Builder|ProductTranslation whereNotes($value)
 * @method static Builder|ProductTranslation whereProductId($value)
 * @method static Builder|ProductTranslation whereTitle($value)
 * @method static Builder|ProductTranslation whereUnitText($value)
 * @method static Builder|ProductTranslation whereUpdatedAt($value)
 * @mixin Eloquent
 */
class ProductTranslation extends Model
{
    protected $fillable = [
        'title',
        'description',
        'excerpt',
        'notes',
        'custom_banner_text',
        'unit_text',
    ];
}
