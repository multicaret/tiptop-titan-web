<?php

namespace App\Models;

use App\Traits\HasAppTypes;
use App\Traits\HasTypes;
use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;

/**
 * App\Models\Order
 *
 * @property int $id
 * @property int $reference_code
 * @property int $user_id
 * @property int $chain_id
 * @property int $branch_id
 * @property int $cart_id
 * @property int $payment_method_id
 * @property int $address_id
 * @property int|null $coupon_id
 * @property int $city_id
 * @property int|null $previous_order_id
 * @property int $type 1:Market, 2: Food
 * @property float $total
 * @property float $coupon_discount_amount
 * @property float $delivery_fee
 * @property float $grand_total
 * @property float $private_payment_method_commission
 * @property float $private_total
 * @property float $private_delivery_fee
 * @property float $private_grand_total
 * @property bool $is_delivery_by_tiptop
 * @property string|null $branch_rating_value
 * @property Carbon|null $rated_at
 * @property string|null $rating_comment
 * @property string|null $driver_rating_value
 * @property int|null $has_good_food_quality_rating
 * @property int|null $has_good_packaging_quality_rating
 * @property int|null $has_good_order_accuracy_rating
 * @property int|null $rating_issue_id
 * @property Carbon|null $completed_at
 * @property string|null $notes
 * @property int $status
 *                     0: Cancelled,
 *                     1: Draft,
 *                     6: Waiting Courier,
 *                     10: Preparing,
 *                     16: On the way,
 *                     18: At the address,
 *                     20: Delivered,
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read \App\Models\Location $address
 * @property-read \App\Models\Branch $branch
 * @property-read \App\Models\Cart $cart
 * @property-read \App\Models\Chain $chain
 * @property-read \App\Models\Coupon|null $coupon
 * @property-read \App\Models\PaymentMethod $paymentMethod
 * @property-read Order|null $previousOrder
 * @property-read \App\Models\Taxonomy|null $ratingIssue
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Order atTheAddress()
 * @method static \Illuminate\Database\Eloquent\Builder|Order cancelled()
 * @method static \Illuminate\Database\Eloquent\Builder|Order delivered()
 * @method static \Illuminate\Database\Eloquent\Builder|Order draft()
 * @method static \Illuminate\Database\Eloquent\Builder|Order foods()
 * @method static \Illuminate\Database\Eloquent\Builder|Order groceries()
 * @method static \Illuminate\Database\Eloquent\Builder|Order new()
 * @method static \Illuminate\Database\Eloquent\Builder|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order onTheWay()
 * @method static Builder|Order onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Order preparing()
 * @method static \Illuminate\Database\Eloquent\Builder|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder|Order waitingCourier()
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereAddressId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereBranchRatingValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCartId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereChainId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCompletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCouponDiscountAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCouponId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDeliveryFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDriverRatingValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereGrandTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereHasGoodFoodQualityRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereHasGoodOrderAccuracyRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereHasGoodPackagingQualityRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereIsDeliveryByTiptop($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePaymentMethodId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePreviousOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePrivateDeliveryFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePrivateGrandTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePrivatePaymentMethodCommission($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePrivateTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereRatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereRatingComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereRatingIssueId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereReferenceCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUserId($value)
 * @method static Builder|Order withTrashed()
 * @method static Builder|Order withoutTrashed()
 * @mixin Eloquent
 * @property-read \App\Models\OrderAgentNote $agentNotes
 * @property-read int|null $agent_notes_count
 */
class Order extends Model
{
    use HasAppTypes;
    use HasTypes;
    use SoftDeletes;

    public const CHANNEL_GROCERY_OBJECT = 1;
    public const CHANNEL_FOOD_OBJECT = 2;

    public const STATUS_CANCELLED = 0;
    public const STATUS_DRAFT = 1;
    public const STATUS_NEW = 2; // Pending approval or rejection,
    public const STATUS_PREPARING = 10; // Confirmed
    public const STATUS_WAITING_COURIER = 12; // Ready, this case is ignored when delivery is made by the branch itself
    public const STATUS_ON_THE_WAY = 16;
    public const STATUS_AT_THE_ADDRESS = 18;
    public const STATUS_DELIVERED = 20;

    protected $casts = [
        'total' => 'double',
        'coupon_discount_amount' => 'double',
        'delivery_fee' => 'double',
        'grand_total' => 'double',
        'private_payment_method_commission' => 'double',
        'private_total' => 'double',
        'private_delivery_fee' => 'double',
        'private_grand_total' => 'double',
        'completed_at' => 'datetime',
        'is_delivery_by_tiptop' => 'boolean',
        'rated_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($query) {
            $query->reference_code = time();
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function chain(): BelongsTo
    {
        return $this->belongsTo(Chain::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }


    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    public function previousOrder(): BelongsTo
    {
        return $this->belongsTo(self::class, 'previous_order_id');
    }

    public function ratingIssue(): BelongsTo
    {
        return $this->belongsTo(Taxonomy::class, 'rating_issue_id');
    }

    public function agentNotes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(OrderAgentNote::class, 'order_id')->latest();
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', self::STATUS_CANCELLED);
    }

    public function scopeDraft($query)
    {
        return $query->where('status', self::STATUS_DRAFT);
    }

    public function scopeNew($query)
    {
        return $query->where('status', self::STATUS_NEW);
    }

    public function scopePreparing($query)
    {
        return $query->where('status', self::STATUS_PREPARING);
    }

    public function scopeWaitingCourier($query)
    {
        return $query->where('status', self::STATUS_WAITING_COURIER);
    }

    public function scopeOnTheWay($query)
    {
        return $query->where('status', self::STATUS_ON_THE_WAY);
    }

    public function scopeAtTheAddress($query)
    {
        return $query->where('status', self::STATUS_AT_THE_ADDRESS);
    }

    public function scopeDelivered($query)
    {
        return $query->where('status', self::STATUS_DELIVERED);
    }

    public function getStatusName()
    {
        return trans('strings.order_status_'.$this->status);
    }

    public static function getAllStatuses($status)
    {
        $statusesRaw = [
            self::STATUS_CANCELLED,
//            self::STATUS_DRAFT,
            self::STATUS_NEW,
            self::STATUS_PREPARING,
            self::STATUS_WAITING_COURIER,
            self::STATUS_ON_THE_WAY,
            self::STATUS_AT_THE_ADDRESS,
            self::STATUS_DELIVERED,
        ];
        $statuses = [];
        foreach ($statusesRaw as $item) {
            $statuses[] = [
                'id' => $item,
                'title' => trans('strings.order_status_'.$item),
                'isSelected' => $status === $item,
            ];
        }

        return $statuses;
    }

    public function getLateCssBgClass(): ?string
    {
        if ($this->status == self::STATUS_NEW) {
            $pastInMinutes = $this->created_at->diffInMinutes();
            if ($pastInMinutes > 7) {
                return 'bg-danger-darker text-white';
            } elseif ($pastInMinutes > 5) {
                return 'bg-warning-darker';
            } elseif ($pastInMinutes > 3) {
                return 'bg-warning-dark';
            } elseif ($pastInMinutes > 2) {
                return 'bg-warning';
            }

        }

        return null;
    }
}
