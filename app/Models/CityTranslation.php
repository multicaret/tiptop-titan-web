<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CityTranslation
 *
 * @property int $id
 * @property int $city_id
 * @property string $name
 * @property string|null $description
 * @property string $locale
 * @property string|null $slug
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CityTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CityTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CityTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CityTranslation whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CityTranslation whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CityTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CityTranslation whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CityTranslation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CityTranslation whereSlug($value)
 * @mixin \Eloquent
 */
class CityTranslation extends Model
{

    public $timestamps = false;
    protected $fillable = ['name', 'slug'];

}
