<?php

namespace App\Models;

use App\Scopes\ActiveScope;
use App\Traits\HasStatuses;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

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
 * @property int $status 1:draft, 2:active, 3:Inactive, 4..n:CUSTOM
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|\App\Models\Country[] $countries
 * @property-read int|null $countries_count
 * @property-read bool $is_active
 * @property-read bool $is_inactive
 * @property-read mixed $status_name
 * @method static Builder|Currency active()
 * @method static Builder|Currency draft()
 * @method static Builder|Currency inactive()
 * @method static Builder|Currency newModelQuery()
 * @method static Builder|Currency newQuery()
 * @method static Builder|Currency notActive()
 * @method static Builder|Currency notDraft()
 * @method static Builder|Currency query()
 * @method static Builder|Currency whereCode($value)
 * @method static Builder|Currency whereCreatedAt($value)
 * @method static Builder|Currency whereDecimalSeparator($value)
 * @method static Builder|Currency whereEnglishName($value)
 * @method static Builder|Currency whereId($value)
 * @method static Builder|Currency whereIsSymbolAfter($value)
 * @method static Builder|Currency whereRate($value)
 * @method static Builder|Currency whereStatus($value)
 * @method static Builder|Currency whereSymbol($value)
 * @method static Builder|Currency whereThousandsSeparator($value)
 * @method static Builder|Currency whereUpdatedAt($value)
 * @mixin Eloquent
 * @noinspection PhpFullyQualifiedNameUsageInspection
 * @noinspection PhpUnnecessaryFullyQualifiedNameInspection
 */
class Currency extends Model
{
    use HasStatuses;


    public const STATUS_DRAFT = 1;
    public const STATUS_ACTIVE = 2;
    public const STATUS_INACTIVE = 3;

    public const IS_SYMBOL_BEFORE = 0;
    public const IS_SYMBOL_AFTER = 1;
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


    public static function renderFormat($amount, $currencyCode = null, $decimals = 0): array
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

        return [$currency->is_symbol_after, $number_formatted, $symbol];
    }

    public static function format($amount, $currencyCode = null, $decimals = 0)
    {
        [$isSymbolAfter, $numberFormatted, $symbol] = self::renderFormat($amount, $currencyCode, $decimals);

        return vsprintf('%s %s', $isSymbolAfter ?
            [$numberFormatted, $symbol] : [$symbol, $numberFormatted]);
    }


    public static function formatHtml($amount, $currencyCode = null, $decimals = 0)
    {
        [$isSymbolAfter, $numberFormatted, $symbol] = self::renderFormat($amount, $currencyCode, $decimals);
        if ($isSymbolAfter) {
            return sprintf('%s <sup>%s</sup>', $numberFormatted, $symbol);
        } else {
            return sprintf('<sup>%s</sup> %s', $symbol, $numberFormatted);
        }
    }


}
