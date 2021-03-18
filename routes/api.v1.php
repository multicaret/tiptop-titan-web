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
         Route::post('register', 'Auth\AuthController@register');
//         Route::post('auth/{provider}', 'Auth\SocialiteController@handleProvider');
         Route::get('otp/methods', 'OtpController@methods')->name('otp.methods');
         Route::get('otp/init-validation', 'OtpController@init')->name('otp.init');
         Route::get('otp/check-validation/{reference}', 'OtpController@check')->name('otp.check');
         Route::post('otp/sms-send', 'OtpController@otpSmsSend')->name('otp.sms-send');
         Route::post('otp/sms-validate', 'OtpController@otpSmsValidate')->name('otp.sms-validate');

         /* generals */
         Route::get('faqs', 'PostController@faqIndex');
         Route::get('faqs/{id}', 'PostController@faqShow');
         Route::get('blog', 'PostController@blogIndex');
         Route::get('blog/{id}', 'PostController@blogShow');
         Route::get('privacy', 'PostController@privacy');
         Route::get('about-us', 'PostController@aboutUs');

         /* misc. */
         Route::post('logs/create', 'LogController@store');
     });

/* Protected Endpoints */
Route::middleware('auth:api')
     ->group(function () {
//         Route::get('profile/addresses', 'AddressController@index');
//         Route::get('profile/addresses/create', 'AddressController@create');
//         Route::post('profile/addresses', 'AddressController@store');
//         Route::post('profile/addresses/destroy', 'AddressController@store');
         Route::post('logout', 'Auth\AuthController@logout');
         Route::resource('profile/addresses', 'AddressController')->except(['edit', 'update']);
         Route::post('profile/addresses/change-selected-address', 'AddressController@changeSelectedAddress');
         Route::post('profile', 'Auth\AuthController@profile');
//         Route::post('password/update', 'Auth\PasswordController@update');
         Route::resource('orders', 'OrderController')->except(['edit', 'update']);
         Route::get('orders/checkout', 'OrderController@checkoutCreate');
         Route::post('orders/checkout', 'OrderController@checkoutStore');
         Route::post('baskets/add-remove-product', 'BasketController@addRemoveProduct');
         Route::post('baskets/clear-basket', 'BasketController@clearBasket');

//         Route::get('users', 'UserController@index');
//         Route::get('users/{id}', 'UserController@show');
//         Route::get('taxonomies', 'TaxonomyController@index');
//         Route::get('taxonomies/{id}', 'TaxonomyController@show');
     });
