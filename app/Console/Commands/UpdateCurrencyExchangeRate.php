<?php

namespace App\Console\Commands;

use App\Models\Currency;
use Illuminate\Console\Command;

class UpdateCurrencyExchangeRate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'currency-rate:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Currency rates in currencies table';

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
        $endpoint = 'http://data.fixer.io/api/latest?access_key='.config('services.fixer.key').'&format=1&base=USD';
        $currenciesApi = json_decode(file_get_contents($endpoint));
        foreach ($currenciesApi->rates as $code => $currencyAPiRate) {
            Currency::where('code', $code)->update(
                ['rate' => $currencyAPiRate]
            );
        }
        $this->info(' currency-rate:update Done!. '.PHP_EOL);

        return true;
    }
}
