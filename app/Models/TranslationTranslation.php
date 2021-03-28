<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\TranslationTranslation
 *
 * @property int $id
 * @property int $translation_id
 * @property string $locale
 * @property string|null $value
 * @method static \Illuminate\Database\Eloquent\Builder|TranslationTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TranslationTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TranslationTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|TranslationTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TranslationTranslation whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TranslationTranslation whereTranslationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TranslationTranslation whereValue($value)
 * @mixin \Eloquent
 */
class TranslationTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['value'];
}
