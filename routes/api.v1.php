<?php


/* Unprotected Endpoints */
Route::middleware('throttle:15')
     ->group(function () {
         /* catalog related */
         Route::get('/', 'HomeController@root');

         /* auth related */
         Route::post('login', 'Auth\AuthController@login');
         Route::post('register', 'Auth\AuthController@register');
         Route::post('password/reset', 'Auth\PasswordController@reset');
//         Route::post('account/verify', 'Auth\VerificationController@verify');
//         Route::post('reverify', 'Auth\VerificationController@reverify');
//         Route::post('auth/{provider}', 'Auth\SocialiteController@handleProvider');

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
