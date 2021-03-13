<?php

namespace App\Models;

use App\Http\Resources\WorkingHourResource;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class WorkingHour extends Model
{
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
            }
        }
        $workingHours['schedule'] = WorkingHourResource::collection($object->workingHours);

        return $workingHours;
    }
}
