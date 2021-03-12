<?php

namespace App\Models;

use App\Traits\HasMediaTrait;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Spatie\MediaLibrary\HasMedia;

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
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Media[] $media
 * @property-read int|null $media_count
 * @property-read \App\Models\PreferenceTranslation|null $translation
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PreferenceTranslation[] $translations
 * @property-read int|null $translations_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Preference listsTranslations($translationField)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Preference newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Preference newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Preference notTranslatedIn($locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Preference orWhereTranslation($translationField, $value, $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Preference orWhereTranslationLike($translationField, $value, $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Preference orderByTranslation($translationField, $sortMethod = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Preference query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Preference sections()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Preference translated()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Preference translatedIn($locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Preference whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Preference whereGroupName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Preference whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Preference whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Preference whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Preference whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Preference whereOrderColumn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Preference whereTranslation($translationField, $value, $locale = null, $method = 'whereHas', $operator = '=')
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Preference whereTranslationLike($translationField, $value, $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Preference whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Preference whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Preference withTranslation()
 * @mixin \Eloquent
 */
class Preference extends Model implements HasMedia
{
    use Translatable, HasMediaTrait;

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
     * @throws \Exception
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
     * @return \Illuminate\Support\Collection
     * @throws \Exception
     */
    public static function getAllPluckValueKey(): \Illuminate\Support\Collection
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
