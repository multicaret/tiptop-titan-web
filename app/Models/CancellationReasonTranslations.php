<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CancellationReasonTranslations extends Model
{
    public $timestamps = false;
    protected $fillable = ['reason', 'description'];

}
