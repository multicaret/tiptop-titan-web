<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\\Http\\Controllers'; //This allows you to keep using the old routing system in Laravel  <aoo8

    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->mapApiVersion1Routes();
        $this->mapRestaurantApiVersion1Routes();
        $this->mapWebAdminRoutes();
        $this->mapWebDashboardRoutes();
        $this->mapAjaxRoutes();
        $this->mapWebRoutes();
        $this->mapTookanRoutes();
    }


    /**
     * Define the "admin" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebAdminRoutes()
    {
        Route::middleware([
            'web',
            'auth:web',
            'force-locale',
            'check-suspension',
//            'localization-session-redirect',
//            'localization-redirect'
        ])
             ->prefix(/*localization()->setLocale() .*/ '/admin')
             ->as('admin.')
             ->namespace($this->namespace.'\Admin')
             ->group(base_path('routes/admin.php'));
    }

    /**
     * Define the "dashboard" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebDashboardRoutes()
    {
        Route::middleware([
            'web',
            'auth',
//            'force-locale',
//            'localization-session-redirect',
//            'localization-redirect'
        ])
             ->prefix(/*localization()->setLocale() .*/ '/dashboard')
             ->as('dashboard.')
             ->namespace($this->namespace.'\Dashboard')
             ->group(base_path('routes/dashboard.php'));
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware([
            'web',
            'localization-session-redirect',
            'localization-redirect'
        ])
             ->prefix(localization()->setLocale())
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "ajax" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapAjaxRoutes()
    {
        Route::middleware([
            'web',
//            'role:admin,super',
            'localization-session-redirect',
            'localization-redirect'
        ])
             ->prefix(localization()->setLocale().'/ajax')
             ->name('ajax.')
             ->namespace($this->namespace.'\Ajax')
             ->group(base_path('routes/ajax.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiVersion1Routes()
    {
        Route::prefix(localization()->setLocale().'/api/v1')
             ->name('api.v1.')
             ->middleware(['api', 'localization-redirect'])
             ->namespace($this->namespace.'\Api\V1')
             ->group(base_path('routes/api.v1.php'));
    }


    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapRestaurantApiVersion1Routes()
    {
        Route::prefix(localization()->setLocale().'/api/restaurants/v1')
             ->name('api.restaurants.v1.')
             ->middleware(['api', 'localization-redirect'])
             ->namespace($this->namespace.'\Api\Restaurants\V1')
             ->group(base_path('routes/api-restaurants.v1.php'));
    }

    /**
     * Define the "webhooks" routes for tookan.
     *
     *
     * @return void
     */
    protected function mapTookanRoutes()
    {
        Route::prefix('tookan')
             ->name('tookan.')
             ->namespace($this->namespace.'\Tookan')
             ->group(base_path('routes/tookan.php'));
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }
}
