<?php

namespace App\Models\OldModels;


use App\Models\Branch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;

class OldWorkingHoursForBranch extends Model
{

    protected $table = 'jo3aan_branches_opening_hours';
    protected $primaryKey = 'id';

    public function attributesBuilder($index, $status)
    {
        $keysBuilder = [
            0 => [
                'opensAt' => 'sun_opening_time',
                'closesAt' => 'sun_closing_time',
            ],
            1 => [
                'opensAt' => 'mon_opening_time',
                'closesAt' => 'mon_closing_time',
            ],
            2 => [
                'opensAt' => 'tue_opening_time',
                'closesAt' => 'tue_closing_time',
            ],
            3 => [
                'opensAt' => 'wed_opening_time',
                'closesAt' => 'wed_closing_time',
            ],
            4 => [
                'opensAt' => 'thu_opening_time',
                'closesAt' => 'thu_closing_time',
            ],
            5 => [
                'opensAt' => 'fri_opening_time',
                'closesAt' => 'fri_closing_time',
            ],
            6 => [
                'opensAt' => 'sat_opening_time',
                'closesAt' => 'sat_closing_time',
            ],
        ];
        $timeKey = $keysBuilder[$index][$status];
        return $this->$timeKey;
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(OldBranch::class, 'branch_id');
    }

    public function loadWorkingHours(): Collection
    {
        $days = collect([]);
        foreach (range(0, 6) as $dayNumber) {
            $workingHour = collect([]);
            $workingHour->workable_type = Branch::class;
            $workingHour->day = $dayNumber;
            $workingHour->opens_at = $this->attributesBuilder($dayNumber, 'opensAt');
            $workingHour->closes_at = $this->attributesBuilder($dayNumber, 'closesAt');
            $workingHour->is_day_off = is_null($workingHour->opens_at);
            $days->push($workingHour);
        }

        return $days;
    }
}
