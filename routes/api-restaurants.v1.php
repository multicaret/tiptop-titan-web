<?php


/* Unprotected Endpoints */
Route::middleware('throttle:15')
     ->group(function () {
         /* auth related */
         Route::post('login', 'Auth\AuthController@login');
//       Route::post('auth/{provider}', 'Auth\SocialiteController@handleProvider');

         /* boot related */
         Route::get('/boot', 'HomeController@boot');

         /* generals */
         Route::get('privacy-policy', 'PostController@privacy');
         Route::get('about-us', 'PostController@aboutUs');
         Route::get('support', 'SettingController@support');
     });

/* Protected Endpoints */
Route::middleware('auth:sanctum')
     ->group(function () {
         Route::post('logout', 'Auth\AuthController@logout');

         Route::get('/', 'HomeController@root');
         Route::get('notifications', 'NotificationController@index');

         Route::group(['prefix' => 'restaurants/{restaurant}'], function () {
             Route::get('edit', 'BranchController@edit');
             Route::post('/', 'BranchController@update');
             Route::post('toggle-activity', 'BranchController@toggleActivity');
             Route::get('categories', 'BranchController@categories');
             Route::post('products/{product}', 'ProductController@update');

             // Orders
             Route::get('orders', 'OrderController@index');
             Route::get('orders/{order}', 'OrderController@show');
             Route::post('orders', 'OrderController@update');
             Route::post('orders/{order}/delete', 'OrderController@destroy');
         });
     });

