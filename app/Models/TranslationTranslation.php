<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TranslationTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['value'];
}
