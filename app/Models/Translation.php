<?php

namespace App\Models;

use App\Traits\HasStatuses;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Translation
 *
 * @property int $id
 * @property int $status
 * @property string $group
 * @property string $key
 * @property int|null $order_column
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $is_published
 * @property-read mixed $status_name
 * @property-read \App\Models\TranslationTranslation|null $translation
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\TranslationTranslation[] $translations
 * @property-read int|null $translations_count
 * @method static \Illuminate\Database\Eloquent\Builder|Translation draft()
 * @method static \Illuminate\Database\Eloquent\Builder|Translation group($groupName)
 * @method static \Illuminate\Database\Eloquent\Builder|Translation inactive()
 * @method static \Illuminate\Database\Eloquent\Builder|Translation incomplete()
 * @method static \Illuminate\Database\Eloquent\Builder|Translation listsTranslations(string $translationField)
 * @method static \Illuminate\Database\Eloquent\Builder|Translation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Translation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Translation notPublished()
 * @method static \Illuminate\Database\Eloquent\Builder|Translation notTranslatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Translation orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Translation orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Translation orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|Translation published()
 * @method static \Illuminate\Database\Eloquent\Builder|Translation query()
 * @method static \Illuminate\Database\Eloquent\Builder|Translation translated()
 * @method static \Illuminate\Database\Eloquent\Builder|Translation translatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Translation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Translation whereGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Translation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Translation whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Translation whereOrderColumn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Translation whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Translation whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static \Illuminate\Database\Eloquent\Builder|Translation whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Translation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Translation withTranslation()
 * @mixin \Eloquent
 */
class Translation extends Model
{
    use Translatable,
        HasStatuses;

    const STATUS_INCOMPLETE = 0;
    const STATUS_DRAFT = 1;
    const STATUS_PUBLISHED = 2;
    const STATUS_INACTIVE = 3;

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
        $directoryIsExists = \File::exists(app()['path.lang']."/{$defaultLocale}/");
        if ($directoryIsExists) {
            $allFiles = \File::files(app()['path.lang']."/{$defaultLocale}/");
            $allGroupsFiles = collect($allFiles)->map(function ($file) {
                return pathinfo($file, PATHINFO_FILENAME);
            })->toArray();
        }

        return $allGroupsFiles;
    }
}
