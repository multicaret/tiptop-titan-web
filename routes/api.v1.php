<?php


/* Unprotected Endpoints */
Route::middleware('throttle:15')
     ->group(function () {
         /* catalog related */
         Route::get('/boot', 'HomeController@boot');
         Route::get('/', 'HomeController@root');
         Route::get('/home', 'HomeController@index');

         Route::get('categories/{groceryCategory}/products', 'CategoryController@products');
         Route::get('products/{id}', 'ProductController@show');
         Route::get('search', 'SearchController@index');
         Route::get('search/products', 'SearchController@searchProducts');

         // Food related
         Route::get('restaurants/filter', 'BranchController@filterCreate')->name('branches.filter');
         Route::post('restaurants/filter', 'BranchController@filter');
         Route::get('restaurants/{restaurant}', 'BranchController@show')->name('branches.show');


         /* auth related */
         Route::post('login', 'Auth\AuthController@login');
//         Route::post('auth/{provider}', 'Auth\SocialiteController@handleProvider');
         Route::get('otp/methods', 'OtpController@methods')->name('otp.methods');
         Route::get('otp/init-validation', 'OtpController@init')->name('otp.init');
         Route::get('otp/check-validation/{reference}', 'OtpController@check')->name('otp.check');
         Route::post('otp/sms-send', 'OtpController@otpSmsSend')->name('otp.sms-send');
         Route::post('otp/sms-validate', 'OtpController@otpSmsValidate')->name('otp.sms-validate');

         /* generals */
         Route::get('blog', 'PostController@blogIndex');
         Route::get('blog/{id}', 'PostController@blogShow');
         Route::get('privacy-policy', 'PostController@privacy');
         Route::get('terms-and-conditions', 'PostController@terms');
         Route::get('about-us', 'PostController@aboutUs');
         Route::get('faq', 'PostController@faq');
         Route::get('support', 'SettingController@support');
         Route::get('countries', 'CountryController@index');

         /* misc. */
         Route::post('logs/create', 'LogController@store');
     });

/* Protected Endpoints */
Route::middleware('auth:sanctum')
     ->group(function () {
         Route::post('logout', 'Auth\AuthController@logout');

         // Profile Addresses
         Route::get('profile/addresses', 'AddressController@index');
         Route::get('profile/addresses/create', 'AddressController@create');
         Route::post('profile/addresses', 'AddressController@store');
         Route::post('profile/addresses/{address}/delete', 'AddressController@destroy');
         Route::post('profile/addresses/change-selected-address', 'AddressController@changeSelectedAddress');
         // Profile Essentials
         Route::get('profile/edit', 'UserController@edit');
         Route::post('profile', 'UserController@update');

         // Favorites
         Route::get('profile/favorites', 'UserController@favorites');
         Route::post('products/{product}/interact', 'UserController@interact');

         // Restaurants Favorites
         Route::get('profile/restaurants/favorites', 'UserController@foodFavorites');
         Route::post('restaurants/{restaurant}/interact', 'UserController@foodInteract');

         // Orders -- Rating
         Route::get('orders/{order}/rate', 'OrderController@createRate');
         Route::post('orders/{order}/rate', 'OrderController@storeRate');

         // Orders
         Route::get('orders', 'OrderController@index');
         Route::get('orders/create', 'OrderController@create');
         Route::post('orders', 'OrderController@store');
         Route::post('orders/{order}/delete', 'OrderController@destroy');

         // Carts
         Route::post('carts/{cart}/products/adjust-quantity', 'CartController@adjustQuantity');
         Route::post('carts/{cart}/delete', 'CartController@destroy');

         Route::get('coupons/{code}/validate', 'CouponController@validateCoupon');


//         Route::get('taxonomies', 'TaxonomyController@index');
//         Route::get('taxonomies/{id}', 'TaxonomyController@show');
     });
