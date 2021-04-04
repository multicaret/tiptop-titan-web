<?php

namespace App\Models;

use App\Http\Resources\CouponResource;
use App\Traits\HasAppTypes;
use App\Traits\HasStatuses;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Coupon
 *
 * @property int $id
 * @property int $creator_id
 * @property int $editor_id
 * @property int $currency_id
 * @property int|null $discount_by_percentage true: percentage, false: fixed amount
 * @property float|null $discount_amount
 * @property int|null $max_usable_count
 * @property int $max_usable_count_by_user
 * @property string|null $expired_at
 * @property string $code
 * @property int $status 0:incomplete, 1:draft, 2:published, 3:Inactive, 4..n:CUSTOM
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon query()
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereCurrencyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereDiscountAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereDiscountByPercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereEditorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereExpiredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereTotalUsageCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereUsageCountBySameUser($value)
 * @mixin \Eloquent
 */
class Coupon extends Model
{
    use HasStatuses,HasAppTypes;

    const STATUS_INCOMPLETE = 0;
    const STATUS_DRAFT = 1;
    const STATUS_PUBLISHED = 2;
    const STATUS_INACTIVE = 3;

    const TYPE_GROCERY_OBJECT = 1;
    const TYPE_FOOD_OBJECT = 2;

    protected $casts = [
        'discount_amount' => 'double',
        'max_allowed_discount_amount' => 'double',
        'min_cart_value_allowed' => 'double',
        'discount_by_percentage' => 'boolean',
        'has_free_delivery' => 'boolean',
        'expired_at' => 'date',
    ];

    public function couponUsage(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CouponUsage::class, 'coupon_id');
    }

    public static function retrieveValidation($coupon)
    {
        if (is_null($coupon)) {
            return [
                'type' => 'undefined',
                'message' => 'Coupon code is wrong'
            ];

        } elseif ( ! is_null($coupon->expired_at) && $coupon->expired_at < now()) {
            return [
                'type' => 'error',
                'message' => 'Coupon is expired'
            ];
        }
        $totalUsageBuilder = $coupon->couponUsage();
        if ($coupon->max_usable_count > $totalUsageBuilder->count()) {
            if ($coupon->max_usable_count_by_user > auth()->user()->couponUsage()->count()) {
                if ($coupon->max_allowed_discount_amount > $coupon->couponUsage()->sum('discounted_amount')) {
                    return [
                        'type' => 'Success',
                        'data' => new CouponResource($coupon)
                    ];
                } else {
                    return [
                        'type' => 'error',
                        'message' => 'Discount amount  is full'
                    ];
                }
            } else {
                return [
                    'type' => 'error',
                    'message' => 'User Cannot Use the current coupon'
                ];
            }
        } else {
            return [
                'type' => 'error',
                'message' => 'The total usage count full'
            ];
        }

    }
}
