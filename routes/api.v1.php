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
         Route::get('products', 'ProductController@searchProducts');


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
         Route::get('countries-with-flags', 'CountryController@countreisWithFlags');

         /* misc. */
         Route::post('logs/create', 'LogController@store');
     });

/* Protected Endpoints */
Route::middleware('auth:sanctum')
     ->group(function () {
         Route::post('logout', 'Auth\AuthController@logout');
         Route::resource('profile/addresses', 'AddressController')->except(['edit', 'update']);
         Route::post('profile/addresses/change-selected-address', 'AddressController@changeSelectedAddress');
         Route::get('profile/edit', 'UserController@edit');
         Route::put('profile', 'UserController@update');
         Route::resource('orders', 'OrderController')->except(['edit', 'update', 'show']);
         Route::post('carts/{cart}/products/adjust-quantity', 'CartController@adjustQuantity');
         Route::post('carts/{cart}/delete', 'CartController@destroy');

//         Route::get('taxonomies', 'TaxonomyController@index');
//         Route::get('taxonomies/{id}', 'TaxonomyController@show');
     });
