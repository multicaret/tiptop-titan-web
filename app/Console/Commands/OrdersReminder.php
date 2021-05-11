<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\User;
use App\Notifications\OrderStatusUpdated;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class OrdersReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:remind {--minutes=} {--roles=*}';

    protected $minutes = 3;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remind about a new orders';


    protected bool $shouldSendToSuperAdmins = false;
    protected bool $shouldSendToBranchOwnersAndManagers = false;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }


    public function initialize(InputInterface $input, OutputInterface $output)
    {
        if ( ! is_null($this->option('minutes'))) {
            $this->minutes = $this->option('minutes');
        } else {
            $this->warn("Using default value of minutes ({$this->minutes}m)");
        }

        $rolesOption = $this->option('roles');
        if (isset($rolesOption) && ! empty($rolesOption) && count($rolesOption)) {
            $this->shouldSendToBranchOwnersAndManagers = str_contains($rolesOption[0], 'branch');
            $this->shouldSendToSuperAdmins = str_contains($rolesOption[0], 'admin');
        }
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info("Running command for minutes: ({$this->minutes}m)");

        $ordersToBeRemindedAbout = Order::whereStatus(Order::STATUS_NEW)
                                        ->where('created_at', '>=', now()->subMinutes($this->minutes))
                                        ->get();

        $this->info("Running for {$ordersToBeRemindedAbout->count()}");
        foreach ($ordersToBeRemindedAbout as $order) {
            $minutesDelay = now()->diffInMinutes($order->created_at);
            $this->info("Order ID: {$order->id} minutesDelay => $minutesDelay");

            if ($this->shouldSendToSuperAdmins) {
                foreach (User::active()->managers()->get() as $admin) {
                    $admin->notify(new OrderStatusUpdated($order, $admin->role_name, $minutesDelay));
                }
            }
            if ($this->shouldSendToBranchOwnersAndManagers) {
                foreach ($order->branch->owners()->active()->get() as $manager) {
                    $manager->notify(new OrderStatusUpdated($order, $manager->role_name, $minutesDelay));
                }
                foreach ($order->branch->managers()->active()->get() as $manager) {
                    $manager->notify(new OrderStatusUpdated($order, $manager->role_name, $minutesDelay));
                }
            }
            $this->info("Notifications sent for Order: {$ordersToBeRemindedAbout->count()}");
        }

        return 'Hi';
    }
}
