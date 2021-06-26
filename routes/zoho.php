<?php

Route::get('analytics/authorize', 'ZohoAnalyticsController@authorizeRequest');
Route::get('analytics/restaurant-daily-orders/import', 'ZohoAnalyticsController@dailyOrdersJsonImport');
Route::get('analytics/branches/import', 'ZohoAnalyticsController@branchesJsonImport');
