<?php

namespace App\Models;

use App\Http\Controllers\Controller;
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
 * @property-read Collection|CouponUsage[] $couponUsages
 * @property-read int|null $coupon_usages_count
 * @property-read bool $is_active
 * @property-read bool $is_food
 * @property-read bool $is_grocery
 * @property-read bool $is_inactive
 * @property-read mixed $status_name
 * @method static Builder|Coupon active()
 * @method static Builder|Coupon draft()
 * @method static Builder|Coupon foods()
 * @method static Builder|Coupon groceries()
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
 * @property int $channel 1:food and grocery, 2:grocery, 3:food
 * @method static Builder|Coupon whereChannel($value)
 * @property-read array $status_js
 */
class Coupon extends Model
{
    use HasAppTypes;
    use HasStatuses;


    public const STATUS_DRAFT = 1;
    public const STATUS_ACTIVE = 2;
    public const STATUS_INACTIVE = 3;

    public const TYPE_FOOD_AND_GROCERY_OBJECT = 1;
    public const CHANNEL_GROCERY_OBJECT = 2;
    public const CHANNEL_FOOD_OBJECT = 3;

    protected $casts = [
        'discount_amount' => 'double',
        'max_allowed_discount_amount' => 'double',
        'min_cart_value_allowed' => 'double',
        'discount_by_percentage' => 'boolean',
        'has_free_delivery' => 'boolean',
        'expired_at' => 'datetime',
    ];

    public static function getCouponChannelsArray(): array
    {
        return [
            self::TYPE_FOOD_AND_GROCERY_OBJECT => trans('strings.grocery_and_food'),
            self::CHANNEL_GROCERY_OBJECT => trans('strings.grocery'),
            self::CHANNEL_FOOD_OBJECT => trans('strings.food'),
        ];
    }

    public static function getAllChannelsRich(): array
    {
        return [
            self::CHANNEL_GROCERY_OBJECT => [
                'id' => self::CHANNEL_GROCERY_OBJECT,
                'title' => trans('strings.grocery'),
                'class' => 'success',
            ],
            self::CHANNEL_FOOD_OBJECT => [
                'id' => self::CHANNEL_FOOD_OBJECT,
                'title' => trans('strings.food'),
                'class' => 'dark',
            ],
            self::TYPE_FOOD_AND_GROCERY_OBJECT => [
                'id' => self::TYPE_FOOD_AND_GROCERY_OBJECT,
                'title' => trans('strings.both'),
                'class' => 'info',
            ],
        ];
    }


    public function couponUsages(): HasMany
    {
        return $this->hasMany(CouponUsage::class, 'coupon_id');
    }

    /**
     * @param  Coupon  $coupon
     * @param  User|null  $user
     * @return array|string[]
     */
    public function validateExpirationDateAndUsageCount(User $user = null): array
    {
        if (is_null($user)) {
            $user = auth()->user();
        }

        $isValid = false;
        $message = 'Coupon code is invalid';

        if ( ! is_null($this->expired_at) && $this->expired_at->isPast()) {
            $message = 'Coupon is expired';
        }

        if ($this->max_usable_count > $this->total_redeemed_count) {
            if ($this->max_usable_count_by_user > $user->couponUsages()->count()) {
                $isValid = true;
            } else {
                $message = 'User Cannot Use the current coupon';
            }
        } else {
            $message = 'The exceeded the total usage amount';
        }

        return [$isValid, $message];

    }


    public function validateCouponDiscountAmount($amount): array
    {
        $isValid = $this->min_cart_value_allowed <= $amount;

        $totalDiscountedAmount = Controller::calculateDiscountedAmount($amount, $this->discount_amount,
            $this->discount_by_percentage);
        if ($this->max_allowed_discount_amount < $totalDiscountedAmount) {
            $isValid = $isValid && true;
            $totalDiscountedAmount = $this->max_allowed_discount_amount;
        }

        return [
            $isValid,
            $totalDiscountedAmount,
        ];

    }
}
