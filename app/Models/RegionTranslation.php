<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\RegionTranslation
 *
 * @property int $id
 * @property int $region_id
 * @property string $name
 * @property string $locale
 * @property string|null $slug
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RegionTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RegionTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RegionTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RegionTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RegionTranslation whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RegionTranslation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RegionTranslation whereRegionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RegionTranslation whereSlug($value)
 * @mixin \Eloquent
 */
class RegionTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['name', 'slug'];

}
