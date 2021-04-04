<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\PreferenceTranslation
 *
 * @property int $id
 * @property int $preference_id
 * @property string $locale
 * @property string|null $value
 * @method static Builder|PreferenceTranslation newModelQuery()
 * @method static Builder|PreferenceTranslation newQuery()
 * @method static Builder|PreferenceTranslation query()
 * @method static Builder|PreferenceTranslation whereId($value)
 * @method static Builder|PreferenceTranslation whereLocale($value)
 * @method static Builder|PreferenceTranslation wherePreferenceId($value)
 * @method static Builder|PreferenceTranslation whereValue($value)
 * @mixin Eloquent
 */
class PreferenceTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['value', 'notes'];
}
