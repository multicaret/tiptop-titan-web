<?php

namespace App\Console\Commands;

use App\Models\OldModels\OldUser;
use App\Models\User;
use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\ProgressBar;

class ImportUserZohoIdFromOldSystem extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user-zoho-id:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private ProgressBar $bar;
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
        $this->bar = $this->output->createProgressBar(OldUser::whereNotNull('zoho_id')->count());
        $this->bar->start();
        foreach (OldUser::whereNotNull('zoho_id')->cursor() as $oldUser) {
            User::where('id',$oldUser->id)->update(['zoho_crm_id'=>$oldUser->zoho_id]);
            $this->bar->advance();

        }
        $this->bar->finish();
        $this->newLine(1);
        $this->info('Done!');
        return 0;
    }
}
