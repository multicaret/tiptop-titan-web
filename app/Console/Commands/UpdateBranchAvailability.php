<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateBranchAvailability extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'branch-availability:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update if Branch is open or not at midnight';

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
     * @return mixed
     */
    public function handle()
    {
        DB::table('branches')
          ->where('is_open_now', 0)
          ->update(['is_open_now' => 1]);

        $this->info(' branch-availability:update Done!. '.PHP_EOL);

        return true;
    }
}
