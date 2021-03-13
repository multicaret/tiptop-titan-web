<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethodTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['title', 'description', 'instructions'];
}
