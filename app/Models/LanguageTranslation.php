<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\LanguageTranslation
 *
 * @property int $id
 * @property int $language_id
 * @property string $name
 * @property string $locale
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LanguageTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LanguageTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LanguageTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LanguageTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LanguageTranslation whereLanguageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LanguageTranslation whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LanguageTranslation whereName($value)
 * @mixin \Eloquent
 */
class LanguageTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['name'];
}
