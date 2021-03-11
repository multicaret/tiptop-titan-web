<?php

namespace App\Providers;

use App\Models\Post;
use App\Models\Taxonomy;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class SlugServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        /*Route::bind('taxonomy', function ($slug) {
            return Taxonomy::whereHas('translations', function ($query) use ($slug) {
                $query->where('locale', localization()->getCurrentLocale())->where('slug', $slug);
            })->firstOrFail();
        });*/

        $routeModelBindingsUsingUUIDs = [
            'post' => Post::class,
            'page' => Post::class,
            'category' => Taxonomy::class,
            'tag' => Taxonomy::class,
        ];


        foreach ($routeModelBindingsUsingUUIDs as $routeVariable => $class) {
            Route::bind($routeVariable, function ($uuid) use ($class) {
                if (strpos($uuid, '-') >= 0) {
                    $uuidExploded = explode('-', $uuid);
                    $uuid = $uuidExploded[count($uuidExploded) - 1];
                }

                return $class::where('uuid', $uuid)->firstOrFail();
            });
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
