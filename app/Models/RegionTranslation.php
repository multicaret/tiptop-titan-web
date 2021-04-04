<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\RegionTranslation
 *
 * @property int $id
 * @property int $region_id
 * @property string $name
 * @property string $locale
 * @property string|null $slug
 * @method static Builder|RegionTranslation newModelQuery()
 * @method static Builder|RegionTranslation newQuery()
 * @method static Builder|RegionTranslation query()
 * @method static Builder|RegionTranslation whereId($value)
 * @method static Builder|RegionTranslation whereLocale($value)
 * @method static Builder|RegionTranslation whereName($value)
 * @method static Builder|RegionTranslation whereRegionId($value)
 * @method static Builder|RegionTranslation whereSlug($value)
 * @mixin Eloquent
 */
class RegionTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['name', 'slug'];

}
