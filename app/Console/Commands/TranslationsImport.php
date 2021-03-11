<?php

namespace App\Console\Commands;

use App\Models\Translation;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Translation\FileLoader;


class TranslationsImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translation:import {--update-only=0}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import all translations from files to database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $forUpdateOnly = $this->option('update-only');
        $allDefaultTranslations = [];
        $defaultLocale = localization()->getDefaultLocale();
        $this->info('forUpdateOnly: ' . $forUpdateOnly);
        $this->loadedTranslationsByLocale($defaultLocale, $allDefaultTranslations);

        if (!$forUpdateOnly) {
            $this->setDefaultLocaleTranslations($defaultLocale, $allDefaultTranslations);
        } else {
            $this->setAnotherLocaleTranslations($defaultLocale, $allDefaultTranslations);
        }
        foreach (localization()->getSupportedLocalesKeys() as $localesKey) {
            if ($localesKey !== $defaultLocale) {
                $allDefaultTranslations = [];
                $this->loadedTranslationsByLocale($localesKey, $allDefaultTranslations);
                $this->setAnotherLocaleTranslations($localesKey, $allDefaultTranslations);
            }
        }
        $this->info('Import is finished 🤪.');

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


    private function containsOnlyNull($input)
    {
        return empty(array_filter($input, function ($a) {
            return $a !== null;
        }));
    }


    private function array_undot($dottedArray)
    {
        $array = array();
        foreach ($dottedArray as $key => $value) {
            array_set($array, $key, $value);
        }
        return $array;
    }


    private function is_multi($a)
    {
        foreach ($a as $v) {
            if (is_array($v)) return true;
        }
        return false;
    }

    /**
     * @param string $defaultLocale
     * @param array $allTranslations
     */
    private function loadedTranslationsByLocale(string $defaultLocale, array &$allTranslations): void
    {
        $directoryIsExists = \File::exists(app()['path.lang'] . "/{$defaultLocale}/");
        if ($directoryIsExists) {
            $allFiles = \File::files(app()['path.lang'] . "/{$defaultLocale}/");
            $allGroupsFiles = collect($allFiles)->map(function ($file) {
                return pathinfo($file, PATHINFO_FILENAME);
            })->toArray();

            // Todo: Load json files
            $loader = new FileLoader(app()['files'], app()['path.lang']);
            foreach ($allGroupsFiles as $groupsName) {
                $this->info("$defaultLocale: -> $groupsName");
                $loadedGroup = $loader->load($defaultLocale, $groupsName);
                $hasMulti = $this->is_multi($loadedGroup);
                if ($hasMulti) {
                    $allTranslations[$groupsName] = Arr::dot($loadedGroup);
                } else {
                    $allTranslations[$groupsName] = $loadedGroup;
                }
            }
        }
    }

    private function setDefaultLocaleTranslations(string $defaultLocale, array $allTranslations): void
    {
        foreach ($allTranslations as $groupsName => $translations) {
            foreach ($translations as $translationKey => $translation) {
                if (!empty($translationKey) && ! is_array($translation)) {
                    Translation::create([
                        'key' => $translationKey,
                        'group' => $groupsName,
                        $defaultLocale => ['value' => $translation],
                    ]);
                }
            }
        }
    }

    private function setAnotherLocaleTranslations(string $localesKey, array $allTranslations): void
    {
        foreach ($allTranslations as $groupsName => $translations) {
            foreach ($translations as $translationKey => $translation) {
                if (!empty($translationKey) && !is_array($translation)) {
                    $translationModel = Translation::where('key', $translationKey)->first();
                    if (is_null($translationModel)) {
                        Translation::create([
                            'key' => $translationKey,
                            'group' => $groupsName,
                            $localesKey => ['value' => $translation],
                        ]);
                    } else {
                        $translationModel->translateOrNew($localesKey)->value = $translation;
                        $translationModel->push();
                    }
                }
            }
        }
    }

}
