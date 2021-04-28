<?php

namespace App\Models;

use App\Traits\HasStatuses;
use Astrotomic\Translatable\Translatable;
use Eloquent;
use File;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\Translation
 *
 * @property int $id
 * @property int $status
 * @property string $group
 * @property string $key
 * @property int|null $order_column
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read bool $is_active
 * @property-read bool $is_inactive
 * @property-read array $status_js
 * @property-read mixed $status_name
 * @property-read \App\Models\TranslationTranslation|null $translation
 * @property-read Collection|\App\Models\TranslationTranslation[] $translations
 * @property-read int|null $translations_count
 * @method static Builder|Translation active()
 * @method static Builder|Translation draft()
 * @method static Builder|Translation group($groupName)
 * @method static Builder|Translation inactive()
 * @method static Builder|Translation listsTranslations(string $translationField)
 * @method static Builder|Translation newModelQuery()
 * @method static Builder|Translation newQuery()
 * @method static Builder|Translation notActive()
 * @method static Builder|Translation notTranslatedIn(?string $locale = null)
 * @method static Builder|Translation orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static Builder|Translation orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static Builder|Translation orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static Builder|Translation query()
 * @method static Builder|Translation translated()
 * @method static Builder|Translation translatedIn(?string $locale = null)
 * @method static Builder|Translation whereCreatedAt($value)
 * @method static Builder|Translation whereGroup($value)
 * @method static Builder|Translation whereId($value)
 * @method static Builder|Translation whereKey($value)
 * @method static Builder|Translation whereOrderColumn($value)
 * @method static Builder|Translation whereStatus($value)
 * @method static Builder|Translation whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static Builder|Translation whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static Builder|Translation whereUpdatedAt($value)
 * @method static Builder|Translation withTranslation()
 * @mixin Eloquent
 */
class Translation extends Model
{
    use HasStatuses;
    use Translatable;


    public const STATUS_DRAFT = 1;
    public const STATUS_ACTIVE = 2;
    public const STATUS_INACTIVE = 3;
    public const IGNORE_FILENAME = 'ignore';

    protected $fillable = ['key', 'group', 'value'];
    protected $with = ['translations'];
    protected $translatedAttributes = ['value'];

    public function scopeGroup($query, $groupName)
    {
        return $query->where('group', $groupName);
    }

    public static function getTranslationGroupsFromFiles(): array
    {
        $allGroupsFiles = [];
        $defaultLocale = localization()->getDefaultLocale();
        $directoryIsExists = File::exists(app()['path.lang']."/{$defaultLocale}/");
        if ($directoryIsExists) {
            $allFiles = File::files(app()['path.lang']."/{$defaultLocale}/");
            $allGroupsFiles = collect($allFiles)->map(function ($file) {
                if (pathinfo($file, PATHINFO_FILENAME) !== self::IGNORE_FILENAME) {
                    return pathinfo($file, PATHINFO_FILENAME);
                }
            })->filter(fn($filename) => ! is_null($filename))->toArray();
        }

        return $allGroupsFiles;
    }
}
