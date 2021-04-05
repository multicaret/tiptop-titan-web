<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\LanguageTranslation
 *
 * @property int $id
 * @property int $language_id
 * @property string $name
 * @property string $locale
 * @method static Builder|LanguageTranslation newModelQuery()
 * @method static Builder|LanguageTranslation newQuery()
 * @method static Builder|LanguageTranslation query()
 * @method static Builder|LanguageTranslation whereId($value)
 * @method static Builder|LanguageTranslation whereLanguageId($value)
 * @method static Builder|LanguageTranslation whereLocale($value)
 * @method static Builder|LanguageTranslation whereName($value)
 * @mixin Eloquent
 */
class LanguageTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['name'];
}
