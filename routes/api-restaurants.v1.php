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
             Route::get('/', 'BranchController@show');
             Route::post('/', 'BranchController@update');
             Route::get('edit', 'BranchController@edit');
             Route::post('toggle-status', 'BranchController@toggleStatus');
             Route::post('products/{product}', 'ProductController@update');
             Route::post('products/{product}/toggle-status', 'ProductController@toggleStatus');

             // Orders
             Route::get('orders', 'OrderController@index');
             Route::get('orders/{order}', 'OrderController@show');
             Route::post('orders/{order}', 'OrderController@update');

             //Jet Orders
             Route::get('jet/orders', 'JetOrderController@index');
             Route::get('jet/orders/create', 'JetOrderController@create');
             Route::post('jet/orders/delivery-fees', 'JetOrderController@getDeliveryFee');
             Route::get('jet/orders/{order}', 'JetOrderController@show');
             Route::post('jet/orders', 'JetOrderController@store');

         });
     });


