<?php

namespace App\Console\Commands;

use App\Integrations\Tookan\TookanClient;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class SyncTookanCaptains extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'captains:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $tookan_status = config('services.tookan.status');
        if (!$tookan_status) return 0;

        $role = ucwords(str_replace('-', ' ', Str::title(User::ROLE_TIPTOP_DRIVER)));
        User::active()->role($role)->chunk(5,function ($captains){
            foreach ($captains as $captain)
            {
                if(empty($captain->tookan_id))
                    (new TookanClient())->createCaptain($captain);
                else
                    (new TookanClient())->updateCaptain($captain);

            }
        });

    }
}
