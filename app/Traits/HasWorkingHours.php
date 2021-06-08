<?php

namespace App\Traits;

use App\Http\Controllers\Controller;
use App\Models\WorkingHour;
use Illuminate\Support\Collection;

trait HasWorkingHours
{
    public function getWorkingHours()
    {
        return $this->workingHours()->count() ? $this->workingHours : $this->getDefaultWorkingHours();
    }

    public static function getDefaultWorkingHours()
    {
        $days = collect([]);
        foreach (range(1, 7) as $dayNumber) {
            $workingHour = new WorkingHour();
            $workingHour->workable_type = self::class;
            $workingHour->day = $dayNumber;
            $workingHour->opens_at = config('defaults.workingHours.opensAt');
            $workingHour->closes_at = config('defaults.workingHours.closesAt');
            if (in_array($dayNumber, config('defaults.workingHours.weekends'))) {
                $workingHour->is_day_off = true;
            } else {
                $workingHour->is_day_off = false;
            }
            $days->push($workingHour);
        }

        return $days;
    }

    public function getWorkingHoursForJs(): Collection
    {
        $tempWorkingHours = [];
        foreach ($this->getWorkingHours() as $item) {
            $openHour = \Str::of($item->opens_at)->beforeLast(':')->replace(':', '')->jsonSerialize();
            $closeHour = \Str::of($item->closes_at)->beforeLast(':')->replace(':', '')->jsonSerialize();
            $tempWorkingHours[WorkingHour::numberOfDays()->flip()[$item->day]][] = [
                'id' => $item->id ?: 'new_'.Controller::uuid(),
                'open' => $openHour,
                'close' => $closeHour === '2359' || $closeHour === '0000' ? '2400' : $closeHour,
                'isOpen' => !$item->is_day_off
            ];
        }

        return collect($tempWorkingHours);
    }

    public function isOpenNow($object, $workingHoursType = 'main'): array
    {
        $dayNumber = now()->format('N');
        $nowTime = now()->format('H:i:s');
        $closesAtToday = null;

        $timeSlot = WorkingHour::whereType($workingHoursType)
                               ->where('day', $dayNumber)
                               ->where('workable_type', get_class($object))
                               ->whereWorkableId($object->id)
                               ->where('is_day_off', 0)
                               ->where(function ($query) use ($nowTime) {
                                   $query->where('opens_at', '<=', $nowTime);
                                   $query->where('closes_at', '>=', $nowTime);
                               })
                               ->first();
        if (is_null($timeSlot)) {
            $isOffNow = true;
        } else {
            $isOffNow = false;
            $closesAtToday = $timeSlot->closes_at;
        }
        return [$isOffNow, $closesAtToday];
    }
}
