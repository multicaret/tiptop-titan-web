<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\PreferenceTranslation
 *
 * @property int $id
 * @property int $preference_id
 * @property string $locale
 * @property string|null $value
 * @method static \Illuminate\Database\Eloquent\Builder|PreferenceTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PreferenceTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PreferenceTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|PreferenceTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PreferenceTranslation whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PreferenceTranslation wherePreferenceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PreferenceTranslation whereValue($value)
 * @mixin \Eloquent
 */
class PreferenceTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['value', 'notes'];
}
