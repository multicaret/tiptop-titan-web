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

Route::get('users/{role}', 'UserController@index')->name('users.index');
Route::get('users/{role}/create', 'UserController@create')->name('users.create');
Route::post('users/{role}/store', 'UserController@store')->name('users.store');
//Bottom ones don't have a "role" wildcard (cue in angry MK sounds)
Route::get('users/{user}/edit', 'UserController@edit')->name('users.edit');
Route::put('users/{user}/update', 'UserController@update')->name('users.update');
Route::delete('users/{user}/delete', 'UserController@destroy')->name('users.destroy');
/*

Route::resource('users', 'UserController')->except(['show']);
Route::resource('restaurant-drivers', 'UserController')->except(['show']);
Route::resource('tiptop-drivers', 'UserController')->except(['show']);
Route::resource('admins', 'UserController')->except(['show']);
Route::resource('supervisors', 'UserController')->except(['show']);
Route::resource('agents', 'UserController')->except(['show']);
Route::resource('content-editors', 'UserController')->except(['show']);
Route::resource('marketers', 'UserController')->except(['show']);
Route::resource('branch-owners', 'UserController')->except(['show']);
Route::resource('branch-managers', 'UserController')->except(['show']);*/

Route::resource('posts', 'PostController')->except(['show']);
Route::resource('cities', 'CityController')->except(['show']);
Route::resource('regions', 'RegionController')->except(['show']);

Route::resource('teams', 'TeamController')->except(['show']);
Route::resource('slides', 'SlideController')->except(['show']);
Route::resource('chains', 'ChainController')->except(['show']);
Route::post('branches/{branch}/workingHours', 'BranchController@storeWorkingHours')->name('branch.working-hours');
Route::resource('branches', 'BranchController')->except(['show']);
Route::resource('restaurants', 'RestaurantController')->only(['create', 'store']);
Route::get('products/{product}/options', 'ProductOptionsController@create')->name('products.options');
Route::resource('products', 'ProductController')->except(['show']);
Route::get('orders/ratings', 'OrderController@ratings')->name('orders.ratings');
Route::get('orders', 'OrderController@index')->name('orders.index');
Route::get('orders/{order}', 'OrderController@show')->name('orders.show');
Route::get('reports/daily', 'OrderDailyReportController@index')->name('reports.index');
Route::resource('coupons', 'CouponController')->except(['show']);
Route::resource('payment-methods', 'PaymentMethodController')->only('index');

Route::get('preferences/adjust-trackers', 'PreferenceController@adjustTrackers')->name('preferences.adjust-trackers');
Route::get('preferences/{section}/edit', 'PreferenceController@edit')->name('preferences.edit');
Route::post('preferences/{section}', 'PreferenceController@update')->name('preferences.update');
Route::get('preferences', 'PreferenceController@index')->name('preferences.index');
Route::post('preferences', 'PreferenceController@store')->name('preferences.store');
Route::resource('taxonomies', 'TaxonomyController');


Route::resource('media', 'MediaController')->only(['store']);
Route::resource('roles', 'RoleController');

Route::get('translations', 'TranslationController@index')->name('translations.index');


