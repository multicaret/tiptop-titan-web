<?php

namespace App\Providers;

use Blade;
use Illuminate\Support\ServiceProvider;

class BladeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::directive('preferences', function ($arguments) {
            $arguments = explode(',', str_replace(['(', ')', ' ', "'", "\""], '', $arguments));
            $key = $arguments[0];
            $isStrippingTags = count($arguments) > 1 && $arguments[1];
            if ($isStrippingTags) {
                return '<?php echo strip_tags(@$appPreferences["'.$key.'"]); ?>';
            }

            return '<?php echo @$appPreferences["'.$key.'"]; ?>';
        });

        Blade::directive('active', function ($expression) {
            return "<?php echo (request()->getUri() == {$expression} || request()->is({$expression})) ? 'active' : null; ?>";
        });
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
