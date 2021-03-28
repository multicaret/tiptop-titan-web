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
 * @method static \Illuminate\Database\Eloquent\Builder|CountryTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CountryTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CountryTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|CountryTranslation whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CountryTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CountryTranslation whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CountryTranslation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CountryTranslation whereSlug($value)
 * @mixin \Eloquent
 */
class CountryTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['name', 'slug'];

}
