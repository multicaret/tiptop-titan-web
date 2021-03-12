<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ChainProduct extends Pivot
{
    protected $casts = [
        'is_storage_tracking_enabled' => 'boolean',
    ];

}
