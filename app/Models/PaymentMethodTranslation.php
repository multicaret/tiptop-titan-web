<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\PaymentMethodTranslation
 *
 * @property int $id
 * @property int $payment_method_id
 * @property string|null $title
 * @property string|null $description
 * @property mixed|null $instructions
 * @property string $locale
 * @method static Builder|PaymentMethodTranslation newModelQuery()
 * @method static Builder|PaymentMethodTranslation newQuery()
 * @method static Builder|PaymentMethodTranslation query()
 * @method static Builder|PaymentMethodTranslation whereDescription($value)
 * @method static Builder|PaymentMethodTranslation whereId($value)
 * @method static Builder|PaymentMethodTranslation whereInstructions($value)
 * @method static Builder|PaymentMethodTranslation whereLocale($value)
 * @method static Builder|PaymentMethodTranslation wherePaymentMethodId($value)
 * @method static Builder|PaymentMethodTranslation whereTitle($value)
 * @mixin Eloquent
 * @noinspection PhpFullyQualifiedNameUsageInspection
 * @noinspection PhpUnnecessaryFullyQualifiedNameInspection
 */
class PaymentMethodTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['title', 'description', 'instructions'];
}
