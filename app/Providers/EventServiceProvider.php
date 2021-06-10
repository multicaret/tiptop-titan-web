<?php

namespace App\Providers;

use App\Models\Branch;
use App\Models\Order;
use App\Models\OrderDailyReport;
use App\Models\Product;
use App\Models\ProductOption;
use App\Models\TookanTeam;
use App\Models\User;
use App\Observers\BranchObserver;
use App\Observers\OrderDailyReportObserver;
use App\Observers\OrderObserver;
use App\Observers\ProductObserver;
use App\Observers\ProductOptionObserver;
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
        Branch::observe(BranchObserver::class);
        Product::observe(ProductObserver::class);
        ProductOption::observe(ProductOptionObserver::class);
        Order::observe(OrderObserver::class);
        TookanTeam::observe(TookanTeamObserver::class);
        OrderDailyReport::observe(OrderDailyReportObserver::class);
    }
}
