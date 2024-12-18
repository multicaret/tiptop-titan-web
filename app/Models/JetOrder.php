<?php

namespace App\Models;

use App\Traits\HasAppTypes;
use App\Traits\HasMediaTrait;
use App\Traits\HasTypes;
use App\Traits\RecordsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * App\Models\JetOrder
 *
 * @property int $id
 * @property string $reference_code
 * @property int $chain_id
 * @property int $branch_id
 * @property int $city_id
 * @property string $destination_full_name
 * @property string $destination_phone
 * @property string $destination_address
 * @property string|null $destination_latitude
 * @property string|null $destination_longitude
 * @property int|null $driver_id
 * @property float $total
 * @property float $delivery_fee
 * @property float $grand_total
 * @property float $grand_total_before_agent_manipulation
 * @property int|null $cancellation_reason_id
 * @property string|null $cancellation_reason_note
 * @property int|null $delivery_time
 * @property string|null $restaurant_notes
 * @property string|null $private_notes This column is generic, for now it has the 'discount_method_id' for orders with coupons from the old DB
 * @property string|null $client_notes
 * @property int $status
 *                     0: Cancelled,
 *                     1: Draft,
 *                     22: Assigning driver,
 *                     6: Waiting Courier,
 *                     16: On the way,
 *                     18: At the address,
 *                     20: Delivered,
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property array|null $agent_notes
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Activity[] $activity
 * @property-read int|null $activity_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\OrderAgentNote[] $agentNotes
 * @property-read int|null $agent_notes_count
 * @property-read \App\Models\Branch $branch
 * @property-read \App\Models\Taxonomy|null $cancellationReason
 * @property-read \App\Models\Chain $chain
 * @property-read \App\Models\User|null $driver
 * @property-read mixed $gallery
 * @property-read bool $is_food
 * @property-read bool $is_grocery
 * @property-read mixed $status_name
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection|Media[] $media
 * @property-read int|null $media_count
 * @property-read \App\Models\PaymentMethod $paymentMethod
 * @property-read \App\Models\TookanInfo|null $tookanInfo
 * @method static \Illuminate\Database\Eloquent\Builder|JetOrder atTheAddress()
 * @method static \Illuminate\Database\Eloquent\Builder|JetOrder cancelled()
 * @method static \Illuminate\Database\Eloquent\Builder|JetOrder delivered()
 * @method static \Illuminate\Database\Eloquent\Builder|JetOrder draft()
 * @method static \Illuminate\Database\Eloquent\Builder|JetOrder foods()
 * @method static \Illuminate\Database\Eloquent\Builder|JetOrder groceries()
 * @method static \Illuminate\Database\Eloquent\Builder|JetOrder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|JetOrder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|JetOrder onTheWay()
 * @method static \Illuminate\Database\Query\Builder|JetOrder onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|JetOrder query()
 * @method static \Illuminate\Database\Eloquent\Builder|JetOrder waitingCourier()
 * @method static \Illuminate\Database\Eloquent\Builder|JetOrder whereAgentNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JetOrder whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JetOrder whereCancellationReasonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JetOrder whereCancellationReasonNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JetOrder whereChainId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JetOrder whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JetOrder whereClientNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JetOrder whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JetOrder whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JetOrder whereDeliveryFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JetOrder whereDeliveryTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JetOrder whereDestinationAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JetOrder whereDestinationFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JetOrder whereDestinationLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JetOrder whereDestinationLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JetOrder whereDestinationPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JetOrder whereDriverId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JetOrder whereGrandTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JetOrder whereGrandTotalBeforeAgentManipulation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JetOrder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JetOrder wherePrivateNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JetOrder whereReferenceCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JetOrder whereRestaurantNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JetOrder whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JetOrder whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JetOrder whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|JetOrder withTrashed()
 * @method static \Illuminate\Database\Query\Builder|JetOrder withoutTrashed()
 * @mixin \Eloquent
 * @noinspection PhpFullyQualifiedNameUsageInspection
 * @noinspection PhpUnnecessaryFullyQualifiedNameInspection
 */
class JetOrder extends Model implements HasMedia
{
    use HasAppTypes;
    use HasTypes;
    use SoftDeletes;
    use RecordsActivity;
    use HasMediaTrait;

    public const STATUS_CANCELLED = 0;
    public const STATUS_DRAFT = 1;
    public const STATUS_ASSIGNING_COURIER = 22; // Pending approval or rejection,
    public const STATUS_WAITING_COURIER = 12; // Ready, this case is ignored when delivery is made by the branch itself
    public const STATUS_ON_THE_WAY = 16;
    public const STATUS_AT_THE_ADDRESS = 18;
    public const STATUS_DELIVERED = 20;

    public const OTHER_CANCELLATION_REASON_ID = 0;

    protected $casts = [
        'total' => 'double',
        'delivery_fee' => 'double',
        'grand_total' => 'double',
        'grand_total_before_agent_manipulation' => 'double',
        'completed_at' => 'datetime',
        'agent_notes' => 'array'
    ];

    protected $guarded = [];

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


    public function chain(): BelongsTo
    {
        return $this->belongsTo(Chain::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function cancellationReason(): BelongsTo
    {
        return $this->belongsTo(Taxonomy::class, 'cancellation_reason_id');
    }

    public function agentNotes(): HasMany
    {
        return $this->hasMany(OrderAgentNote::class, 'jet_order_id')->latest();
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', self::STATUS_CANCELLED);
    }

    public function scopeDraft($query)
    {
        return $query->where('status', self::STATUS_DRAFT);
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

    public function getStatusNameAttribute()
    {
        return $this->getStatusName();
    }

    public function getAllStatuses($statusesToSelectFrom = null)
    {
        if (is_null($statusesToSelectFrom)) {
            $statusesToSelectFrom = [
                self::STATUS_DRAFT,
                self::STATUS_ASSIGNING_COURIER,
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

    public function getPermittedStatus(): array
    {
        switch ($this->status) {
            case self::STATUS_ASSIGNING_COURIER:
                return $this->getAllStatuses([
                    self::STATUS_WAITING_COURIER,
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

    public function getLateCssBgClass(): ?string
    {
        if ($this->status == self::STATUS_ASSIGNING_COURIER) {
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
            'updated_at' => [
                'title' => 'Update',
                'type' => 'datetime-normal',
            ],
        ];

        if (array_key_exists($columnName, $visibleColumns)) {
            return $visibleColumns[$columnName];
        }

        return null;
    }

    public static function getActivityLogDifference($columnName, $value)
    {
        $activityLogDifferenceItem = self::getVisibleColumnsInActivityLogDifference($columnName);
        if ( ! is_null($activityLogDifferenceItem)) {
            $activityLogDifferenceItem['value'] = self::getFormattedActivityLogDifferenceItem($activityLogDifferenceItem,
                $columnName, $value);

            return $activityLogDifferenceItem;
        }

        return false;
    }

    public function getGalleryAttribute()
    {
        return $this->getMediaForUploader('gallery');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('gallery')
             ->withResponsiveImages()
             ->registerMediaConversions(function (Media $media) {
                 $this->addMediaConversion('HD')
                      ->width(1024)
                      ->height(1024);
             });
    }

    public function tookanInfo()
    {
        return $this->morphOne(TookanInfo::class, 'tookanable');
    }
}
