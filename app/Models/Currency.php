<?php

namespace App\Models;

use App\Scopes\ActiveScope;
use App\Traits\HasStatuses;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Currency
 *
 * @property int $id
 * @property string $english_name
 * @property string $code
 * @property string $symbol
 * @property float|null $rate
 * @property string|null $decimal_separator
 * @property string|null $thousands_separator
 * @property bool|null $is_symbol_after
 * @property int $status 0:incomplete, 1:draft, 2:published, 3:Inactive, 4..n:CUSTOM
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Country[] $countries
 * @property-read int|null $countries_count
 * @property-read mixed $is_published
 * @property-read mixed $status_name
 * @method static \Illuminate\Database\Eloquent\Builder|Currency draft()
 * @method static \Illuminate\Database\Eloquent\Builder|Currency inactive()
 * @method static \Illuminate\Database\Eloquent\Builder|Currency incomplete()
 * @method static \Illuminate\Database\Eloquent\Builder|Currency newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Currency newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Currency notPublished()
 * @method static \Illuminate\Database\Eloquent\Builder|Currency published()
 * @method static \Illuminate\Database\Eloquent\Builder|Currency query()
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereDecimalSeparator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereEnglishName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereIsSymbolAfter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereSymbol($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereThousandsSeparator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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

        return vsprintf("%s %s", $currency->is_symbol_after ?
            [$number_formatted, $symbol] : [$symbol, $number_formatted]);
    }


}
