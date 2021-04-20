<?php

namespace App\Models\OldModels;


use App\Models\Branch;
use App\Models\Order;
use App\Models\PaymentMethod;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\OldModels\OldOrder
 *
 * @property int $id
 * @property string $code
 * @property int|null $customer_id
 * @property int $branch_id
 * @property int|null $driver_id
 * @property int $basket_id
 * @property int|null $address_id
 * @property string|null $type
 * @property string|null $status PENDING, APPROVED, PREPARING, DELIVERING, DELIVERED, RECEIVED, DECLINED
 * @property string|null $restaurant_notes
 * @property string|null $customer_notes
 * @property string|null $payment_status
 * @property string $price
 * @property string|null $cost After discount.
 * @property string $app_share
 * @property string $branch_share
 * @property string $app_percentage
 * @property string $amount_range_from
 * @property string|null $amount_range_to
 * @property string $delivery_fee
 * @property string|null $delivery_type RESTAURANT | APP
 * @property int|null $driver_approved
 * @property string|null $drivers_notified
 * @property int|null $currently_notified_driver_id
 * @property string|null $notify_date
 * @property int|null $number_of_notified
 * @property string|null $discount
 * @property int|null $discount_method_id
 * @property string|null $discount_method_type
 * @property string $discount_method_amount
 * @property string|null $payment_method BANKCARD, CASH
 * @property string|null $due_date
 * @property string|null $scheduled_date
 * @property string $rating
 * @property int|null $rating_count
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $workiom_id
 * @property string|null $zoho_id
 * @property string|null $agent_device
 * @property string|null $agent_os
 * @property int|null $cancellation_reason_id
 * @property string|null $note
 * @property string|null $zoho_books_invoice_id
 * @property int $is_grocery
 * @property int $delivery_time
 * @property-read Branch $branch
 * @property-read mixed $chain_id
 * @property-read mixed $city_id
 * @property-read mixed $is_delivery_by_tiptop
 * @property-read mixed $is_grocery_food
 * @property-read mixed $payment_method_id
 * @property-read mixed $private_notes
 * @property-read mixed $restaurant_share_result
 * @method static Builder|OldOrder newModelQuery()
 * @method static Builder|OldOrder newQuery()
 * @method static Builder|OldOrder query()
 * @method static Builder|OldOrder whereAddressId($value)
 * @method static Builder|OldOrder whereAgentDevice($value)
 * @method static Builder|OldOrder whereAgentOs($value)
 * @method static Builder|OldOrder whereAmountRangeFrom($value)
 * @method static Builder|OldOrder whereAmountRangeTo($value)
 * @method static Builder|OldOrder whereAppPercentage($value)
 * @method static Builder|OldOrder whereAppShare($value)
 * @method static Builder|OldOrder whereBasketId($value)
 * @method static Builder|OldOrder whereBranchId($value)
 * @method static Builder|OldOrder whereBranchShare($value)
 * @method static Builder|OldOrder whereCancellationReasonId($value)
 * @method static Builder|OldOrder whereCode($value)
 * @method static Builder|OldOrder whereCost($value)
 * @method static Builder|OldOrder whereCreatedAt($value)
 * @method static Builder|OldOrder whereCurrentlyNotifiedDriverId($value)
 * @method static Builder|OldOrder whereCustomerId($value)
 * @method static Builder|OldOrder whereCustomerNotes($value)
 * @method static Builder|OldOrder whereDeletedAt($value)
 * @method static Builder|OldOrder whereDeliveryFee($value)
 * @method static Builder|OldOrder whereDeliveryTime($value)
 * @method static Builder|OldOrder whereDeliveryType($value)
 * @method static Builder|OldOrder whereDiscount($value)
 * @method static Builder|OldOrder whereDiscountMethodAmount($value)
 * @method static Builder|OldOrder whereDiscountMethodId($value)
 * @method static Builder|OldOrder whereDiscountMethodType($value)
 * @method static Builder|OldOrder whereDriverApproved($value)
 * @method static Builder|OldOrder whereDriverId($value)
 * @method static Builder|OldOrder whereDriversNotified($value)
 * @method static Builder|OldOrder whereDueDate($value)
 * @method static Builder|OldOrder whereId($value)
 * @method static Builder|OldOrder whereIsGrocery($value)
 * @method static Builder|OldOrder whereNote($value)
 * @method static Builder|OldOrder whereNotifyDate($value)
 * @method static Builder|OldOrder whereNumberOfNotified($value)
 * @method static Builder|OldOrder wherePaymentMethod($value)
 * @method static Builder|OldOrder wherePaymentStatus($value)
 * @method static Builder|OldOrder wherePrice($value)
 * @method static Builder|OldOrder whereRating($value)
 * @method static Builder|OldOrder whereRatingCount($value)
 * @method static Builder|OldOrder whereRestaurantNotes($value)
 * @method static Builder|OldOrder whereScheduledDate($value)
 * @method static Builder|OldOrder whereStatus($value)
 * @method static Builder|OldOrder whereType($value)
 * @method static Builder|OldOrder whereUpdatedAt($value)
 * @method static Builder|OldOrder whereWorkiomId($value)
 * @method static Builder|OldOrder whereZohoBooksInvoiceId($value)
 * @method static Builder|OldOrder whereZohoId($value)
 * @mixin Eloquent
 */
class OldOrder extends OldModel
{
    protected $table = 'jo3aan_orders';
    protected $primaryKey = 'id';


    public const IS_DEFAULT = 1;

    public const STATUS_ACTIVE = 'ACTIVE';
    public const STATUS_DISABLED = 'DISABLED';
    public const STATUS_SUSPENDED = 'SUSPENDED';

    public function attributesComparing(): array
    {
        $attributesKeys = [
            'id' => 'id',
            'code' => 'reference_code',
            'customer_id' => 'user_id',
            'chain_id' => 'chain_id',
            'branch_id' => 'branch_id',
            'city_id' => 'city_id',
            'driver_id' => 'driver_id',
//            'basket_id' => 'cart_id',
            'address_id' => 'address_id',
//            'status' => '', // typeComparing()
            'restaurant_notes' => 'restaurant_notes',
            'customer_notes' => 'notes',
//            'payment_status' => '',
            'price' => 'total',
            'cost' => 'grand_total',
            'app_share' => 'tiptop_share_result',
            'restaurant_share_result' => 'restaurant_share_result',
            'app_percentage' => 'tiptop_share_percentage',
            'delivery_fee' => 'delivery_fee',
            'is_delivery_by_tiptop' => 'is_delivery_by_tiptop',
//            'discount' => '',
            'private_notes' => 'private_notes',
//            'discount_method_type' => '',
            'discount_method_amount' => 'coupon_discount_amount',
            'payment_method_id' => 'payment_method_id',
            'rating' => 'branch_rating_value',
            'agent_device' => 'agent_device',
            'agent_os' => 'agent_os',
//            'cancellation_reason_id' => 'cancellation_reason_id',
//            'note' => '',
            'is_grocery_food' => 'type',
            'delivery_time' => 'delivery_time',
            'deleted_at' => 'deleted_at',
            'created_at' => 'created_at',
            'updated_at' => 'updated_at',
        ];

        return $attributesKeys;
    }

    public static function typeComparing(): array
    {
        return [
            'PENDING' => Order::STATUS_PREPARING,
            'CANCELED' => Order::STATUS_CANCELLED,
            'DELIVERED' => Order::STATUS_DELIVERED,
            'DECLINED' => Order::STATUS_CANCELLED,
            'NOT_DELIVERED' => Order::STATUS_CANCELLED,
            'PREPARING' => Order::STATUS_PREPARING,
            'READY' => Order::STATUS_WAITING_COURIER,
            'ON_THE_WAY' => Order::STATUS_ON_THE_WAY,
            'SCHEDULED' => Order::STATUS_SCHEDULED,
        ];
    }

    public function getIsDeliveryByTiptopAttribute()
    {
        return $this->delivery_type === 'APP';
    }

    public function getPaymentMethodIdAttribute()
    {
        $titleValue = $this->payment_method === 'CASH' ? 'Cash on Delivery' : 'Credit Card on Delivery';

        return optional(PaymentMethod::whereTranslation('title', $titleValue)->first())->id;
    }

    public function getIsGroceryFoodAttribute()
    {
        return $this->is_grocery === 1 ? Order::CHANNEL_GROCERY_OBJECT : Order::CHANNEL_FOOD_OBJECT;
    }

    public function getPrivateNotesAttribute()
    {
        return $this->discount_method_type === 'Modules\CouponSystem\Entities\Instance' ? $this->discount_method_id : null;
    }

    public function branch(): BelongsTo
    {
        return $this->setConnection('mysql')->belongsTo(Branch::class);
    }

    public function getChainIdAttribute()
    {
        return optional($this->branch)->chain_id;
    }

    public function getCityIdAttribute()
    {
        return optional($this->branch)->city_id;
    }

    public function getRestaurantShareResultAttribute()
    {
        return $this->branch_share < 0 ? ($this->branch_share * -1) : $this->branch_share;
    }
}
