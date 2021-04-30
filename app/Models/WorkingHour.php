<?php

namespace App\Models;

use App\Http\Resources\WorkingHourResource;
use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\WorkingHour
 *
 * @property int $id
 * @property string $workable_type
 * @property int $workable_id
 * @property int $day
 * @property string|null $opens_at
 * @property string|null $closes_at
 * @property bool $is_day_off
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Model|\Eloquent $workable
 * @method static Builder|WorkingHour newModelQuery()
 * @method static Builder|WorkingHour newQuery()
 * @method static Builder|WorkingHour query()
 * @method static Builder|WorkingHour whereClosesAt($value)
 * @method static Builder|WorkingHour whereCreatedAt($value)
 * @method static Builder|WorkingHour whereDay($value)
 * @method static Builder|WorkingHour whereId($value)
 * @method static Builder|WorkingHour whereIsDayOff($value)
 * @method static Builder|WorkingHour whereOpensAt($value)
 * @method static Builder|WorkingHour whereUpdatedAt($value)
 * @method static Builder|WorkingHour whereWorkableId($value)
 * @method static Builder|WorkingHour whereWorkableType($value)
 * @mixin Eloquent
 */
class WorkingHour extends Model
{
    protected $casts = [
        'is_day_off' => 'boolean',
    ];

    public function workable()
    {
        return $this->morphTo();
    }

    public static function retrieve($object, Carbon $date = null)
    {
        if (is_null($date)) {
            $dayNumber = now()->format('N');
        } else {
            $dayNumber = $date->format('N');
        }
        $workingHours = [
            'offs' => [],
            'offsRendered' => '-',
            'opensAt' => null,
            'closesAt' => null,
            'isOpen' => false,
        ];

        foreach ($object->workingHours as $workingHour) {
            if ($workingHour->is_day_off) {
                $workingHours['offs'][] = trans('strings.working_day_'.$workingHour->day);
            } else {
                if ($workingHour->day == $dayNumber) {
                    if ( ! isset($workingHours['opensAt'])) {
                        $workingHours['opensAt'] = Carbon::parse($workingHour->opens_at)->format('H:i');
                    }
                    if ( ! isset($workingHours['closesAt'])) {
                        $workingHours['closesAt'] = Carbon::parse($workingHour->closes_at)->format('H:i');
                    }
                }
            }
        }
        $workingHours['offsRendered'] = implode(' - ', $workingHours['offs']);

        $todayWorkingHours = WorkingHour::where('workable_id', $object->id)
                                        ->where('workable_type', get_class($object))
                                        ->where('day', $dayNumber)
                                        ->first();
        if ($todayWorkingHours) {
            if (is_null($todayWorkingHours->opens_at)) {
                $yesterdayWorkingHours = WorkingHour::where('workable_id', $object->id)
                                                    ->where('workable_type', get_class($object))
                                                    ->where('day', Carbon::yesterday()->format('N'))
                                                    ->first();
                if ($yesterdayWorkingHours) {
                    if (is_null($yesterdayWorkingHours->opens_at)) {
                        $workingHours['isOpen'] = false;
                    } else {
                        $yesterday_opens_at = Carbon::createFromFormat('H:i:s', $yesterdayWorkingHours->opens_at);
                        $yesterday_closes_at = Carbon::createFromFormat('H:i:s', $yesterdayWorkingHours->closes_at);
                        if ($yesterday_opens_at->gt($yesterday_closes_at)) {
                            $workingHours['isOpen'] = Carbon::now()->lt($yesterday_closes_at);
                        }
                    }
                }
            } else {
                $today_opens_at = Carbon::createFromFormat('H:i:s', $todayWorkingHours->opens_at);
                $today_closes_at = Carbon::createFromFormat('H:i:s', $todayWorkingHours->closes_at);

                if ($today_opens_at->gt($today_closes_at)) {
                    $today_closes_at->addDays(1);
                }

                $workingHours['isOpen'] = $today_opens_at->eq($today_closes_at)/* if CARBON eq true, thus, this place is open 24Hours */
                    || (Carbon::now()->gt($today_opens_at) && Carbon::now()->lt($today_closes_at));

                if ($todayWorkingHours->is_day_off) {
                    $workingHours['isOpen'] = false;
                }
            }
        }
        $workingHours['schedule'] = WorkingHourResource::collection($object->workingHours);

        return $workingHours;
    }
}
