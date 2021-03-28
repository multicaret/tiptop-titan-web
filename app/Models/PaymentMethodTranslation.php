<?php

namespace App\Models;

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
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethodTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethodTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethodTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethodTranslation whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethodTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethodTranslation whereInstructions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethodTranslation whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethodTranslation wherePaymentMethodId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethodTranslation whereTitle($value)
 * @mixin \Eloquent
 */
class PaymentMethodTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['title', 'description', 'instructions'];
}
