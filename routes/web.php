<?php

use Illuminate\Support\Facades\Route;

Auth::routes(['verify' => true]);
Route::get('login/{provider}', 'Auth\LoginController@redirectToProvider')->name('social.redirect');
Route::get('login/{provider}/callback', 'Auth\RegisterController@handleProviderCallback')->name('social.handle');

Route::get('/', 'HomeController@index');
Route::get('home', 'HomeController@index')->name('home');
Route::get('about-us', 'PageController@about')->name('about');
Route::get('contact-us', 'PageController@contact')->name('contact');
Route::post('contact-us', 'PageController@contactForm');
Route::get('privacy-policy', 'PageController@privacyPolicy')->name('pages.privacyPolicy');
Route::get('terms-and-conditions', 'PageController@termsAndConditions')->name('pages.termsAndConditions');
Route::get('faq', 'PageController@faq')->name('faq');
Route::get('pages/{page}', 'PageController@show')->name('pages.show');

Route::get('blog', 'BlogController@index')->name('blog.index');
Route::get('blog/{post}', 'BlogController@show')->name('blog.show');

Route::view('support', 'frontend.support')->name('support');


Route::get('static-map', 'HomeController@staticMap');

Route::get('foo', 'HomeController@foo');
