<?php

namespace App\Providers;

use App\Models\Order;
use App\Models\TookanTeam;
use App\Models\User;
use App\Observers\OrderObserver;
use App\Observers\TookanTeamObserver;
use App\Observers\UserObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use SocialiteProviders\Manager\SocialiteWasCalled;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        SocialiteWasCalled::class => [
            'SocialiteProviders\\Facebook\\FacebookExtendSocialite@handle',
//            'SocialiteProviders\\Google\\GoogleExtendSocialite@handle',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        User::observe(UserObserver::class);
        Order::observe(OrderObserver::class);
        TookanTeam::observe(TookanTeamObserver::class);
    }
}
