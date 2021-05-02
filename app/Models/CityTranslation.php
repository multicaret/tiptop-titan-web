<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
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
 * @method static Builder|CityTranslation newModelQuery()
 * @method static Builder|CityTranslation newQuery()
 * @method static Builder|CityTranslation query()
 * @method static Builder|CityTranslation whereCityId($value)
 * @method static Builder|CityTranslation whereDescription($value)
 * @method static Builder|CityTranslation whereId($value)
 * @method static Builder|CityTranslation whereLocale($value)
 * @method static Builder|CityTranslation whereName($value)
 * @method static Builder|CityTranslation whereSlug($value)
 * @mixin Eloquent
 * @noinspection PhpFullyQualifiedNameUsageInspection
 * @noinspection PhpUnnecessaryFullyQualifiedNameInspection
 */
class CityTranslation extends Model
{

    public $timestamps = false;
    protected $fillable = ['name', 'slug'];

}
