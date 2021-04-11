<?php

namespace App\Models\OldModels;

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
}
