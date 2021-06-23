<?php


Route::get('analytics/restaurant-daily-orders/import', 'ZohoAnalyticsController@dailyOrdersJsonImport');
Route::get('analytics/branches/import', 'ZohoAnalyticsController@branchesJsonImport');
