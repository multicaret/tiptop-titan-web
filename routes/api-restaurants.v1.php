<?php


/* Unprotected Endpoints */
Route::middleware('throttle:15')
     ->group(function () {
         /* auth related */
         Route::post('login', 'Auth\AuthController@login');
         /* boot related */
         Route::get('/boot', 'HomeController@boot');
         Route::get('/', 'HomeController@root');

         Route::get('categories/{groceryCategory}/products', 'CategoryController@products');
         Route::get('products/{id}', 'ProductController@show');

         /* auth related */
         Route::post('login', 'Auth\AuthController@login');
//         Route::post('auth/{provider}', 'Auth\SocialiteController@handleProvider');

         /* generals */
         Route::get('privacy-policy', 'PostController@privacy');
         Route::get('about-us', 'PostController@aboutUs');;
         Route::get('support', 'SettingController@support');
     });

/* Protected Endpoints */
Route::middleware('auth:sanctum')
     ->group(function () {
         Route::post('logout', 'Auth\AuthController@logout');

         // Orders
         Route::get('orders/food', 'OrderController@indexFood');
         Route::get('orders/create', 'OrderController@create');
         Route::post('orders', 'OrderController@store');
         Route::post('orders/{order}/delete', 'OrderController@destroy');
     });


