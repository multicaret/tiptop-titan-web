<?php

namespace App\Models;

use App\Traits\HasMediaTrait;
use Astrotomic\Translatable\Translatable;
use Eloquent;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;

/**
 * App\Models\Preference
 *
 * @property int $id
 * @property string $key
 * @property string $type
 * @property string|null $notes
 * @property string|null $group_name
 * @property int|null $order_column
 * @property string|null $icon
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read MediaCollection|\Spatie\MediaLibrary\MediaCollections\Models\Media[] $media
 * @property-read int|null $media_count
 * @property-read \App\Models\PreferenceTranslation|null $translation
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PreferenceTranslation[] $translations
 * @property-read int|null $translations_count
 * @method static Builder|Preference listsTranslations(string $translationField)
 * @method static Builder|Preference newModelQuery()
 * @method static Builder|Preference newQuery()
 * @method static Builder|Preference notTranslatedIn(?string $locale = null)
 * @method static Builder|Preference orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static Builder|Preference orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static Builder|Preference orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static Builder|Preference query()
 * @method static Builder|Preference sections()
 * @method static Builder|Preference translated()
 * @method static Builder|Preference translatedIn(?string $locale = null)
 * @method static Builder|Preference whereCreatedAt($value)
 * @method static Builder|Preference whereGroupName($value)
 * @method static Builder|Preference whereIcon($value)
 * @method static Builder|Preference whereId($value)
 * @method static Builder|Preference whereKey($value)
 * @method static Builder|Preference whereNotes($value)
 * @method static Builder|Preference whereOrderColumn($value)
 * @method static Builder|Preference whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static Builder|Preference whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static Builder|Preference whereType($value)
 * @method static Builder|Preference whereUpdatedAt($value)
 * @method static Builder|Preference withTranslation()
 * @mixin Eloquent
 * @noinspection PhpFullyQualifiedNameUsageInspection
 * @noinspection PhpUnnecessaryFullyQualifiedNameInspection
 */
class Preference extends Model implements HasMedia
{
    use HasMediaTrait;
    use Translatable;

    protected $with = ['translations'];
    protected $translatedAttributes = ['value'];
    protected $fillable = ['key', 'value', 'notes'];


    public function scopeSections()
    {
        return $this->where('type', 'section');
    }


    public static function retrieve($key)
    {
        return self::getAll()->where('key', $key)->first();
    }

    /**
     * @param                   $key
     * @param  array|string|null  $replace
     * @param  array|string|null  $replaceWith
     *
     * @return mixed
     */
    public static function retrieveValue($key, $replace = [], $replaceWith = [])
    {
        $preferencesItem = self::retrieve($key);
        if ( ! is_null($preferencesItem)) {
            $value = $preferencesItem->value;
            if ( ! empty($replace) && ! empty($replaceWith)) {
                $replace = is_array($replace) ? $replace : [$replace];
                $replaceWith = is_array($replaceWith) ? $replaceWith : [$replaceWith];
                if (count($replace) === count($replaceWith)) {
                    foreach ($replace as $index => $replaceItem) {
                        $value = str_replace($replaceItem, $replaceWith[$index], $value);
                    }
                }
            }

            return $value;
        }

        return 'NOT FOUND IN PREFERENCES, ( '.$key.' )';
    }

    /**
     * @return Collection
     * @throws Exception
     */
    public static function getAll(): Collection
    {
        return cache()->tags('preferences')->rememberForever(localization()->getCurrentLocale().'.preferences',
            function () {
                $preferences = Preference::all();
                $preferences->map(function (Preference $preference) {
                    if ($preference->type == 'file') {
                        $preference->value = $preference->getValue();
                    }
                });

                return $preferences;
            });
    }

    /**
     * @return Collection
     * @throws Exception
     */
    public static function getAllPluckValueKey(): Collection
    {
        return self::getAll()->pluck('value', 'key');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('file')->singleFile();
    }

    public function getValue()
    {
        $value = $this->value;
        if ($this->type == 'file') {
            if (is_string($this->value) &&
                ((int) $this->value) == 0) {
                $value = url($this->value);
            } elseif ( ! is_null($file = $this->getFirstMedia('file'))) {
                $value = $file->getFullUrl();
            }
        }

        return $value;
    }
}
