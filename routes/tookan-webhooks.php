<?php

//Tookan Webhooks
Route::group(['prefix' => 'tookan'], function (){
Route::post('pickup/success', function (){
    dd(123);
});
Route::post('pickup/success', 'TookanController@pickupSuccessWebHook');
Route::post('pickup/fail', 'TookanController@pickupFailWebHook');
Route::post('pickup/request_received', 'TookanController@pickupRequestReceived');
Route::post('pickup/agent_arrived', 'TookanController@pickupAgentArrived');
Route::post('delivery/success', 'TookanController@deliverySuccessWebHook');
Route::post('delivery/fail', 'TookanController@deliveryFailWebHook');
Route::post('delivery/request_received', 'TookanController@deliveryRequestReceived');
Route::post('delivery/agent_started', 'TookanController@deliveryAgentStarted');
Route::post('delivery/agent_arrived', 'TookanController@deliveryAgentArrived');
Route::post('driver_assigned', 'TookanController@driverAssigned');
Route::get('order/{order}/delivery_tracking_url', 'TookanController@getDeliveryTrackingUrl')->name('tracking_url');

});
