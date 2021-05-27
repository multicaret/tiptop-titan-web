<?php

namespace App\Console\Commands;

use App\Models\OldModels\OldUser;
use App\Models\User;
use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\ProgressBar;

class FixLastNameForOldUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:fix-last-name';

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
        $this->bar = $this->output->createProgressBar(OldUser::count());
        $this->bar->start();
        foreach (OldUser::cursor() as $oldUser) {
            $stringable = \Str::of($oldUser->name);
            if ( ! $stringable->contains(' ')) {
                User::where('id', $oldUser->id)->update(['last' => null]);
                $this->bar->advance();
            }
        }
        $this->bar->finish();
        $this->newLine();
        $this->info('Done!');

        return 0;
    }
}
