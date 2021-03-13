<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Timezone extends Model
{
    public $timestamps = false;

    public function cities()
    {
        return $this->hasMany(City::class);
    }
}
