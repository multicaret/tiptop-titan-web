<?php

namespace App\Models;

use App\Traits\HasMediaTrait;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Spatie\MediaLibrary\HasMedia;

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
