<?php

/*
|--------------------------------------------------------------------------
| Web Ajax Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web ajax routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great dear MultiCaret developer!
|
*/


Route::prefix('countries')->name('countries.')->group(function () {
    Route::any('/', 'CountryController@index')->name('index');
    Route::any('{country}', 'CountryController@show')->name('show');
    Route::any('{country}/regions', 'CountryRegionController@index')->name('regions.index');
    Route::any('{country}/regions/{region}', 'CountryRegionController@show')->name('regions.show');
    Route::any('{country}/regions/{region}/cities', 'CountryRegionCityController@index')->name('regions.cities.index');
    Route::any('{country}/regions/{region}/cities/{city}',
        'CountryRegionCityController@show')->name('regions.cities.show');
});


Route::prefix('datatables')->name('datatables.')->group(function () {
    Route::get('taxonomies', 'DatatableController@taxonomies')->name('taxonomies');
    Route::get('posts', 'DatatableController@posts')->name('posts');
    Route::get('users', 'DatatableController@users')->name('users');
    Route::post('reorder', 'DatatableController@reorder')->name('reorder');
    Route::get('cities', 'DatatableController@cities')->name('cities');
    Route::get('regions', 'DatatableController@regions')->name('regions');
    Route::get('teams', 'DatatableController@teams')->name('teams');
    Route::get('slides', 'DatatableController@slides')->name('slides');
    Route::get('translations', 'DatatableController@translationList')->name('translations');
    Route::get('chains', 'DatatableController@chains')->name('chains');
    Route::get('branches', 'DatatableController@branches')->name('branches');
    Route::get('products', 'DatatableController@products')->name('products');
    Route::get('orders/ratings', 'DatatableController@orderRatings')->name('orders.ratings');
    Route::get('coupons', 'DatatableController@coupons')->name('coupons');
});

Route::post('change-status', 'AjaxController@statusChange')->name('statuses.change');
Route::post('change-channel', 'AjaxController@channelChange')->name('channels.change');


Route::group(['prefix' => 'search'], function () {
    Route::get('users', 'SearchController@users')->name('search.users');
    Route::get('taxonomies', 'SearchController@taxonomies')->name('search.taxonomies');
});

Route::post('theme-settings/save', 'AjaxController@saveAdminThemeSettings')->name('theme.settings.save');
Route::get('theme-settings/load', 'AjaxController@loadAdminThemeSettings')->name('theme.settings.load');

Route::put('translations', 'TranslationController@translationUpdate')->name('translation.update');
Route::get('translations/load', 'TranslationController@updateTranslationsData')->name('translation.load');
Route::resource('media', 'MediaController')->only(['store']);
Route::get('branch-by-chain', 'AjaxController@loadChainBranches')->name('branch-by-chain');
Route::get('chains/{chain}/sync', 'AjaxController@syncChain')->name('chains.sync');
