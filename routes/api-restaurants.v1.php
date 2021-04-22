<?php


/* Unprotected Endpoints */
Route::middleware('throttle:15')
     ->group(function () {
         /* auth related */
         Route::post('login', 'Auth\AuthController@login');
//       Route::post('auth/{provider}', 'Auth\SocialiteController@handleProvider');

         /* boot related */
         Route::get('/boot', 'HomeController@boot');
         Route::get('/', 'HomeController@root');

        /*restaurant related*/
         Route::get('restaurants/{restaurant}/edit', 'BranchController@edit');
         Route::put('restaurants/{restaurant}', 'BranchController@update');

         Route::get('categories/{groceryCategory}/products', 'CategoryController@products');
         Route::get('products/{id}', 'ProductController@show');

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
         Route::post('orders', 'OrderController@store');
         Route::post('orders/{order}/delete', 'OrderController@destroy');
     });


