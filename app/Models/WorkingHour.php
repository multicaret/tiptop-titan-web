<?php

namespace App\Models;

use App\Http\Resources\WorkingHourResource;
use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

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
 * @noinspection PhpFullyQualifiedNameUsageInspection
 * @noinspection PhpUnnecessaryFullyQualifiedNameInspection
 */
class WorkingHour extends Model
{
    protected $casts = [
        'is_day_off' => 'boolean',
    ];

    private static function getNextWorkingDayNumber(int $dayNumber, $workingHours): int
    {
        $workingDaysNumber = $workingHours->pluck('is_day_off', 'day')->filter(fn($v) => ! $v);
        $workingDaysNumber->forget($dayNumber);
        $tempDayNumber = $dayNumber;
        if ($tempDayNumber == 7) {
            $tempDayNumber = 1;
        }

        if ( ! $workingDaysNumber->has($tempDayNumber)) {
            $tempDayNumber = self::getNextWorkingDayNumber($tempDayNumber + 1, $workingHours);
        }

        return $tempDayNumber;

    }

    public function workable()
    {
        return $this->morphTo();
    }

    public static function retrieve($object, Carbon $dateTime = null)
    {
        if (is_null($dateTime)) {
            $dayNumber = (int) now()->format('N');
            $selectTime = now()->format('H:i:s');
        } else {
            $dayNumber = $dateTime->format('N');
            $selectTime = $dateTime->format('H:i:s');
        }
        $workingHours = [
            'offs' => [],
            'offsRendered' => '-',
            'opensAt' => null,
            'closesAt' => null,
            'schedule' => null,
            'isOpen' => false,
        ];
        if ($object->workingHours->isEmpty()) {
            $object->workingHours = $object->getWorkingHours();
        } else {
            $timeSlot = WorkingHour::where('day', $dayNumber)
                                   ->whereWorkableId($object->id)
                                   ->where('workable_type', get_class($object))
                                   ->where('is_day_off', 0)
                                   ->where(function ($query) use ($selectTime) {
                                       $query->where('opens_at', '<=', $selectTime);
                                       $query->where('closes_at', '>=', $selectTime);
                                   })
                                   ->first();

            if ( ! is_null($timeSlot)) {
                $workingHours['isOpen'] = true;
            } else {
                $workingHours['isOpen'] = false;
            }
        }
        foreach ($object->workingHours as $workingHour) {
            if ($workingHour->is_day_off == 1) {
                $workingHours['offs'][] = trans('strings.working_day_'.$workingHour->day);
            } else {
                // set opens at or closes at value from today shifts
                $daysShifts = $object->workingHours()->where('day', $dayNumber)->where('is_day_off',
                    0)->orderBy('opens_at')->get();
                $dayShifts = $daysShifts->groupBy('day')->get($dayNumber);


                if ( ! is_null($dayShifts)) {
                    $getOpenTimeShift = $dayShifts->where('opens_at', '<=', $selectTime)->where('closes_at', '>=',
                        $selectTime)->first();
                    if ( ! empty($getOpenTimeShift)) {
                        if (is_null($workingHours['closesAt'])) {
                            $workingHours['closesAt'] = Carbon::parse($getOpenTimeShift->closes_at)->format('H:i');
                        }
                    } else {
                        $firstNextOpenShift = $dayShifts->where('opens_at', '>=', $selectTime)->first();
                        if ( ! is_null($firstNextOpenShift) && is_null($workingHours['opensAt'])) {
                            $workingHours['opensAt'] = Carbon::parse($firstNextOpenShift->opens_at)->format('H:i');
                        }
                    }

                    // find next working day and set opens at value
                    if (is_null($workingHours['opensAt']) && is_null($workingHours['closesAt'])) {
                        $nextWorkingDayNumber = self::getNextWorkingDayNumber($dayNumber, $object->workingHours);
                        $nextDayShifts = $daysShifts->groupBy('day')->get($nextWorkingDayNumber);
                        if ( ! is_null($nextDayShifts) && ! is_null($nextDayShifts->first()) && is_null($workingHours['opensAt'])) {
                            $workingHours['opensAt'] = Carbon::parse($nextDayShifts->first()->opens_at)->format('H:i');
                        }
                    }
                }
            }
        }
        $workingHours['offsRendered'] = implode(' - ', $workingHours['offs']);

        $workingHours['schedule'] = WorkingHourResource::collection($object->workingHours);

        return $workingHours;
    }

    public static function numberOfDays(): Collection
    {
        $days = [
            'monday' => 1,
            'tuesday' => 2,
            'wednesday' => 3,
            'thursday' => 4,
            'friday' => 5,
            'saturday' => 6,
            'sunday' => 7,
        ];

        return collect($days);
    }

    public static function updateHourValue($value, $key)
    {
        $hour = $value[$key];
        if ( ! is_null($hour)) {
            if ($hour === '24hrs' || $hour === '2400') {
                return $key === 'open' ? '00:00' : '23:59';
            }
            if (empty($hour)) {
                if ($key === 'open') {
                    return config('defaults.workingHours.opensAt');
                } else {
                    return config('defaults.workingHours.closesAt');
                }
            }

            return substr_replace($hour, ':', 2, 0);
        } else {
            return null;
        }
    }
}
