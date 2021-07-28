<?php

Route::get('analytics/authorize', 'ZohoAnalyticsController@authorizeRequest');
Route::get('analytics/restaurant-daily-orders/import', 'ZohoAnalyticsController@dailyOrdersJsonImport');
Route::get('analytics/branches/import', 'ZohoAnalyticsController@branchesJsonImport');
Route::get('analytics/search-terms/import', 'ZohoAnalyticsController@searchTermsJsonImport');
Route::get('analytics/orders-details/import', 'ZohoAnalyticsController@detailedOrdersJsonImport');
Route::get('analytics/orders-rate/import', 'ZohoAnalyticsController@ordersRatesImport');
