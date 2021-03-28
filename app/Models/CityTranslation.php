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
 * @method static \Illuminate\Database\Eloquent\Builder|CityTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CityTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CityTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|CityTranslation whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CityTranslation whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CityTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CityTranslation whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CityTranslation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CityTranslation whereSlug($value)
 * @mixin \Eloquent
 */
class CityTranslation extends Model
{

    public $timestamps = false;
    protected $fillable = ['name', 'slug'];

}
