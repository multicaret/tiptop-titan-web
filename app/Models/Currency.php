<?php

namespace App\Models;

use App\Scopes\ActiveScope;
use App\Traits\HasStatuses;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasStatuses;

    const STATUS_INCOMPLETE = 0;
    const STATUS_DRAFT = 1;
    const STATUS_PUBLISHED = 2;
    const STATUS_INACTIVE = 3;

    const IS_SYMBOL_BEFORE = 0;
    const IS_SYMBOL_AFTER = 1;
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'rate' => 'float',
        'is_symbol_after' => 'boolean',
    ];

    /* GLOBAL SCOPES */
    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new ActiveScope);
    }

    /**
     * @param $currencyCode
     *
     * @return mixed
     */
    private static function getCurrency($currencyCode)
    {
        $builder = self::withoutGlobalScope(ActiveScope::class);
        if (is_null($currencyCode)) {
            $currency = $builder->where('code', config('app.default_currency_code'))->first();
            if (session()->has('currency_code')) {
                $currency = $builder->where('code', session()->get('currency_code'))->first();
            }
        } else {
            $currency = $builder->where('code', $currencyCode)->first();
        }

        return $currency;
    }


    public function countries()
    {
        return $this->hasMany(Country::class);
    }

    public static function format($amount, $currencyCode = null, $decimals = 0)
    {
        if (is_null($currencyCode)) {
            $currencyCode = config('defaults.currency.code');
        }
        $currency = self::getCurrency($currencyCode);
        $number_formatted = number_format($amount, $decimals, $currency->decimal_separator,
            $currency->thousands_separator);

        $symbol = $currency->symbol;
        if (localization()->getCurrentLocale() == 'en') {
            if ($currencyCode == config('defaults.currency.code')) {
                $symbol = 'IQD';
            }
        }

        return $currency->is_symbol_after ?
            $number_formatted.$symbol : $symbol.$number_formatted;
    }


}
