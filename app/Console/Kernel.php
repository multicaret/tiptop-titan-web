<?php

namespace App\Console;

use App\Console\Commands\ExportOrdersToZoho;
use App\Console\Commands\OrdersReminder;
use App\Console\Commands\UpdateBranchAvailability;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        UpdateBranchAvailability::class,
        OrdersReminder::class,
        ExportOrdersToZoho::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
//        $schedule->command(UpdateCurrencyExchangeRate::class)->hourly();

//        $schedule->command('backup:clean')->daily()->at('01:00');
//        $schedule->command('backup:run')->daily()->at('02:00');
        $schedule->command('branch-availability:update')->daily()->at('03:56');

        if (app()->environment('production')) {
            $schedule->command('backup:clean')->twiceDaily(1, 13);
            $schedule->command('backup:run')->twiceDaily(2, 14);
            $schedule->command('orders:remind --minutes=3 --roles=branch,admin')->everyThreeMinutes();
            $schedule->command('orders:remind --minutes=5 --roles=admin')->everyFiveMinutes();
            $schedule->command('orders:export-to-zoho')->everyTwoHours();

        }
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
