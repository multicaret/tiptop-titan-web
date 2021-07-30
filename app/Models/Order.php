<?php

namespace App\Models;

use App\Traits\HasAppTypes;
use App\Traits\HasTypes;
use App\Traits\RecordsActivity;
use Eloquent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
 * @property int|null $cart_id
 * @property int $payment_method_id
 * @property int $address_id
 * @property int|null $coupon_id
 * @property int $city_id
 * @property int|null $driver_id
 * @property int|null $previous_order_id
 * @property int $type 1:Market, 2: Food
 * @property float $total
 * @property float $coupon_discount_amount
 * @property float $delivery_fee
 * @property float $grand_total
 * @property float $grand_total_before_agent_manipulation
 * @property float $private_payment_method_commission
 * @property float $private_total
 * @property float $private_delivery_fee
 * @property float $private_grand_total
 * @property bool $is_delivery_by_tiptop
 * @property string|null $branch_rating_value
 * @property Carbon|null $rated_at
 * @property string|null $rating_comment
 * @property int|null $rating_issue_id
 * @property bool|null $has_good_food_quality_rating
 * @property bool|null $has_good_packaging_quality_rating
 * @property bool|null $has_good_order_accuracy_rating
 * @property string|null $driver_rating_value
 * @property string|null $driver_rating_comment
 * @property string|null $driver_rated_at
 * @property int|null $cancellation_reason_id
 * @property string|null $cancellation_reason_note
 * @property int|null $delivery_time
 * @property float $tiptop_share_result
 * @property float $tiptop_share_percentage is tiptop_share_percentage, which is taken directly from commission column in Branch
 * @property float $restaurant_share_result
 * @property string|null $agent_device
 * @property string|null $agent_os
 * @property string|null $restaurant_notes
 * @property string|null $private_notes This column is generic, for now it has the 'discount_method_id' for orders with coupons from the old DB
 * @property Carbon|null $completed_at
 * @property string|null $customer_notes
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
 * @property string|null $zoho_books_invoice_id
 * @property string|null $zoho_books_payment_id
 * @property-read Collection|\App\Models\Activity[] $activity
 * @property-read int|null $activity_count
 * @property-read \App\Models\Location $address
 * @property-read Collection|\App\Models\OrderAgentNote[] $agentNotes
 * @property-read int|null $agent_notes_count
 * @property-read \App\Models\Branch $branch
 * @property-read \App\Models\Taxonomy|null $cancellationReason
 * @property-read \App\Models\Cart|null $cart
 * @property-read \App\Models\Chain $chain
 * @property-read \App\Models\Coupon|null $coupon
 * @property-read \App\Models\User|null $driver
 * @property-read bool $is_food
 * @property-read bool $is_grocery
 * @property-read mixed $status_name
 * @property-read \App\Models\PaymentMethod $paymentMethod
 * @property-read Order|null $previousOrder
 * @property-read \App\Models\Taxonomy|null $ratingIssue
 * @property-read \App\Models\TookanInfo|null $tookanInfo
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
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereAgentDevice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereAgentOs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereBranchRatingValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCancellationReasonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCancellationReasonNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCartId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereChainId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCompletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCouponDiscountAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCouponId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCustomerNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDeliveryFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDeliveryTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDriverId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDriverRatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDriverRatingComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDriverRatingValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereGrandTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereGrandTotalBeforeAgentManipulation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereHasGoodFoodQualityRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereHasGoodOrderAccuracyRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereHasGoodPackagingQualityRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereIsDeliveryByTiptop($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePaymentMethodId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePreviousOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePrivateDeliveryFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePrivateGrandTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePrivateNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePrivatePaymentMethodCommission($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePrivateTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereRatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereRatingComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereRatingIssueId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereReferenceCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereRestaurantNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereRestaurantShareResult($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereTiptopSharePercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereTiptopShareResult($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereZohoBooksInvoiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereZohoBooksPaymentId($value)
 * @method static Builder|Order withTrashed()
 * @method static Builder|Order withoutTrashed()
 * @mixin Eloquent
 * @noinspection PhpFullyQualifiedNameUsageInspection
 * @noinspection PhpUnnecessaryFullyQualifiedNameInspection
 */
class Order extends Model
{
    use HasAppTypes;
    use HasTypes;
    use SoftDeletes;
    use RecordsActivity;

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
    public const STATUS_SCHEDULED = 25;


    public const OTHER_CANCELLATION_REASON_ID = 0;

    protected $casts = [
        'total' => 'double',
        'coupon_discount_amount' => 'double',
        'delivery_fee' => 'double',
        'grand_total' => 'double',
        'grand_total_before_agent_manipulation' => 'double',
        'private_payment_method_commission' => 'double',
        'private_total' => 'double',
        'private_delivery_fee' => 'double',
        'private_grand_total' => 'double',
        'completed_at' => 'datetime',
        'rated_at' => 'datetime',
        'is_delivery_by_tiptop' => 'boolean',
        'has_good_food_quality_rating' => 'boolean',
        'has_good_packaging_quality_rating' => 'boolean',
        'has_good_order_accuracy_rating' => 'boolean',
    ];

    public function tagEndUser()
    {
        $tag = Taxonomy::select(['id', 'type'])
                       ->where('type', Taxonomy::TYPE_END_USER_TAGS)
                       ->whereHas('translations', function ($query) {
                            $query->when($this->type == Order::CHANNEL_FOOD_OBJECT, function ($query) {
                                $query->where('title', 'Food');

                            })->when($this->type == Order::CHANNEL_GROCERY_OBJECT, function ($query) {
                                $query->where('title', 'Market');
                            });

                        })->first();
        if ( ! empty($tag)) {
            $this->user->tags()->sync([$tag->id]);
        }
    }

    private static function getFormattedActivityLogDifferenceItem(
        ?array $activityLogDifferenceItem,
        $columnName,
        $value
    ) {
        switch ($activityLogDifferenceItem['type']) {
            case 'yes-no':
                return $value ? 'Yes' : 'No';
            case 'trans':
                return trans('strings.order_'.$columnName.'_'.$value);
            case 'currency-formatted':
                return Currency::formatHtml($value);
            case 'datetime-normal':
                return Carbon::parse($value)->format(config('defaults.datetime.normal_format'));
            case null:
            default:
                return $value;
        }
    }

    /**
     * @param $value
     * @return array|null
     */
    private static function getVisibleColumnsInActivityLogDifference($columnName): ?array
    {
        $visibleColumns = [
            'payment_method_id' => [
                'title' => 'Payment method id',
                'type' => null,
            ],
            'address_id' => [
                'title' => 'Address id',
                'type' => null,
            ],
            'coupon_id' => [
                'title' => 'Coupon id',
                'type' => null,
            ],
            'total' => [
                'title' => 'Total',
                'type' => 'currency-formatted',
            ],
            'coupon_discount_amount' => [
                'title' => 'Coupon discount amount',
                'type' => 'currency-formatted',
            ],
            'delivery_fee' => [
                'title' => 'Delivery fee',
                'type' => 'currency-formatted',
            ],
            'grand_total' => [
                'title' => 'Grand total',
                'type' => 'currency-formatted',
            ],
            'branch_rating_value' => [
                'title' => 'Branch rating value',
                'type' => null,
            ],
            'rated_at' => [
                'title' => 'Rated at',
                'type' => 'datetime-normal',
            ],
            'rating_comment' => [
                'title' => 'Rating comment',
                'type' => null,
            ],
            'rating_issue_id' => [
                'title' => 'Rating issue id',
                'type' => null,
            ],
            'has_good_food_quality_rating' => [
                'title' => 'Has good food quality rating',
                'type' => 'yes-no',
            ],
            'has_good_packaging_quality_rating' => [
                'title' => 'Has good packaging quality rating',
                'type' => 'yes-no',
            ],
            'has_good_order_accuracy_rating' => [
                'title' => 'Has good order accuracy rating',
                'type' => 'yes-no',
            ],
            'driver_rating_value' => [
                'title' => 'Driver rating value',
                'type' => null,
            ],
            'driver_rating_comment' => [
                'title' => 'Driver rating comment',
                'type' => null,
            ],
            'driver_rated_at' => [
                'title' => 'Driver rated at',
                'type' => 'datetime-normal',
            ],
            'cancellation_reason_note' => [
                'title' => 'Cancellation reason note',
                'type' => null,
            ],
            'restaurant_notes' => [
                'title' => 'Restaurant notes',
                'type' => null,
            ],
            'agent_note' => [
                'title' => 'Agent note',
                'type' => null,
            ],
            'customer_notes' => [
                'title' => 'Customer notes',
                'type' => null,
            ],
            'status' => [
                'title' => 'Status',
                'type' => 'trans',
            ],
        ];

        if (array_key_exists($columnName, $visibleColumns)) {
            return $visibleColumns[$columnName];
        }

        return null;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withTrashed();
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
        return $this->belongsTo(Location::class)->withTrashed();
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function previousOrder(): BelongsTo
    {
        return $this->belongsTo(self::class, 'previous_order_id');
    }

    public function ratingIssue(): BelongsTo
    {
        return $this->belongsTo(Taxonomy::class, 'rating_issue_id');
    }

    public function cancellationReason(): BelongsTo
    {
        return $this->belongsTo(Taxonomy::class, 'cancellation_reason_id');
    }

    public function agentNotes(): HasMany
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

    public function getStatusNameAttribute()
    {
        return $this->getStatusName();
    }

    public function getStatusName()
    {
        return trans('strings.order_status_'.$this->status);
    }

    public function getPermittedStatus(): array
    {
        switch ($this->status) {
            case self::STATUS_NEW:
                return $this->getAllStatuses([
                    self::STATUS_PREPARING,
                    self::STATUS_CANCELLED,
                ]);
            case self::STATUS_PREPARING:
                return $this->getAllStatuses([
                    self::STATUS_WAITING_COURIER,
                    self::STATUS_ON_THE_WAY,
                    self::STATUS_CANCELLED,
                ]);
            case self::STATUS_WAITING_COURIER:
                return $this->getAllStatuses([
                    self::STATUS_ON_THE_WAY,
                    self::STATUS_DELIVERED,
                    self::STATUS_CANCELLED,
                ]);
            case self::STATUS_ON_THE_WAY:
                return $this->getAllStatuses([
                    self::STATUS_AT_THE_ADDRESS,
                    self::STATUS_DELIVERED,
                    self::STATUS_CANCELLED,
                ]);
            case self::STATUS_AT_THE_ADDRESS:
                return $this->getAllStatuses([
                    self::STATUS_DELIVERED,
                    self::STATUS_CANCELLED,
                ]);
            case self::STATUS_DELIVERED:
                return $this->getAllStatuses([
                    self::STATUS_CANCELLED,
                ]);
            case self::STATUS_CANCELLED:
                /*if ($this->status != self::STATUS_CANCELLED) {
                    return $this->getAllStatuses([
                        $this->status,
                        self::STATUS_CANCELLED,
                    ]);
                } else {*/
                return $this->getAllStatuses([
                    self::STATUS_CANCELLED,
                ]);
//                }
            default;
                return [];
        }
    }

    public function getAllStatuses($statusesToSelectFrom = null)
    {
        if (is_null($statusesToSelectFrom)) {
            $statusesToSelectFrom = [
//            self::STATUS_DRAFT,
                self::STATUS_NEW,
                self::STATUS_PREPARING,
                self::STATUS_WAITING_COURIER,
                self::STATUS_ON_THE_WAY,
                self::STATUS_AT_THE_ADDRESS,
                self::STATUS_DELIVERED,
                self::STATUS_CANCELLED,
            ];
        }
        $statuses = [];
        foreach ($statusesToSelectFrom as $item) {
            $statuses[] = [
                'id' => $item,
                'title' => trans('strings.order_status_'.$item),
                'isSelected' => $this->status === $item,
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

    public function tookanInfo()
    {
        return $this->morphOne(TookanInfo::class, 'tookanable');
    }

    public function getStatusesIntervals()
    {
        $total = 0;
        $statusesIntervals = [];
        $lastActivity = $this->created_at;
        foreach ($this->activity as $activity) {
            if (isset($activity->differences->status) && $activity->differences->status != Order::STATUS_NEW) {
                $time = $activity->created_at->diffInSeconds($lastActivity);
                $total += $time;
                $statusesIntervals[$activity->differences->status] = $time;
//                $times[$activity->differences->status] = CarbonInterval::seconds($time)->cascade()->forHumans();
                $lastActivity = $activity->created_at;
            }
        }

        return [$statusesIntervals, $total];
    }
}
