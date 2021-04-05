<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CountryTranslation
 *
 * @property int $id
 * @property int $country_id
 * @property string $name
 * @property string $locale
 * @property string|null $slug
 * @method static Builder|CountryTranslation newModelQuery()
 * @method static Builder|CountryTranslation newQuery()
 * @method static Builder|CountryTranslation query()
 * @method static Builder|CountryTranslation whereCountryId($value)
 * @method static Builder|CountryTranslation whereId($value)
 * @method static Builder|CountryTranslation whereLocale($value)
 * @method static Builder|CountryTranslation whereName($value)
 * @method static Builder|CountryTranslation whereSlug($value)
 * @mixin Eloquent
 */
class CountryTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['name', 'slug'];

}
