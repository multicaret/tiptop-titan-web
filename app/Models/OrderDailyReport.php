<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDailyReport extends Model
{
    protected $casts = [
        'day' => 'date',
        'is_peak_this_month' => 'boolean',
        'is_nadir_this_month' => 'boolean',
        'is_peak_this_quarter' => 'boolean',
        'is_nadir_this_quarter' => 'boolean',
        'is_peak_this_year' => 'boolean',
        'is_nadir_this_year' => 'boolean',
    ];
}
