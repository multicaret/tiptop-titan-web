<?php

namespace App\Notifications\Channels;

use Berkayk\OneSignal\OneSignalClient;
use NotificationChannels\OneSignal\OneSignalChannel;

class OneSignalDashboardChannel extends OneSignalChannel
{

    public function __construct()
    {
        $oneSignal = new OneSignalClient(
            config('services.onesignal.dashboard_app_id'),
            config('services.onesignal.dashboard_rest_api_key'),
            null
        );
        parent::__construct($oneSignal);
    }

}
