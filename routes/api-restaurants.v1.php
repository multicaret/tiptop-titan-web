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
         Route::post('restaurants/{restaurant}/toggle-activity', 'BranchController@toggleActivity');
         Route::get('restaurants/{restaurant}/categories', 'BranchController@categories');
         Route::put('restaurants/{restaurant}/products/{product}', 'ProductController@update');

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
         Route::get('restaurants/{restaurant}/orders', 'OrderController@index');
         Route::post('restaurants/{restaurant}/orders', 'OrderController@store');
         Route::post('restaurants/{restaurant}/orders/{order}/delete', 'OrderController@destroy');
     });


