<?php

namespace App\Notifications\Channels;

use Berkayk\OneSignal\OneSignalClient;
use NotificationChannels\OneSignal\OneSignalChannel;

class OneSignalRestaurantChannel extends OneSignalChannel
{

    public function __construct()
    {
        $oneSignal = new OneSignalClient(
            config('services.onesignal.restaurant_app_id'),
            config('services.onesignal.restaurant_rest_api_key'),
            null
        );
        parent::__construct($oneSignal);
    }

}
