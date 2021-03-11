<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CountryTranslation
 *
 * @property int $id
 * @property int $country_id
 * @property string $name
 * @property string $locale
 * @property string|null $slug
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CountryTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CountryTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CountryTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CountryTranslation whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CountryTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CountryTranslation whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CountryTranslation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CountryTranslation whereSlug($value)
 * @mixin \Eloquent
 */
class CountryTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['name', 'slug'];

}
