<?php

namespace App\Models;

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
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTranslation whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTranslation whereExcerpt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTranslation whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTranslation whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTranslation whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTranslation whereTitle($value)
 * @mixin \Eloquent
 */
class ProductTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['title', 'description', 'excerpt', 'notes'];
}
