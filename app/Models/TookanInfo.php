<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TookanInfo extends Model
{
    use HasFactory;

    protected $table = 'tookans';

    protected $guarded = [];

    public function tookanable()
    {
        return $this->morphTo();
    }
}
