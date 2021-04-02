<?php

namespace App\Models;

use App\Traits\HasAppTypes;
use App\Traits\HasTypes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
 * @property string|null $branch_rating_value
 * @property string|null $rating_comment
 * @property string|null $driver_rating_value
 * @property int|null $has_good_food_quality_rating
 * @property int|null $has_good_packaging_quality_rating
 * @property int|null $has_good_order_accuracy_rating
 * @property int|null $rating_issue_id
 * @property \Illuminate\Support\Carbon|null $completed_at
 * @property string|null $notes
 * @property int $status
 *             0: Cancelled,
 *             1: Draft,
 *             6: Waiting Courier,
 *             10: Preparing,
 *             16: On the way,
 *             18: At the address,
 *             20: Delivered,
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Location $address
 * @property-read \App\Models\Branch $branch
 * @property-read \App\Models\Cart $cart
 * @property-read \App\Models\Chain $chain
 * @property-read \App\Models\Coupon|null $coupon
 * @property-read \App\Models\PaymentMethod $paymentMethod
 * @property-read Order|null $previousOrder
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product[] $products
 * @property-read int|null $products_count
 * @property-read \App\Models\Taxonomy|null $ratingIssue
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order newQuery()
 * @method static \Illuminate\Database\Query\Builder|Order onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Order query()
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
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePaymentMethodId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePreviousOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePrivateDeliveryFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePrivateGrandTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePrivatePaymentMethodCommission($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePrivateTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereRatingComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereRatingIssueId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereReferenceCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|Order withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Order withoutTrashed()
 * @mixin \Eloquent
 * @property string|null $rating_value
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereRatingValue($value)
 */
class Order extends Model
{
    use SoftDeletes,
        HasTypes,
        HasAppTypes;

    const TYPE_GROCERY_OBJECT = 1;
    const TYPE_FOOD_OBJECT = 2;

    const STATUS_CANCELLED = 0;
    const STATUS_DRAFT = 1;
    const STATUS_NEW = 2; // Pending approval or rejection,
    const STATUS_PREPARING = 10; // Confirmed
    const STATUS_WAITING_COURIER = 12; // Ready, this case is ignored when delivery is made by the branch itself
    const STATUS_ON_THE_WAY = 16;
    const STATUS_AT_THE_ADDRESS = 18;
    const STATUS_DELIVERED = 20;

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
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($query) {
            $query->reference_code = time();
        });
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function chain(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Chain::class);
    }

    public function branch(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }


    public function cart(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    public function paymentMethod(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function address(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function coupon(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    public function previousOrder(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(self::class, 'previous_order_id');
    }

    public function ratingIssue(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Taxonomy::class, 'rating_issue_id');
    }

    public function products(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'order_product', 'order_id', 'product_id')
                    ->withPivot('product_object')
                    ->withTimestamps();
    }

    public function scopeNew($query)
    {
        return $query->where('status', self::STATUS_NEW);
    }


}
