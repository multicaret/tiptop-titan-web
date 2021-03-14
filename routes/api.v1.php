<?php


/* Unprotected Endpoints */
Route::middleware('throttle:15')
     ->group(function () {
         /* catalog related */
         Route::get('/boot', 'HomeController@boot');
         Route::get('/', 'HomeController@root');
         Route::get('/home', 'HomeController@index');

         Route::get('categories/{groceryCategory}/products', 'CategoryController@products');

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

         /* misc. */
         Route::post('logs/create', 'LogController@store');
     });

/* Protected Endpoints */
Route::middleware('auth:api')
     ->group(function () {
         Route::post('profile', 'Auth\AuthController@profile');
         Route::post('password/update', 'Auth\PasswordController@update');

//         Route::get('users', 'UserController@index');
//         Route::get('users/{id}', 'UserController@show');
//         Route::get('taxonomies', 'TaxonomyController@index');
//         Route::get('taxonomies/{id}', 'TaxonomyController@show');
     });
