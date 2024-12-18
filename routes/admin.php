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
Route::get('users/{role}/{user}/edit', 'UserController@edit')->name('users.edit');
Route::put('users/{role}/{user}/update', 'UserController@update')->name('users.update');
Route::delete('users/{role}/{user}/delete', 'UserController@destroy')->name('users.destroy');
Route::get('users/{role}/create', 'UserController@create')->name('users.create');
Route::post('users/{role}/store', 'UserController@store')->name('users.store');

Route::get('users/{user}/addresses/create', 'UserController@createAddress')->name('users.addresses.create');
Route::post('users/{user}/addresses/store', 'UserController@storeAddress')->name('users.addresses.store');
Route::get('users/{user}/addresses/{address}/edit', 'UserController@editAddress')->name('users.addresses.edit');
Route::put('users/{user}/addresses/{address}/update', 'UserController@updateAddress')->name('users.addresses.update');
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
Route::resource('brands', 'BrandController')->except(['show']);
Route::resource('cities', 'CityController')->except(['show']);
Route::resource('regions', 'RegionController')->except(['show']);

Route::resource('teams', 'TeamController')->except(['show']);
Route::resource('slides', 'SlideController')->except(['show']);
Route::get('chains/{chain}/sync', 'ChainController@sync')->name('chains.sync');
Route::post('chains/{chain}/sync', 'ChainController@postSync');
Route::resource('chains', 'ChainController')->except(['show']);
Route::post('chains/{chains}/products/import-excel-file', 'ChainController@productsExcelImporter')
     ->name('chains.products.import-from-excel');

Route::get('branches/{branch}/export-to-excel', 'BranchController@exportToExcel')->name('branch.export-to-excel');
Route::post('branches/{branch}/import-excel-file',
    'BranchController@importFromExcel')->name('branch.import-from-excel');
Route::post('branches/{branch}/workingHours', 'BranchController@storeWorkingHours')->name('branch.working-hours');
Route::post('branches/{branch}/apply_discount', 'BranchController@applyDiscount')->name('branch.apply_discount');
Route::resource('branches', 'BranchController')->except(['show']);
Route::resource('restaurants', 'RestaurantController')->only(['create', 'store']);

Route::get('products/{product}/options', 'ProductOptionsController@create')->name('products.options');
Route::resource('products', 'ProductController')->except(['show']);

Route::get('ratings/orders', 'OrderController@orderRatings')->name('orders.ratings');
//Route::get('ratings/drivers', 'OrderController@Orderratings')->name('orders.ratings'); //future routes
//Route::get('ratings/branches', 'OrderController@Orderratings')->name('orders.ratings');

Route::get('orders', 'OrderController@index')->name('orders.index');
Route::get('orders/{order}', 'OrderController@show')->name('orders.show');

Route::get('jet/orders', 'JetOrderController@index')->name('jet.orders.index');
Route::get('jet/orders/{order}', 'JetOrderController@show')->name('jet.orders.show');

Route::get('reports/daily', 'OrderDailyReportController@index')->name('reports.index');
Route::resource('coupons', 'CouponController')->except(['show']);
Route::resource('payment-methods', 'PaymentMethodController')->only('index');

Route::get('preferences/adjust-trackers', 'PreferenceController@adjustTrackers')->name('preferences.adjust-trackers');
Route::get('preferences/{section}/edit', 'PreferenceController@edit')->name('preferences.edit');
Route::post('preferences/{section}', 'PreferenceController@update')->name('preferences.update');
Route::get('preferences', 'PreferenceController@index')->name('preferences.index');
Route::post('preferences', 'PreferenceController@store')->name('preferences.store');
Route::resource('taxonomies', 'TaxonomyController');
Route::get('notifications', 'NotificationController@index')->name('notifications.index');
Route::get('notifications/{notification}/handle', 'NotificationController@handle')->name('notifications.handle');

Route::resource('media', 'MediaController')->only(['store']);
Route::resource('roles', 'RoleController');

Route::get('translations', 'TranslationController@index')->name('translations.index');

Route::get('tagged-users/{id}/export','UserController@exportTaggedUsers')->name('tagged_users.export');
