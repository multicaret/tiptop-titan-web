<?php

/*
|--------------------------------------------------------------------------
| Web Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web admin routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" & "auth" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('admin.dashboard');
})->name('index');

Route::resource('posts', 'PostController')->except(['show']);
Route::resource('users', 'UserController')->except(['show']);
Route::resource('cities', 'CityController')->except(['show']);
Route::resource('regions', 'RegionController')->except(['show']);

Route::resource('slides', 'SlideController')->except(['show']);
Route::resource('chains', 'ChainController')->except(['show']);

Route::get('preferences/{section}/edit', 'PreferenceController@edit')->name('preferences.edit');
Route::post('preferences/{section}', 'PreferenceController@update')->name('preferences.update');
Route::get('preferences', 'PreferenceController@index')->name('preferences.index');
Route::post('preferences', 'PreferenceController@store')->name('preferences.store');
Route::resource('taxonomies', 'TaxonomyController');


Route::resource('media', 'MediaController')->only(['store']);
Route::resource('roles', 'RoleController');

Route::get('translations', 'TranslationController@index')->name('translations.index');
