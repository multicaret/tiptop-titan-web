<?php


Route::get('/', 'DashboardController@home')->name('home');

/* User Related */
Route::view('profile', 'dashboard.users.profile')->name('users.profile');
Route::view('settings', 'dashboard.users.edit')->name('users.edit');
Route::view('statements', 'dashboard.statements.index')->name('statements.index');
Route::view('notifications', 'dashboard.notifications.index')->name('notifications.index');
