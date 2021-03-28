<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\TaxonomyTranslation
 *
 * @property int $id
 * @property int $taxonomy_id
 * @property string $locale
 * @property string $title
 * @property string|null $description
 * @property bool $is_auto_inserted
 * @method static \Illuminate\Database\Eloquent\Builder|TaxonomyTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TaxonomyTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TaxonomyTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|TaxonomyTranslation whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaxonomyTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaxonomyTranslation whereIsAutoInserted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaxonomyTranslation whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaxonomyTranslation whereTaxonomyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaxonomyTranslation whereTitle($value)
 * @mixin \Eloquent
 */
class TaxonomyTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['title', 'description'];
    protected $casts = ['is_auto_inserted' => 'boolean'];


}
