<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\TranslationTranslation
 *
 * @property int $id
 * @property int $translation_id
 * @property string $locale
 * @property string|null $value
 * @method static Builder|TranslationTranslation newModelQuery()
 * @method static Builder|TranslationTranslation newQuery()
 * @method static Builder|TranslationTranslation query()
 * @method static Builder|TranslationTranslation whereId($value)
 * @method static Builder|TranslationTranslation whereLocale($value)
 * @method static Builder|TranslationTranslation whereTranslationId($value)
 * @method static Builder|TranslationTranslation whereValue($value)
 * @mixin Eloquent
 * @noinspection PhpFullyQualifiedNameUsageInspection
 * @noinspection PhpUnnecessaryFullyQualifiedNameInspection
 */
class TranslationTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['value'];
}
