<?php

namespace App\Console\Commands;

use App\Models\Currency;
use Illuminate\Console\Command;

class TestDevelopment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'development:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test anything';

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

        $this->info(' development:test Done!. '.PHP_EOL);

        return true;
    }
}
