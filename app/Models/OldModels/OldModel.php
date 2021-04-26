<?php

namespace App\Models\OldModels;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

abstract class OldModel extends Model
{
    protected $connection = 'mysql-old';

    protected static function booted()
    {
        static::addGlobalScope('not-ancient', function (Builder $builder) {
            $beginsAt = Carbon::parse('2020-12-25')->setTimeFromTimeString('00:00');
            $builder->where('created_at', '>=', $beginsAt);
        });
    }

    public function validateLatLong($lat, $long)
    {
        $regex = '/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?),[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/';

        return preg_match($regex, $lat.','.$long);
    }

    public function getUuidString($min = 10, $max = 99): string
    {
        return Controller::uuid().mt_rand($min, $max);
    }
}
