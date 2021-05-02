<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
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
 * @method static Builder|TaxonomyTranslation newModelQuery()
 * @method static Builder|TaxonomyTranslation newQuery()
 * @method static Builder|TaxonomyTranslation query()
 * @method static Builder|TaxonomyTranslation whereDescription($value)
 * @method static Builder|TaxonomyTranslation whereId($value)
 * @method static Builder|TaxonomyTranslation whereIsAutoInserted($value)
 * @method static Builder|TaxonomyTranslation whereLocale($value)
 * @method static Builder|TaxonomyTranslation whereTaxonomyId($value)
 * @method static Builder|TaxonomyTranslation whereTitle($value)
 * @mixin Eloquent
 * @noinspection PhpFullyQualifiedNameUsageInspection
 * @noinspection PhpUnnecessaryFullyQualifiedNameInspection
 */
class TaxonomyTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['title', 'description'];
    protected $casts = ['is_auto_inserted' => 'boolean'];


}
