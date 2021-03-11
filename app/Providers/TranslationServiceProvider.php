<?php

namespace App\Providers;

use Illuminate\Support\Carbon;
use Astrotomic\Translatable\Locales;
use Illuminate\Support\ServiceProvider;
use Illuminate\Translation\Translator;
use App\Utilities\TranslationLoader;

class TranslationServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Carbon::setLocale(localization()->getCurrentLocale());
        $this->setupTranslatable();
    }

    private function setupTranslatable()
    {
        $this->app['config']->set('translatable.use_fallback', true);
        $this->app['config']->set('translatable.fallback_locale', localization()->getDefaultLocale());
        $this->app['config']->set('translatable.locales', localization()->getSupportedLocalesKeys());
        $this->app->singleton('translatable.locales', Locales::class);
        $this->app->singleton(Locales::class);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerLoader();
        $this->registerTranslator();

    }

    private function registerLoader()
    {
        $this->app->singleton('translation.loader', function ($app) {
            return new TranslationLoader($app['files'], $app['path.lang']);
        });
    }

    private function registerTranslator()
    {
        $this->app->singleton('translator', function ($app) {
            $loader = $app['translation.loader'];
            $locale = $app['config']['app.locale'];

            $trans = new Translator($loader, $locale);

            $trans->setFallback($app['config']['app.fallback_locale']);

            return $trans;
        });
    }
}
