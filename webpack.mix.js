const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js([
        // 'resources/assets/js/bootstrap.js',
        'resources/assets/js/app.js'
    ],
    'public/js/');

mix.sass('resources/assets/sass/app.scss', 'public/css');
mix.sass('resources/assets/sass/app-rtl.scss', 'public/css');

/*mix.postCss('resources/css/app.css', 'public/css', [
    //
]);*/

/*Admin Panel Related*/
mix.js([
        'resources/assets/js/bootstrap.js',
        'resources/assets/js/libs/jquery.fileuploader.js',
        'resources/assets/js/admin/fileuploader.init.js',
        'resources/assets/js/admin/inits.js',
        'resources/assets/js/admin/main.js',
    ],
    'public/js/admin.js');

mix.sass('resources/assets/sass/admin.scss', 'public/css');

mix.copyDirectory('resources/assets/images', 'public/images');

if (mix.inProduction()) {
    mix.version();
}
