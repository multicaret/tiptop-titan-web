<?php

namespace App\Utilities;

use App\Models\Translation;
use Illuminate\Support\Facades\Cache;
use Illuminate\Translation\FileLoader;

class TranslationLoader extends FileLoader
{
    /**
     * Get translation file paths.
     *
     * @return array
     */
    public function paths()
    {
        return array_merge([$this->path], $this->hints);
    }

    /**
     * Load the messages for the given locale.
     *
     * @param string $locale
     * @param string $group
     * @param string $namespace
     * @return array
     */
    public function load($locale, $group, $namespace = null): array
    {
        return Cache::tags('translations')
            ->rememberForever(md5("translation_loader.{$locale}.{$group}.{$namespace}"), function () use ($locale, $group, $namespace) {
                return $this->getTranslations($locale, $group, $namespace);
            });
    }

    /**
     * Get file and database translations.
     *
     * @param string $locale
     * @param string $group
     * @param string $namespace
     * @return array
     */
    private function getTranslations($locale, $group, $namespace)
    {
        $databaseTranslations = $this->databaseTranslations($locale, $group, $namespace);
        $fileTranslations = $this->fileTranslations($locale, $group, $namespace);
        return array_replace_recursive(
            $this->breakDot($fileTranslations),
            $this->breakDot($databaseTranslations)
        );
    }

    private function breakDot($translations)
    {
        $array = [];

        foreach ($translations as $key => $value) {
            if (strpos($key, '*') === false) {
                \Arr::set($array, $key, $value);
            } else {
                $array[$key] = $value;
            }
        }
        return $array;
    }

    /**
     * Get file translations.
     *
     * @param string $locale
     * @param string $group
     * @param string $namespace
     * @return array
     */
    private function fileTranslations($locale, $group, $namespace)
    {
        return parent::load($locale,$group, $namespace);
    }

    /**
     * Get database translations.
     *
     * @param string $locale
     * @param string $group
     * @param string $namespace
     * @return array
     */
    private function databaseTranslations($locale, $group, $namespace)
    {
        return Translation::group($group)
            ->get()
            ->mapWithKeys(function ($translation) use ($locale, $group) {
                return [$translation->key => optional($translation->getTranslation($locale))->value];
            })->all();
    }
}
