<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductOptionTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['group_title', 'option_title'];
}
