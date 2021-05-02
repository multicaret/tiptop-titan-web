<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\TookanInfo
 *
 * @property int $id
 * @property string $tookanable_type
 * @property int $tookanable_id
 * @property string|null $tookan_id
 * @property string $job_pickup_id
 * @property string $job_delivery_id
 * @property string|null $delivery_tracking_link
 * @property string|null $pickup_tracking_link
 * @property string|null $job_hash
 * @property string|null $job_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Model|\Eloquent $tookanable
 * @method static \Illuminate\Database\Eloquent\Builder|TookanInfo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TookanInfo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TookanInfo query()
 * @method static \Illuminate\Database\Eloquent\Builder|TookanInfo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TookanInfo whereDeliveryTrackingLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TookanInfo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TookanInfo whereJobDeliveryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TookanInfo whereJobHash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TookanInfo whereJobPickupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TookanInfo whereJobToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TookanInfo wherePickupTrackingLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TookanInfo whereTookanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TookanInfo whereTookanableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TookanInfo whereTookanableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TookanInfo whereUpdatedAt($value)
 * @mixin \Eloquent
 * @noinspection PhpFullyQualifiedNameUsageInspection
 * @noinspection PhpUnnecessaryFullyQualifiedNameInspection
 */
class TookanInfo extends Model
{
    use HasFactory;

    protected $table = 'tookans';

    protected $guarded = [];

    public function tookanable()
    {
        return $this->morphTo();
    }
}
