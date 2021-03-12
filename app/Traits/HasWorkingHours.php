<?php

namespace App\Traits;

use App\Models\WorkingHour;

trait HasWorkingHours
{
    public function getWorkingHours()
    {
        return $this->workingHours()->count() ? $this->workingHours : $this->getDefaultWorkingHours();
    }

    public static function getDefaultWorkingHours()
    {
        $days = collect([]);
        foreach (range(0, 6) as $dayNumber) {
            $workingHour = new WorkingHour();
            $workingHour->workable_type = self::class;
            $workingHour->day = $dayNumber;
            $workingHour->opens_at = config('defaults.workingHours.opensAt');
            $workingHour->closes_at = config('defaults.workingHours.closesAt');
            if ($dayNumber == 4 || $dayNumber == 5) {
                $workingHour->is_day_off = 1;
            } else {
                $workingHour->is_day_off = 0;
            }
            $days->push($workingHour);
        }

        return $days;
    }
}
