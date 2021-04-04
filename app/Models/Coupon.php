<?php

namespace App\Models;

use App\Http\Resources\CouponResource;
use App\Traits\HasAppTypes;
use App\Traits\HasStatuses;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\Coupon
 *
 * @property int $id
 * @property int $creator_id
 * @property int $editor_id
 * @property int $currency_id
 * @property int $type 1:Market, 2: Food
 * @property string $name
 * @property string|null $description
 * @property float|null $discount_amount
 * @property bool|null $discount_by_percentage true: percentage, false: fixed amount
 * @property float $max_allowed_discount_amount
 * @property float $min_cart_value_allowed
 * @property bool $has_free_delivery
 * @property int $max_usable_count
 * @property int $total_redeemed_count
 * @property int $max_usable_count_by_user
 * @property float $money_redeemed_so_far
 * @property Carbon|null $expired_at
 * @property string $redeem_code
 * @property int $status 1:draft, 2:active, 3:Inactive, 4..n:CUSTOM
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read Collection|CouponUsage[] $couponUsage
 * @property-read int|null $coupon_usage_count
 * @property-read bool $is_active
 * @property-read bool $is_inactive
 * @property-read mixed $status_name
 * @method static Builder|Coupon active()
 * @method static Builder|Coupon draft()
 * @method static Builder|Coupon food()
 * @method static Builder|Coupon grocery()
 * @method static Builder|Coupon inactive()
 * @method static Builder|Coupon newModelQuery()
 * @method static Builder|Coupon newQuery()
 * @method static Builder|Coupon notActive()
 * @method static Builder|Coupon query()
 * @method static Builder|Coupon whereCreatedAt($value)
 * @method static Builder|Coupon whereCreatorId($value)
 * @method static Builder|Coupon whereCurrencyId($value)
 * @method static Builder|Coupon whereDeletedAt($value)
 * @method static Builder|Coupon whereDescription($value)
 * @method static Builder|Coupon whereDiscountAmount($value)
 * @method static Builder|Coupon whereDiscountByPercentage($value)
 * @method static Builder|Coupon whereEditorId($value)
 * @method static Builder|Coupon whereExpiredAt($value)
 * @method static Builder|Coupon whereHasFreeDelivery($value)
 * @method static Builder|Coupon whereId($value)
 * @method static Builder|Coupon whereMaxAllowedDiscountAmount($value)
 * @method static Builder|Coupon whereMaxUsableCount($value)
 * @method static Builder|Coupon whereMaxUsableCountByUser($value)
 * @method static Builder|Coupon whereMinCartValueAllowed($value)
 * @method static Builder|Coupon whereMoneyRedeemedSoFar($value)
 * @method static Builder|Coupon whereName($value)
 * @method static Builder|Coupon whereRedeemCode($value)
 * @method static Builder|Coupon whereStatus($value)
 * @method static Builder|Coupon whereTotalRedeemedCount($value)
 * @method static Builder|Coupon whereType($value)
 * @method static Builder|Coupon whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Coupon extends Model
{
    use HasAppTypes;
    use HasStatuses;


    public const STATUS_DRAFT = 1;
    public const STATUS_ACTIVE = 2;
    public const STATUS_INACTIVE = 3;

    public const TYPE_GROCERY_OBJECT = 1;
    public const TYPE_FOOD_OBJECT = 2;

    protected $casts = [
        'discount_amount' => 'double',
        'max_allowed_discount_amount' => 'double',
        'min_cart_value_allowed' => 'double',
        'discount_by_percentage' => 'boolean',
        'has_free_delivery' => 'boolean',
        'expired_at' => 'date',
    ];

    public function couponUsage(): HasMany
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
