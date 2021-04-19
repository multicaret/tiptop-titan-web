<?php

namespace App\Providers;

use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();
        Validator::extend('recaptcha', 'App\Rules\ReCaptcha@passes');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Carbon::setLocale($this->app->getLocale());
        Sanctum::ignoreMigrations();
        if ($this->app->environment() !== 'production') {
            $this->app->register(IdeHelperServiceProvider::class);
        }
    }
}
